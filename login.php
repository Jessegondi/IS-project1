<?php
require __DIR__ . '/db.php';
$title = 'Log in';
$error = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id, username, full_name, password_hash FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Incorrect username or password.';
}
include __DIR__ . '/header.php';
?>
<main><div class="card">
  <h1>Log in</h1>
  <?php if ($flash): ?><div class="msg ok"><?= e($flash) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="msg err"><?= e($error) ?></div><?php endif; ?>
  <form method="post" action="login.php">
    <label for="username">Username</label>
    <input id="username" name="username" required autofocus>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    <button type="submit">Log in</button>
  </form>
  <p class="hint">No account yet? <a href="signup.php">Sign up</a></p>
</div></main>
</body></html>
