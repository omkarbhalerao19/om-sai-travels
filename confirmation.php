<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'Booking Confirmation';

$pnr = trim($_GET['pnr'] ?? '');
$stmt = $pdo->prepare("SELECT b.*, r.origin, r.destination, r.bus_name, r.bus_type, r.departure_time, r.arrival_time, r.duration
                       FROM bookings b
                       JOIN routes r ON b.route_id = r.id
                       WHERE b.pnr = ?");
$stmt->execute([$pnr]);
$bk = $stmt->fetch();

include 'includes/header.php';
?>

<section class="section">
  <div class="container">
    <?php if (!$bk): ?>
      <div class="text-center py-5">
        <i class="bi bi-x-circle text-danger" style="font-size:4rem;"></i>
        <h3 class="mt-3">Booking not found</h3>
        <a href="index.php" class="btn btn-primary mt-3 rounded-pill">Back to home</a>
      </div>
    <?php else: ?>
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center"
                 style="width:80px;height:80px;border-radius:50%;background:#e8f5e9;color:#2e7d32;font-size:2.5rem;">
              <i class="bi bi-check2"></i>
            </div>
            <h2 class="mt-3">Booking Confirmed!</h2>
            <p class="text-muted">A confirmation has been sent to <strong><?= e($bk['email']) ?></strong></p>
          </div>

          <div class="ticket">
            <div class="ticket-head d-flex flex-wrap justify-content-between align-items-center">
              <div>
                <small class="text-uppercase opacity-75">Om Sai Travels — e-Ticket</small>
                <h3 class="mb-0 text-white"><?= e($bk['bus_name']) ?></h3>
                <small class="opacity-75"><?= e($bk['bus_type']) ?></small>
              </div>
              <div class="text-end mt-3 mt-md-0">
                <small class="text-uppercase opacity-75 d-block mb-1">PNR Number</small>
                <span class="pnr-box"><?= e($bk['pnr']) ?></span>
              </div>
            </div>

            <div class="ticket-body">
              <div class="row g-4 align-items-center">
                <div class="col-md-5">
                  <small class="text-muted text-uppercase">From</small>
                  <h4 class="mb-0"><?= e($bk['origin']) ?></h4>
                  <div class="time text-primary"><?= fmt_time($bk['departure_time']) ?></div>
                  <small class="text-muted"><?= date('D, d M Y', strtotime($bk['travel_date'])) ?></small>
                </div>
                <div class="col-md-2 text-center">
                  <i class="bi bi-bus-front text-danger" style="font-size:2.2rem;"></i>
                  <div class="small text-muted mt-1"><?= e($bk['duration']) ?></div>
                </div>
                <div class="col-md-5 text-md-end">
                  <small class="text-muted text-uppercase">To</small>
                  <h4 class="mb-0"><?= e($bk['destination']) ?></h4>
                  <div class="time text-primary"><?= fmt_time($bk['arrival_time']) ?></div>
                  <small class="text-muted">Arrival</small>
                </div>
              </div>

              <hr class="my-4">

              <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                  <small class="text-muted text-uppercase d-block">Passenger</small>
                  <strong><?= e($bk['passenger_name']) ?></strong>
                </div>
                <div class="col-sm-6 col-md-3">
                  <small class="text-muted text-uppercase d-block">Age / Gender</small>
                  <strong><?= (int)$bk['passenger_age'] ?> / <?= e($bk['passenger_gender']) ?></strong>
                </div>
                <div class="col-sm-6 col-md-3">
                  <small class="text-muted text-uppercase d-block">Seat(s)</small>
                  <strong><?= e($bk['seat_numbers']) ?></strong>
                </div>
                <div class="col-sm-6 col-md-3">
                  <small class="text-muted text-uppercase d-block">Total Fare</small>
                  <strong class="text-danger h5"><?= rupees($bk['total_fare']) ?></strong>
                </div>
              </div>

              <div class="alert alert-primary mt-4 mb-0 small">
                <i class="bi bi-info-circle me-2"></i>
                Please reach the boarding point 15 minutes before departure. Carry a valid government ID at the time of travel. Pay at boarding.
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-outline-primary rounded-pill px-4"><i class="bi bi-printer me-1"></i> Print Ticket</button>
            <a href="index.php" class="btn btn-primary rounded-pill px-4"><i class="bi bi-house me-1"></i> Home</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
