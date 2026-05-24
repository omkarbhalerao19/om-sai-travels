<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$page_title = 'Messages';

if (($_GET['action'] ?? '') === 'delete' && !empty($_GET['id'])) {
    $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([(int)$_GET['id']]);
    flash('success', 'Message deleted.');
    header('Location: messages.php'); exit;
}

$msgs = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
include __DIR__ . '/_header.php';
?>

<div class="bus-card">
  <h5 class="mb-3">Customer Messages (<?= count($msgs) ?>)</h5>
  <?php if (!$msgs): ?>
    <p class="text-muted text-center py-4 mb-0">No messages received yet.</p>
  <?php else: foreach ($msgs as $m): ?>
    <div class="border rounded p-3 mb-3">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <strong><?= e($m['name']) ?></strong> <span class="text-muted small">&lt;<?= e($m['email']) ?>&gt;</span>
          <div class="small text-muted"><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></div>
        </div>
        <a class="btn btn-sm btn-outline-danger" href="messages.php?action=delete&id=<?= (int)$m['id'] ?>" onclick="return confirm('Delete this message?')"><i class="bi bi-trash"></i></a>
      </div>
      <h6 class="mt-2 mb-1"><?= e($m['subject']) ?></h6>
      <p class="mb-0 small"><?= nl2br(e($m['message'])) ?></p>
    </div>
  <?php endforeach; endif; ?>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
