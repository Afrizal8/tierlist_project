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
<h2>Register</h2>

<!-- Form untuk register user -->
<form action="<?= BASE_URL ?>../includes/controllers/auth_controller.php" method="post" class="form">
  <input type="hidden" name="action" value="register">
  <label>Username
    <input type="text" name="username" required>
  </label>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <label>Confirm Password
    <input type="password" name="password_confirm" required>
  </label>
  <button type="submit" class="btn primary">Register</button>
</form>

<p>Already have an account? <a href="<?= BASE_URL ?>login.php">Login</a></p>

</main>

<?php include __DIR__ . '/../includes/templates/footer.php'; ?>
