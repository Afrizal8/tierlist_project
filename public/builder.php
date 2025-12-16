<?php
session_start();
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/templates/header.php";
?>

<main class="container">

    <h2 class="page-title">Create Tier List</h2>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="flash flash-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="flash flash-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="top-input">
        <input type="text" id="title" placeholder="Title">
        <button id="saveBtn" class="btn primary">Save Tier List</button>
    </div>

    <textarea id="description" placeholder="Description"></textarea>

    <!-- Tier area -->
    <div class="tiers-box">
        <?php
        $tiers = [
            "S" => "#e57373",
            "A" => "#ffb74d",
            "B" => "#fff176",
            "C" => "#fff59d",
            "D" => "#aed581",
            "E" => "#81c784",
            "F" => "#4dd0e1"
        ];

        foreach ($tiers as $t => $color): ?>
            <div class="tier-row">
                <div class="tier-label" style="background: <?= $color ?>"><?= $t ?></div>
                <div class="tier-drop" data-tier="<?= $t ?>"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Add item -->
    <div class="item-controls">

        <label class="upload-btn">
            Upload Image(s)
            <input type="file" id="imageFiles" multiple accept="image/*">
        </label>

        <input type="text" id="newItemName" placeholder="Item name">
        <button id="addItemBtn" class="btn-dark">Add Item</button>

        <div class="thumbnail-select">
            <label for="thumbnailSelect">Choose thumbnail:</label>
            <select id="thumbnailSelect">
                <option value="">(none)</option>
            </select>
        </div>
    </div>

    <div class="item-pool" id="itemPool">
        Item Pool
    </div>

    <form id="tierForm"
          action="<?= BASE_URL ?>../includes/controllers/tier_controller.php"
          method="POST"
          enctype="multipart/form-data">

        <input type="hidden" name="action" value="create_tier">
        <input type="hidden" name="title">
        <input type="hidden" name="description">
        <input type="hidden" name="items_json" id="items_json">
        <input type="hidden" name="thumbnail_index" id="thumbnail_index">
    </form>

</main>

<?php require_once __DIR__ . "/../includes/templates/footer.php"; ?>
<!-- Load builder.js only here, at bottom -->
<script src="<?= BASE_URL ?>assets/js/builder.js?v=1"></script>
