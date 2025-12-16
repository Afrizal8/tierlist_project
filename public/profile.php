<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmtUser = $pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();

$stmtLists = $pdo->prepare("SELECT * FROM tier_lists WHERE user_id = ? ORDER BY created_at DESC");
$stmtLists->execute([$user_id]);
$lists = $stmtLists->fetchAll();

include __DIR__ . '/../includes/templates/header.php';
?>
<main class="container">

<h2>Profile: <?= e($user['username']); ?></h2>
<p>Email: <?= e($user['email']); ?></p>
<p>Member since: <?= e($user['created_at']); ?></p>

<h3>Tier Lists</h3>

<div class="card-grid">
  <?php foreach ($lists as $l): 
      $thumb = $l['thumbnail'] 
              ? BASE_URL . $l['thumbnail'] 
              : BASE_URL . "assets/img/no-thumb.png";
  ?>
    <a class="card" href="<?= BASE_URL ?>detail.php?id=<?= $l['id']; ?>">
        <img src="<?= $thumb ?>" class="card-thumb">
        <div class="card-title"><?= e($l['title']); ?></div>
    </a>
  <?php endforeach; ?>
</div>

</main>

<?php include __DIR__ . '/../includes/templates/footer.php'; ?>
