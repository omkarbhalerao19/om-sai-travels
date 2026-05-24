<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) { header('Location: dashboard.php'); exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$u]);
    $a = $stmt->fetch();
    if ($a && password_verify($p, $a['password_hash'])) {
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_name'] = $a['name'];
        header('Location: dashboard.php');
        exit;
    }
    $err = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"><title>Admin Login | Om Sai Travels</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<style>
  body {
    min-height:100vh; display:flex; align-items:center;
    background: linear-gradient(135deg, var(--primary-dark), var(--accent));
  }
  .login-card { max-width: 420px; margin: 0 auto; }
</style>
</head>
<body>
<div class="container">
  <div class="login-card bg-white rounded-4 shadow p-4 p-md-5">
    <div class="text-center mb-4">
      <span class="brand-mark mb-2" style="width:56px;height:56px;font-size:1.5rem;"><i class="bi bi-bus-front-fill"></i></span>
      <h3 class="mt-2 mb-1">Admin Login</h3>
      <p class="text-muted small mb-0">Om Sai Travels Control Panel</p>
    </div>
    <?php if ($err): ?><div class="alert alert-danger small"><?= e($err) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label small fw-semibold">Username</label>
        <input name="username" class="form-control" placeholder="admin" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-danger w-100 rounded-pill"><i class="bi bi-box-arrow-in-right me-1"></i> Login</button>
    </form>
    <div class="text-center mt-3">
      <a href="../index.php" class="small text-muted"><i class="bi bi-arrow-left me-1"></i>Back to website</a>
    </div>
    <div class="alert alert-info small mt-3 mb-0">
      <strong>Default credentials:</strong> admin / admin@123
    </div>
  </div>
</div>
</body></html>
