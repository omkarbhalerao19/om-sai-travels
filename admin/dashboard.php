<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$page_title = 'Dashboard';

$total_routes   = $pdo->query("SELECT COUNT(*) FROM routes")->fetchColumn();
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$total_revenue  = $pdo->query("SELECT COALESCE(SUM(total_fare),0) FROM bookings WHERE status='Confirmed'")->fetchColumn();
$total_msgs     = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

$recent = $pdo->query("SELECT b.*, r.origin, r.destination FROM bookings b JOIN routes r ON b.route_id=r.id ORDER BY b.created_at DESC LIMIT 8")
              ->fetchAll();

include __DIR__ . '/_header.php';
?>

<div class="row g-3 mb-4">
  <div class="col-sm-6 col-lg-3"><div class="stat-card">
    <div class="d-flex justify-content-between align-items-start">
      <div><div class="stat-label">Routes</div><div class="stat-value"><?= (int)$total_routes ?></div></div>
      <span class="brand-mark"><i class="bi bi-bus-front-fill"></i></span>
    </div>
  </div></div>
  <div class="col-sm-6 col-lg-3"><div class="stat-card">
    <div class="d-flex justify-content-between align-items-start">
      <div><div class="stat-label">Bookings</div><div class="stat-value"><?= (int)$total_bookings ?></div></div>
      <span class="brand-mark" style="background:linear-gradient(135deg,#ef5350,#c62828);"><i class="bi bi-ticket-perforated-fill"></i></span>
    </div>
  </div></div>
  <div class="col-sm-6 col-lg-3"><div class="stat-card">
    <div class="d-flex justify-content-between align-items-start">
      <div><div class="stat-label">Revenue</div><div class="stat-value"><?= rupees($total_revenue) ?></div></div>
      <span class="brand-mark" style="background:linear-gradient(135deg,#66bb6a,#2e7d32);"><i class="bi bi-cash-stack"></i></span>
    </div>
  </div></div>
  <div class="col-sm-6 col-lg-3"><div class="stat-card">
    <div class="d-flex justify-content-between align-items-start">
      <div><div class="stat-label">Messages</div><div class="stat-value"><?= (int)$total_msgs ?></div></div>
      <span class="brand-mark" style="background:linear-gradient(135deg,#ab47bc,#6a1b9a);"><i class="bi bi-envelope-fill"></i></span>
    </div>
  </div></div>
</div>

<div class="bus-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Recent Bookings</h5>
    <a href="bookings.php" class="btn btn-sm btn-outline-primary rounded-pill">View all</a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>PNR</th><th>Passenger</th><th>Route</th><th>Date</th><th>Seats</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach ($recent as $b): ?>
        <tr>
          <td><code><?= e($b['pnr']) ?></code></td>
          <td><?= e($b['passenger_name']) ?></td>
          <td><?= e($b['origin']) ?> &rarr; <?= e($b['destination']) ?></td>
          <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
          <td><?= e($b['seat_numbers']) ?></td>
          <td><strong><?= rupees($b['total_fare']) ?></strong></td>
          <td><span class="badge <?= $b['status']==='Confirmed'?'bg-success':'bg-danger' ?>"><?= e($b['status']) ?></span></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$recent): ?><tr><td colspan="7" class="text-center text-muted py-4">No bookings yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
