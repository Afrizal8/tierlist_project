<?php
require_once __DIR__ . '/../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu.";
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_tier') {

    $title = trim($_POST['title'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $items_json = $_POST['items_json'] ?? "[]";
    $thumbnail_index = ($_POST['thumbnail_index'] !== "") ? intval($_POST['thumbnail_index']) : null;

    if ($title === "") {
        $_SESSION['error'] = "Judul wajib diisi.";
        header("Location: " . BASE_URL . "builder.php");
        exit;
    }

    $items = json_decode($items_json, true);
    if (!is_array($items)) $items = [];

    $uploadBase = __DIR__ . '/../../public/assets/uploads/';
    if (!is_dir($uploadBase)) mkdir($uploadBase, 0755, true);

    try {
        $pdo->beginTransaction();

        // Insert tier list dulu
        $stmt = $pdo->prepare("INSERT INTO tier_lists (user_id, title, description, created_at) VALUES (?,?,?,NOW())");
        $stmt->execute([$user_id, $title, $description]);
        $tier_list_id = $pdo->lastInsertId();

        // Folder upload untuk tier list ini
        $targetDir = $uploadBase . $tier_list_id . "/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        /* ===== Simpan file upload ===== */
        $uploadedPaths = []; // index => path relatif
        if (!empty($_FILES["images"]) && is_array($_FILES["images"]["name"])) {
            foreach ($_FILES["images"]["name"] as $i => $origName) {
                if ($_FILES["images"]["error"][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($origName, PATHINFO_EXTENSION);
                    $newName = time() . "_" . bin2hex(random_bytes(8)) . "." . $ext;
                    $destFull = $targetDir . $newName;

                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $destFull)) {
                        // simpan path relatif (tanpa public/)
                        $uploadedPaths[$i] = "assets/uploads/" . $tier_list_id . "/" . $newName;
                    } else {
                        $uploadedPaths[$i] = null;
                    }
                }
            }
        }

        /* ===== Tentukan thumbnail ===== */
        $thumbnailPath = null;
        if ($thumbnail_index !== null && isset($uploadedPaths[$thumbnail_index])) {
            $thumbnailPath = $uploadedPaths[$thumbnail_index];
            $pdo->prepare("UPDATE tier_lists SET thumbnail=? WHERE id=?")
                ->execute([$thumbnailPath, $tier_list_id]);
        }

        /* ===== Insert items ===== */
        $stmtItem = $pdo->prepare("INSERT INTO tier_items (tier_list_id, item_name, tier_level, image_path, created_at) VALUES (?,?,?,?,NOW())");

        foreach ($items as $it) {
            $name = $it["name"] ?? "";
            $tier = $it["tier"] ?? null;
            $idx = isset($it["fileIndex"]) ? intval($it["fileIndex"]) : null;
            $imgPath = ($idx !== null && isset($uploadedPaths[$idx])) ? $uploadedPaths[$idx] : null;

            if ($name !== "" && $tier) {
                $stmtItem->execute([$tier_list_id, $name, $tier, $imgPath]);
            }
        }

        $pdo->commit();
        $_SESSION['success'] = "Tier list berhasil disimpan.";
        header("Location: " . BASE_URL . "detail.php?id=" . $tier_list_id);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: " . BASE_URL . "builder.php");
        exit;
    }
}
