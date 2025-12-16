<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>TIER LIST BUILDER</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/builder.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <h1 class="brand"><a href="<?= BASE_URL ?>index.php">Tier List Builder</a></h1>
      <nav class="main-nav">
        <a href="<?= BASE_URL ?>index.php">Home</a>
        <a href="<?= BASE_URL ?>browse.php">Browse</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= BASE_URL ?>builder.php">Build Tier List</a>
          <a href="<?= BASE_URL ?>profile.php">Profile</a>
          <a href="<?= BASE_URL ?>logout.php">Logout</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>login.php">Login</a>
          <a href="<?= BASE_URL ?>register.php">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
