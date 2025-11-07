<?php
require_once 'auth.php';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $user = function_exists('getUserByUsername') ? getUserByUsername($username) : null;
        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Invalid username or password.';
        } else {
            // set session
            $_SESSION['user_id'] = $user['userID'] ?? $user['userId'] ?? $username;
            $_SESSION['username'] = $user['userID'] ?? $user['userId'] ?? $username;
            header('Location: homepage.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Sign In — TopShelf</title>
  <style>
    /* copied page styles (keeps the same look as homepage) */
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:#f5f5f5}
    header{background:#fff;padding:1rem 2rem;box-shadow:0 1px 3px rgba(0,0,0,.1)}
    nav{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto}
    .container{max-width:900px;margin:0 auto;padding:3rem 2rem}
    .hero{background:#f0f0f0;padding:4rem 2rem;text-align:center}
    .card{background:#fff;border:1px solid #e6e6e6;border-radius:8px;padding:2rem;box-shadow:0 1px 2px rgba(0,0,0,.03)}
    .form-row{margin-bottom:1rem}
    label{display:block;font-size:0.95rem;color:#333;margin-bottom:0.4rem}
    input[type="text"],input[type="password"]{width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px}
    button.primary{padding:0.6rem 1rem;border-radius:6px;border:none;background:#333;color:#fff;cursor:pointer}
    .error{color:#721c24;background:#f8d7da;padding:0.6rem;border-radius:6px;margin-bottom:1rem}
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="hero">
    <h1>Sign In</h1>
  </section>

  <div class="container">
    <div class="card" style="max-width:480px;margin:0 auto">
      <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="post" action="login.php" novalidate>
        <div class="form-row">
          <label for="username">Username</label>
          <input id="username" name="username" type="text" required value="<?php echo htmlspecialchars($username); ?>"/>
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" required/>
        </div>

        <div style="display:flex;gap:0.5rem;align-items:center;justify-content:flex-end">
          <a href="register.php" style="color:#333;text-decoration:none">Create account</a>
          <button type="submit" class="primary">Sign In</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>