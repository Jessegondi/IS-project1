<?php
require __DIR__ . '/db.php';
$title = 'Sign up';
$errors = [];
$v = ['username'=>'','full_name'=>'','email'=>'','phone'=>'','course'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) { $v[$k] = trim($_POST[$k] ?? ''); }
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $v['username'])) $errors[] = 'Username must be 3-50 letters, numbers or underscores.';
    if ($v['full_name'] === '') $errors[] = 'Full name is required.';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $v['username'], $v['email']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'That username or email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare('INSERT INTO users (username, full_name, email, phone, course, password_hash) VALUES (?,?,?,?,?,?)');
            $ins->bind_param('ssssss', $v['username'], $v['full_name'], $v['email'], $v['phone'], $v['course'], $hash);
            $ins->execute();
            $_SESSION['flash'] = 'Account created. You can log in now.';
            header('Location: login.php');
            exit;
        }
    }
}
include __DIR__ . '/header.php';
?>
<main><div class="card">
  <h1>Create your account</h1>
  <?php foreach ($errors as $err): ?><div class="msg err"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" action="signup.php">
    <label for="username">Username</label>
    <input id="username" name="username" value="<?= e($v['username']) ?>" required>
    <label for="full_name">Full name</label>
    <input id="full_name" name="full_name" value="<?= e($v['full_name']) ?>" required>
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="<?= e($v['email']) ?>" required>
    <label for="phone">Phone (optional)</label>
    <input id="phone" name="phone" value="<?= e($v['phone']) ?>">
    <label for="course">Course (optional)</label>
    <input id="course" name="course" value="<?= e($v['course']) ?>">
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required minlength="8">
    <label for="confirm">Confirm password</label>
    <input id="confirm" type="password" name="confirm" required minlength="8">
    <button type="submit">Create account</button>
  </form>
  <p class="hint">Already registered? <a href="login.php">Log in</a></p>
</div></main>
</body></html>
