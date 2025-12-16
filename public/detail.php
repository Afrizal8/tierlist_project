<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "browse.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*, u.username 
    FROM tier_lists t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.id = ?
");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    $_SESSION['error'] = "Tier list tidak ditemukan.";
    header("Location: " . BASE_URL . "browse.php");
    exit;
}

$stmtItems = $pdo->prepare("
  SELECT * FROM tier_items 
  WHERE tier_list_id = ? 
  ORDER BY FIELD(tier_level,'S','A','B','C','D','E','F'), id ASC
");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

$tiers = ['S'=>[],'A'=>[],'B'=>[],'C'=>[],'D'=>[],'E'=>[],'F'=>[]];
foreach ($items as $it) $tiers[$it['tier_level']][] = $it;

include __DIR__ . '/../includes/templates/header.php';
?>
<main class="container">

<h2 class="page-title"><?= e($t['title']); ?></h2>
<p class="muted">By <strong><?= e($t['username']); ?></strong> — <?= e($t['created_at']); ?></p>
<p><?= nl2br(e($t['description'])); ?></p>

<!-- Tier Display -->
<div class="tiers-box detail-view">
    <?php
    $colors = [
        "S" => "#e57373",
        "A" => "#ffb74d",
        "B" => "#fff176",
        "C" => "#fff59d",
        "D" => "#aed581",
        "E" => "#81c784",
        "F" => "#4dd0e1"
    ];
    foreach ($colors as $tier => $color): ?>
        <div class="tier-row">
            <div class="tier-label" style="background: <?= $color ?>"><?= $tier ?></div>
            <div class="tier-drop readonly">
                <?php foreach ($tiers[$tier] as $it): ?>
                    <div class="tier-card">
                        <?php if ($it['image_path']): ?>
                            <img src="<?= BASE_URL . $it['image_path']; ?>" alt="" class="tier-card-img">
                        <?php endif; ?>
                        <span class="tier-card-title"><?= e($it['item_name']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</main>
<?php include __DIR__ . '/../includes/templates/footer.php'; ?>
