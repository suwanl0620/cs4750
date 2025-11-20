<?php
require_once 'auth.php';
?>
<header>
  <nav>
    <div class="logo"><a href="homepage.php" style="text-decoration:none;color:inherit;">📚 TopShelf</a></div>

    <div class="nav-links">
      <a href="homepage.php">Home</a>
      <a href="my-reviews.php">My Reviews</a>
      <a href="homepage.php#about">About</a>
    </div>

    <div class="auth-buttons">
      <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
        <span style="margin-right:0.8rem;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? $_SESSION['user_id'] ?? ''); ?></span>
        <a href="logout.php" style="text-decoration:none;">
          <button style="padding:0.45rem 0.9rem;border-radius:4px;border:1px solid #333;background:#333;color:#fff;cursor:pointer;">Logout</button>
        </a>
      <?php else: ?>
        <a href="login.php" style="text-decoration:none;">
          <button style="padding:0.45rem 0.9rem;border-radius:4px;border:1px solid #333;background:#fff;cursor:pointer;">Sign In</button>
        </a>
        <a href="register.php" style="text-decoration:none;margin-left:0.5rem;">
          <button style="padding:0.45rem 0.9rem;border-radius:4px;border:1px solid #333;background:#333;color:#fff;cursor:pointer;">Register</button>
        </a>
      <?php endif; ?>
    </div>
  </nav>
</header>