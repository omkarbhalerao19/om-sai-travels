<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'Contact';

$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $msg = trim($_POST['message'] ?? '');

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $subject && $msg) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $msg]);
        $sent = true;
    }
}
include 'includes/header.php';
?>

<section class="mini-hero">
  <div class="container">
    <h1>Get in touch</h1>
    <p class="breadcrumbs"><a href="index.php">Home</a> &raquo; Contact</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <h3 class="mb-3">We'd love to hear from you</h3>
        <p class="text-muted">Questions about your booking, group bookings, charter buses or careers — write to us, and we'll respond within 24 hours.</p>
        <ul class="list-unstyled mt-4">
          <li class="mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i> 23, Shivaji Nagar, Pune, Maharashtra 411005</li>
          <li class="mb-3"><i class="bi bi-telephone-fill text-danger me-2"></i> +91 98765 43210 / +91 99887 76655</li>
          <li class="mb-3"><i class="bi bi-envelope-fill text-danger me-2"></i> book@omsaitravels.in</li>
          <li><i class="bi bi-clock-fill text-danger me-2"></i> 24x7 Customer Support</li>
        </ul>
      </div>
      <div class="col-lg-7">
        <div class="bus-card">
          <h5 class="mb-3"><i class="bi bi-chat-dots text-primary me-2"></i>Send us a message</h5>
          <?php if ($sent): ?>
            <div class="alert alert-success">Thanks! Your message was sent successfully. We'll be in touch soon.</div>
          <?php endif; ?>
          <form method="post" class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-semibold">Name</label>
              <input type="text" name="name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label small fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" required></div>
            <div class="col-12"><label class="form-label small fw-semibold">Subject</label>
              <input type="text" name="subject" class="form-control" required></div>
            <div class="col-12"><label class="form-label small fw-semibold">Message</label>
              <textarea name="message" rows="5" class="form-control" required></textarea></div>
            <div class="col-12"><button class="btn btn-danger rounded-pill px-4">Send Message <i class="bi bi-send ms-1"></i></button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
