<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit;
}
include __DIR__ . '/../includes/templates/header.php';
?>
<main class="container">
<h2>Login</h2>

<!-- Form untuk login user -->
<form action="<?= BASE_URL ?>../includes/controllers/auth_controller.php" method="post" class="form">
  <input type="hidden" name="action" value="login">
  <label>Email atau Username
    <input type="text" name="email_or_username" required>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button type="submit" class="btn primary">Login</button>
</form>

<p>No account? <a href="<?= BASE_URL ?>register.php">Create one</a></p>

</main>

<?php include __DIR__ . '/../includes/templates/footer.php'; ?>
