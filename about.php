<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'About Us';
include 'includes/header.php';
?>

<section class="mini-hero">
  <div class="container">
    <h1>About Om Sai Travels</h1>
    <p class="breadcrumbs"><a href="index.php">Home</a> &raquo; About</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=900&q=80" class="img-fluid rounded-4 shadow" alt="Bus journey">
      </div>
      <div class="col-lg-6">
        <span class="eyebrow" style="color:var(--accent);font-weight:600;letter-spacing:3px;text-transform:uppercase;font-size:.8rem;">Our Story</span>
        <h2 class="mt-2 mb-3">Maharashtra's most-trusted bus operator since 2008</h2>
        <p class="text-muted">Om Sai Travels started with a single bus on the Pune–Parbhani route. Today, we operate 80+ buses across 30+ Maharashtra cities, having served over 5 lakh happy passengers.</p>
        <p class="text-muted">Our mission is simple: bring affordable, comfortable and on-time bus travel to every corner of Maharashtra — whether you're going home, on pilgrimage, or off to a wedding.</p>
        <div class="row g-3 mt-2">
          <div class="col-4 text-center"><h3 class="text-danger mb-0">80+</h3><small class="text-muted">Buses</small></div>
          <div class="col-4 text-center"><h3 class="text-danger mb-0">30+</h3><small class="text-muted">Cities</small></div>
          <div class="col-4 text-center"><h3 class="text-danger mb-0">5L+</h3><small class="text-muted">Happy travellers</small></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section bg-soft">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Our values</span>
      <h2>What drives us forward</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-heart-fill"></i></div>
          <h5>Passenger first</h5>
          <p class="text-muted small mb-0">Every decision starts with: "Is this better for our passenger?"</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-shield-fill-check"></i></div>
          <h5>Safety always</h5>
          <p class="text-muted small mb-0">Trained drivers, GPS tracking, regular vehicle maintenance.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-stars"></i></div>
          <h5>Honest pricing</h5>
          <p class="text-muted small mb-0">No hidden fees. Off-peak discounts shared with travellers.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
