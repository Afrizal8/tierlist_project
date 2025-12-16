<?php
// includes/controllers/auth_controller.php
require_once __DIR__ . '/../config.php';
session_start();

// Simple helper
function redirect($to) {
    header("Location: " . BASE_URL . $to);
    exit;
}

// Basic input retrieval (sesuaikan nama field form registrasi kamu)
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

// Simple validation (sesuaikan kebutuhan)
if (!$username || !$email || !$password) {
    $_SESSION['error'] = "Mohon isi username, email, dan password.";
    redirect('register.php');
}

// Avoid duplicate registration (example)
$stmtChk = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmtChk->execute([$email]);
if ($stmtChk->fetch()) {
    $_SESSION['error'] = "Email sudah terdaftar.";
    redirect('register.php');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Start transaction to ensure consistency
    $pdo->beginTransaction();

    // 1) Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$username, $email, $passwordHash]);
    $user_id = $pdo->lastInsertId();

    // 2) Create default tier list for the new user
    $defaultTitle = "Demo Tier List";
    $defaultDesc = "Ini adalah tier list bawaan — kamu bisa buat tier list seperti ini.";
    $stmtTL = $pdo->prepare("INSERT INTO tier_lists (user_id, title, description, thumbnail, created_at) VALUES (?, ?, ?, NULL, NOW())");
    $stmtTL->execute([$user_id, $defaultTitle, $defaultDesc]);
    $tier_list_id = $pdo->lastInsertId();

    // 3) Prepare upload folders
    $publicDir = realpath(__DIR__ . '/../../public'); // path ke public
    if ($publicDir === false) {
        throw new Exception("Cannot resolve public directory path.");
    }

    $uploadBase = $publicDir . '/assets/uploads/';
    if (!is_dir($uploadBase)) mkdir($uploadBase, 0755, true);

    $targetDir = $uploadBase . $tier_list_id . '/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    // 4) Default images source folder (you must place default images here)
    $defaultsDir = $publicDir . '/assets/defaults/';
    // List default files: thumbnail + 4 items
    $defaultFiles = [
        'thumbnail' => 'default-thumb.jpg', // default thumbnail
        'items' => [
            'item1.jpg',
            'item2.jpg',
            'item3.jpg',
            'item4.jpg'
        ]
    ];

    // 5) Copy default thumbnail (if exists) and save path in tier_lists.thumbnail
    $thumbnailPath = null;
    $srcThumb = $defaultsDir . $defaultFiles['thumbnail'];
    if (is_file($srcThumb)) {
        $ext = pathinfo($srcThumb, PATHINFO_EXTENSION);
        $newName = time() . '_thumb_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $targetDir . $newName;
        if (!copy($srcThumb, $dest)) {
            // copy gagal -> just continue without thumbnail
        } else {
            // store path relative to public
            $thumbnailPath = 'assets/uploads/' . $tier_list_id . '/' . $newName;
            $stmtUpdateThumb = $pdo->prepare("UPDATE tier_lists SET thumbnail = ? WHERE id = ?");
            $stmtUpdateThumb->execute([$thumbnailPath, $tier_list_id]);
        }
    }

    // 6) Insert 4 default items (with images if available)
    $stmtItem = $pdo->prepare("INSERT INTO tier_items (tier_list_id, item_name, tier_level, image_path, created_at) VALUES (?, ?, ?, ?, NOW())");

    // Define default items (name + tier level)
    $defaultItems = [
        ['name' => 'Genghis Khan', 'tier' => 'S'],
        ['name' => 'Napoleon', 'tier' => 'S'],
        ['name' => 'Mao Zedong', 'tier' => 'B'],
        ['name' => 'Stalin', 'tier' => 'C']
    ];

    foreach ($defaultItems as $index => $di) {
        $imgRelPath = null;
        $srcItemFile = $defaultsDir . ($defaultFiles['items'][$index] ?? '');
        if (isset($defaultFiles['items'][$index]) && is_file($srcItemFile)) {
            $ext = pathinfo($srcItemFile, PATHINFO_EXTENSION);
            $newItemName = time() . '_item' . ($index+1) . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $destItem = $targetDir . $newItemName;
            if (@copy($srcItemFile, $destItem)) {
                $imgRelPath = 'assets/uploads/' . $tier_list_id . '/' . $newItemName;
            }
        }

        $stmtItem->execute([$tier_list_id, $di['name'], $di['tier'], $imgRelPath]);
    }

    // 7) Commit all
    $pdo->commit();

    // 8) Set session and redirect to homepage / auto login
    $_SESSION['user_id'] = $user_id;
    $_SESSION['success'] = "Registrasi berhasil — Default tier list telah dibuat.";
    redirect('index.php');

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Log error if you have logger, or show friendly message
    $_SESSION['error'] = "Terjadi error saat registrasi: " . $e->getMessage();
    redirect('register.php');
}
