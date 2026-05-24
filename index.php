<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'Home';

// Distinct cities for dropdowns
$cities = $pdo->query("SELECT DISTINCT origin AS city FROM routes UNION SELECT DISTINCT destination FROM routes ORDER BY city")
              ->fetchAll(PDO::FETCH_COLUMN);

// Popular routes for cards
$popular = $pdo->query("SELECT origin, destination, MIN(fare) AS min_fare FROM routes WHERE active=1 GROUP BY origin, destination ORDER BY MIN(fare) ASC LIMIT 6")
               ->fetchAll();

$routeImages = [
  'Pune-Parbhani'   => 'https://images.unsplash.com/photo-1602557097648-3d2c00d4dd1f?auto=format&fit=crop&w=900&q=80',
  'Mumbai-Pune'     => 'https://images.unsplash.com/photo-1567157577867-05ccb1388e66?auto=format&fit=crop&w=900&q=80',
  'Pune-Mumbai'     => 'https://images.unsplash.com/photo-1567157577867-05ccb1388e66?auto=format&fit=crop&w=900&q=80',
  'Pune-Aurangabad' => 'https://images.unsplash.com/photo-1609155382994-c8d2f5934d6a?auto=format&fit=crop&w=900&q=80',
  'Pune-Nashik'     => 'https://images.unsplash.com/photo-1587974928442-77dc3e0dba72?auto=format&fit=crop&w=900&q=80',
  'Pune-Kolhapur'   => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=900&q=80',
  'Pune-Solapur'    => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=900&q=80',
  'Pune-Latur'      => 'https://images.unsplash.com/photo-1502920514313-52581002a659?auto=format&fit=crop&w=900&q=80',
  'Mumbai-Aurangabad'=>'https://images.unsplash.com/photo-1605649461784-edc01e6b4cb3?auto=format&fit=crop&w=900&q=80',
  'Mumbai-Nashik'   => 'https://images.unsplash.com/photo-1564013434775-f71db0030976?auto=format&fit=crop&w=900&q=80',
  'Nashik-Nagpur'   => 'https://images.unsplash.com/photo-1502920514313-52581002a659?auto=format&fit=crop&w=900&q=80',
  'Aurangabad-Nagpur'=>'https://images.unsplash.com/photo-1502920514313-52581002a659?auto=format&fit=crop&w=900&q=80',
  'Pune-Nagpur'     => 'https://images.unsplash.com/photo-1583407723467-9b2d22504831?auto=format&fit=crop&w=900&q=80',
];
function img_for($o, $d, $imgs) {
  $key = "$o-$d";
  return $imgs[$key] ?? 'https://images.unsplash.com/photo-1502920514313-52581002a659?auto=format&fit=crop&w=900&q=80';
}

