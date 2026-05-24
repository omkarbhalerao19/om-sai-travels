<?php
require_once __DIR__ . '/../includes/functions.php';
$current_admin = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"><title><?= isset($page_title)?e($page_title).' | ':'' ?>Om Sai Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-side">
    <div class="brand d-flex align-items-center">
      <span class="brand-mark me-2"><i class="bi bi-bus-front-fill"></i></span>
      <span class="label fw-bold">Om Sai Admin</span>
    </div>
    <nav class="mt-3">
      <a href="dashboard.php" class="nav-item <?= $current_admin==='dashboard.php'?'active':'' ?>"><i class="bi bi-grid-1x2-fill me-2"></i><span class="label">Dashboard</span></a>
      <a href="routes.php"    class="nav-item <?= $current_admin==='routes.php'?'active':'' ?>"><i class="bi bi-bus-front me-2"></i><span class="label">Routes &amp; Fares</span></a>
      <a href="bookings.php"  class="nav-item <?= $current_admin==='bookings.php'?'active':'' ?>"><i class="bi bi-ticket-perforated-fill me-2"></i><span class="label">Bookings</span></a>
      <a href="messages.php"  class="nav-item <?= $current_admin==='messages.php'?'active':'' ?>"><i class="bi bi-chat-left-text-fill me-2"></i><span class="label">Messages</span></a>
      <a href="logout.php"    class="nav-item"><i class="bi bi-box-arrow-right me-2"></i><span class="label">Logout</span></a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0"><?= isset($page_title)?e($page_title):'' ?></h3>
      <div class="d-flex align-items-center">
        <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-globe me-1"></i> View Site</a>
        <span class="text-muted small">Hi, <strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong></span>
      </div>
    </div>
    <?= flash('') ?>
