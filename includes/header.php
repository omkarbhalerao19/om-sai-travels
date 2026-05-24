<?php
require_once __DIR__ . '/functions.php';
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($page_title) ? e($page_title) . ' | ' : '' ?>Om Sai Travels</title>
  <meta name="description" content="Book bus tickets across Maharashtra with Om Sai Travels — Pune, Mumbai, Parbhani, Aurangabad, Nagpur and more.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Top info bar -->
<div class="top-bar">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="small"><i class="bi bi-telephone-fill me-2"></i>+91 98765 43210 &nbsp;|&nbsp; <i class="bi bi-envelope-fill me-2"></i>book@omsaitravels.in</div>
    <div class="small"><i class="bi bi-geo-alt-fill me-2"></i>Maharashtra, India &nbsp;|&nbsp; <i class="bi bi-clock me-2"></i>24x7 Customer Support</div>
  </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <span class="brand-mark me-2"><i class="bi bi-bus-front-fill"></i></span>
      <span class="fw-bold">Om Sai <span class="text-danger">Travels</span></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= $current==='index.php'?'active':'' ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='routes.php'?'active':'' ?>" href="routes.php">Routes</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='my-booking.php'?'active':'' ?>" href="my-booking.php">My Booking</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='about.php'?'active':'' ?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='contact.php'?'active':'' ?>" href="contact.php">Contact</a></li>
        <li class="nav-item ms-lg-3"><a class="btn btn-danger rounded-pill px-3" href="admin/login.php"><i class="bi bi-person-lock me-1"></i> Admin</a></li>
      </ul>
    </div>
  </div>
</nav>
