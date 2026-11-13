<?php
require __DIR__ . '/db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$title = 'Dashboard';
$stmt = $conn->prepare('SELECT username, full_name, email, phone, course, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$me = $stmt->get_result()->fetch_assoc();
include __DIR__ . '/header.php';
?>
<main><div class="card">
  <h1>Welcome, <?= e($me['full_name']) ?></h1>
  <div class="msg ok">Login successful. You are signed in as <strong><?= e($me['username']) ?></strong>.</div>
  <table>
    <tr><th>Email</th><td><?= e($me['email']) ?></td></tr>
    <tr><th>Phone</th><td><?= e($me['phone'] ?: '-') ?></td></tr>
    <tr><th>Course</th><td><?= e($me['course'] ?: '-') ?></td></tr>
    <tr><th>Registered</th><td><?= e($me['created_at']) ?></td></tr>
  </table>
</div></main>
</body></html>
