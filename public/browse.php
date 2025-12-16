<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($keyword) {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username
        FROM tier_lists t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.title LIKE ?
        ORDER BY t.created_at DESC
    ");
    $stmt->execute(["%$keyword%"]);
} else {
    $stmt = $pdo->query("
        SELECT t.*, u.username 
        FROM tier_lists t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC
    ");
}

$tierlists = $stmt->fetchAll();


include __DIR__ . '/../includes/templates/header.php';
?>

<main class="container">
  <h2>Browse Tier Lists</h2>
<form method="GET" class="searchbar">
    <input type="text" name="q" placeholder="Search title..." value="<?= e($_GET['q'] ?? '') ?>">
    <button type="submit">Search</button>
</form>

<div class="card-grid">
  <?php foreach ($tierlists as $t): 
      $thumb = $t['thumbnail'] 
              ? BASE_URL . $t['thumbnail'] 
              : BASE_URL . "assets/img/no-thumb.png";
  ?>
    <a class="card" href="<?= BASE_URL ?>detail.php?id=<?= $t['id']; ?>">
        <img src="<?= $thumb ?>" class="card-thumb">
        <div class="card-title"><?= e($t['title']); ?></div>
    </a>
  <?php endforeach; ?>
</div>

</main>




<?php include __DIR__ . '/../includes/templates/footer.php'; ?>
