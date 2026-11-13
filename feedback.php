<?php
require __DIR__ . '/db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$title = 'Feedback';
$errors = [];
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $rating  = (int)($_POST['rating'] ?? 0);
    if ($subject === '') $errors[] = 'Subject is required.';
    if ($message === '') $errors[] = 'Message is required.';
    if ($rating < 1 || $rating > 5) $errors[] = 'Choose a rating from 1 to 5.';
    if (!$errors) {
        $stmt = $conn->prepare('INSERT INTO feedback (user_id, subject, message, rating) VALUES (?,?,?,?)');
        $stmt->bind_param('issi', $_SESSION['user_id'], $subject, $message, $rating);
        $stmt->execute();
        $ok = 'Feedback saved.';
    }
}

$rows = $conn->query('SELECT f.id, u.username, f.subject, f.message, f.rating, f.submitted_at
                      FROM feedback f JOIN users u ON u.id = f.user_id
                      ORDER BY f.id DESC')->fetch_all(MYSQLI_ASSOC);
include __DIR__ . '/header.php';
?>
<main class="wide">
  <div class="card">
    <h1>Send feedback</h1>
    <?php if ($ok): ?><div class="msg ok"><?= e($ok) ?></div><?php endif; ?>
    <?php foreach ($errors as $err): ?><div class="msg err"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" action="feedback.php">
      <label for="subject">Subject</label>
      <input id="subject" name="subject" required>
      <label for="message">Message</label>
      <textarea id="message" name="message" rows="4" required></textarea>
      <label for="rating">Rating</label>
      <select id="rating" name="rating" required>
        <option value="">Choose...</option>
        <?php for ($i = 1; $i <= 5; $i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
      </select>
      <button type="submit">Save feedback</button>
    </form>
  </div>
  <div class="card">
    <h1>Saved feedback</h1>
    <?php if (!$rows): ?>
      <p class="hint">Nothing saved yet. Submit the form above to add the first entry.</p>
    <?php else: ?>
    <div class="table-wrap"><table>
      <tr><th>ID</th><th>User</th><th>Subject</th><th>Message</th><th>Rating</th><th>Submitted</th></tr>
      <?php foreach ($rows as $r): ?>
      <tr><td><?= e($r['id']) ?></td><td><?= e($r['username']) ?></td><td><?= e($r['subject']) ?></td>
          <td><?= e($r['message']) ?></td><td><?= e($r['rating']) ?></td><td><?= e($r['submitted_at']) ?></td></tr>
      <?php endforeach; ?>
    </table></div>
    <?php endif; ?>
  </div>
</main>
</body></html>
