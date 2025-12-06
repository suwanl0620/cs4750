<?php
require_once 'auth.php';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (function_exists('getUserByUsername') && getUserByUsername($username)) {
        $error = 'That username is already taken. Please choose another.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (function_exists('createUser') && createUser($username, $hash)) {
            $_SESSION['user_id'] = $user['userID'] ?? $user['userId'] ?? $username;
            $_SESSION['username'] = $user['userID'] ?? $user['userId'] ?? $username;
            header('Location: homepage.php');
            exit;
        } else {
            $error = 'Failed to create user. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Register — TopShelf</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="hero">
    <h1>Create an Account</h1>
  </section>

  <div class="container">
    <?php if ($error): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
  </div>

  <div class="login-container"> 

    <form method="post" action="register.php" novalidate>
      <div class="form-group">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required value="<?php echo htmlspecialchars($username); ?>"/>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required/>
      </div>

      <div class="form-actions">
        <button type="submit" style="padding:0.45rem 0.9rem;border-radius:4px;border:1px solid #333;background:#333;color:#fff;cursor:pointer;">Register</button>
      </div>
    </form>
  </div>
</body>
</html>