include 'includes/header.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="container py-5">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <p class="text-uppercase fw-semibold mb-2" style="letter-spacing:3px;"><span class="divider-dot me-2"></span> Maharashtra's Trusted Bus Service</p>
        <h1 class="mb-3">Travel smarter. Reach happier.</h1>
        <p class="lead mb-4">Book comfortable AC sleepers, Volvo coaches and seater buses across Pune, Mumbai, Parbhani, Aurangabad, Nagpur and more — with guaranteed seats and on-time service.</p>
        <div class="d-flex gap-3">
          <a href="#search" class="btn btn-danger btn-lg rounded-pill px-4"><i class="bi bi-search me-2"></i>Find buses</a>
          <a href="routes.php" class="btn btn-outline-light btn-lg rounded-pill px-4">View routes</a>
        </div>
      </div>
      <div class="col-lg-6" id="search">
        <div class="search-card">
          <h5 class="mb-3 fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Where are you going?</h5>
          <form action="routes.php" method="get" class="row g-3">
            <div class="col-md-6">
              <label>From</label>
              <select name="from" class="form-select" required>
                <option value="">Select origin</option>
                <?php foreach ($cities as $c): ?>
                  <option value="<?= e($c) ?>"><?= e($c) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label>To</label>
              <select name="to" class="form-select" required>
                <option value="">Select destination</option>
                <?php foreach ($cities as $c): ?>
                  <option value="<?= e($c) ?>"><?= e($c) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label>Travel Date</label>
              <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
              <label>Passengers</label>
              <select name="pax" class="form-select">
                <?php for ($i=1; $i<=6; $i++) echo "<option value='$i'>$i Passenger".($i>1?'s':'')."</option>"; ?>
              </select>
            </div>
            <div class="col-12 d-grid">
              <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-bus-front me-2"></i>Search Buses</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Why ride with us</span>
      <h2>Comfort &amp; reliability on every trip</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-shield-check"></i></div>
          <h5>Safe &amp; Verified</h5>
          <p class="text-muted small mb-0">All buses RTO-verified with trained drivers and GPS tracking on every route.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-clock-history"></i></div>
          <h5>On-Time Service</h5>
          <p class="text-muted small mb-0">98% on-time arrival across our Pune, Mumbai &amp; Marathwada network.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-cash-coin"></i></div>
          <h5>Best Fares</h5>
          <p class="text-muted small mb-0">Transparent pricing with dynamic off-peak discounts up to 5% lower.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="icon"><i class="bi bi-headset"></i></div>
          <h5>24×7 Support</h5>
          <p class="text-muted small mb-0">Real humans available around the clock for booking help &amp; cancellations.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- POPULAR ROUTES -->
<section class="section bg-soft">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Most travelled</span>
      <h2>Popular Maharashtra routes</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($popular as $p): ?>
        <div class="col-md-6 col-lg-4">
          <a href="routes.php?from=<?= urlencode($p['origin']) ?>&to=<?= urlencode($p['destination']) ?>" class="text-decoration-none text-dark">
            <div class="route-card position-relative h-100">
              <div class="image" style="background-image:url('<?= e(img_for($p['origin'],$p['destination'],$routeImages)) ?>')"></div>
              <span class="badge-fare">From <?= rupees($p['min_fare']) ?></span>
              <div class="p-4">
                <h5 class="mb-1"><?= e($p['origin']) ?> <i class="bi bi-arrow-right text-danger mx-1"></i> <?= e($p['destination']) ?></h5>
                <p class="text-muted small mb-3"><i class="bi bi-bus-front me-1"></i> AC Sleeper · Volvo · Seater available</p>
                <span class="btn btn-sm btn-outline-primary rounded-pill">View buses <i class="bi bi-arrow-right ms-1"></i></span>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section">
  <div class="container">
    <div class="section-heading">
      <span class="eyebrow">Simple process</span>
      <h2>Book your ticket in 3 easy steps</h2>
    </div>
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon mx-auto"><i class="bi bi-1-circle-fill"></i></div>
          <h5>Search</h5>
          <p class="text-muted small">Enter your origin, destination &amp; travel date.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon mx-auto"><i class="bi bi-2-circle-fill"></i></div>
          <h5>Choose</h5>
          <p class="text-muted small">Pick the bus and seats that fit your plan.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="icon mx-auto"><i class="bi bi-3-circle-fill"></i></div>
          <h5>Travel</h5>
          <p class="text-muted small">Get a PNR instantly &amp; board on the day of travel.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section bg-soft">
  <div class="container">
    <div class="row align-items-center g-4 p-4 p-lg-5 rounded-4" style="background: linear-gradient(135deg, var(--primary-dark), var(--accent)); color:#fff;">
      <div class="col-lg-8">
        <h2 class="text-white mb-2">Group bookings &amp; charter buses</h2>
        <p class="mb-0 text-white-50">Planning a wedding, college trip or pilgrimage? We offer special rates for groups of 20+ passengers across Maharashtra.</p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="contact.php" class="btn btn-light btn-lg rounded-pill px-4">Get a quote <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
