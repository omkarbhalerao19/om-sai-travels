<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'My Booking';

$pnr = trim($_GET['pnr'] ?? $_POST['pnr'] ?? '');
$bk  = null;

if ($pnr !== '') {
    $stmt = $pdo->prepare("SELECT b.*, r.origin, r.destination, r.bus_name, r.bus_type, r.departure_time, r.arrival_time, r.duration
                           FROM bookings b JOIN routes r ON b.route_id = r.id WHERE b.pnr = ?");
    $stmt->execute([$pnr]);
    $bk = $stmt->fetch();
}

include 'includes/header.php';
?>

<section class="mini-hero">
  <div class="container">
    <h1>My Booking</h1>
    <p class="breadcrumbs"><a href="index.php">Home</a> &raquo; My Booking</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="bus-card mb-4">
          <h5 class="mb-3"><i class="bi bi-search text-primary me-2"></i>Look up your booking</h5>
          <form method="post" class="row g-2">
            <div class="col-md-9">
              <input type="text" name="pnr" class="form-control" placeholder="Enter your PNR (e.g. OSTABCD1234)" value="<?= e($pnr) ?>" required>
            </div>
            <div class="col-md-3 d-grid">
              <button class="btn btn-primary">Find Booking</button>
            </div>
          </form>
        </div>

        <?php if ($pnr && !$bk): ?>
          <div class="alert alert-warning">No booking found with PNR <strong><?= e($pnr) ?></strong>.</div>
        <?php elseif ($bk): ?>
          <div class="bus-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
              <div>
                <span class="bus-type"><?= e($bk['bus_type']) ?></span>
                <h5 class="mt-2 mb-0"><?= e($bk['bus_name']) ?></h5>
              </div>
              <span class="badge <?= $bk['status']==='Confirmed'?'bg-success':'bg-danger' ?> px-3 py-2"><?= e($bk['status']) ?></span>
            </div>
            <div class="row g-3">
              <div class="col-md-6"><small class="text-muted d-block">From → To</small><strong><?= e($bk['origin']) ?> &rarr; <?= e($bk['destination']) ?></strong></div>
              <div class="col-md-6"><small class="text-muted d-block">Travel Date</small><strong><?= date('D, d M Y', strtotime($bk['travel_date'])) ?></strong></div>
              <div class="col-md-3"><small class="text-muted d-block">Departure</small><strong><?= fmt_time($bk['departure_time']) ?></strong></div>
              <div class="col-md-3"><small class="text-muted d-block">Arrival</small><strong><?= fmt_time($bk['arrival_time']) ?></strong></div>
              <div class="col-md-3"><small class="text-muted d-block">Seats</small><strong><?= e($bk['seat_numbers']) ?></strong></div>
              <div class="col-md-3"><small class="text-muted d-block">Total Fare</small><strong class="text-danger"><?= rupees($bk['total_fare']) ?></strong></div>
              <div class="col-md-6"><small class="text-muted d-block">Passenger</small><strong><?= e($bk['passenger_name']) ?> (<?= (int)$bk['passenger_age'] ?>/<?= e($bk['passenger_gender']) ?>)</strong></div>
              <div class="col-md-6"><small class="text-muted d-block">Contact</small><strong><?= e($bk['phone']) ?> · <?= e($bk['email']) ?></strong></div>
            </div>
            <a href="confirmation.php?pnr=<?= urlencode($bk['pnr']) ?>" class="btn btn-outline-primary rounded-pill mt-4"><i class="bi bi-eye me-1"></i> View full ticket</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
