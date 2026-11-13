<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'IS Project 1') ?></title>
<link rel="stylesheet" href="style.css">
</head><body>
<nav>
  <span class="brand">IS Project 1</span>
  <?php if (!empty($_SESSION['user_id'])): ?>
    <a href="dashboard.php">Dashboard</a>
    <a href="feedback.php">Feedback</a>
    <a href="logout.php">Log out</a>
  <?php else: ?>
    <a href="login.php">Log in</a>
    <a href="signup.php">Sign up</a>
  <?php endif; ?>
</nav>
