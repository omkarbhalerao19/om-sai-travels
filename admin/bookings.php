<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$page_title = 'Bookings';

if (($_GET['action'] ?? '') === 'cancel' && !empty($_GET['id'])) {
    $pdo->prepare("UPDATE bookings SET status='Cancelled' WHERE id=?")->execute([(int)$_GET['id']]);
    flash('success', 'Booking cancelled.');
    header('Location: bookings.php'); exit;
}

$q = trim($_GET['q'] ?? '');
$sql = "SELECT b.*, r.origin, r.destination, r.bus_name FROM bookings b JOIN routes r ON b.route_id=r.id";
$args = [];
if ($q !== '') { $sql .= " WHERE b.pnr LIKE ? OR b.passenger_name LIKE ? OR b.phone LIKE ?"; $args = ["%$q%","%$q%","%$q%"]; }
$sql .= " ORDER BY b.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$rows = $stmt->fetchAll();

include __DIR__ . '/_header.php';
?>

<div class="bus-card">
  <form method="get" class="row g-2 mb-3">
    <div class="col-md-6"><input name="q" class="form-control" placeholder="Search by PNR, name or phone" value="<?= e($q) ?>"></div>
    <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Search</button></div>
    <div class="col-md-3"><a href="bookings.php" class="btn btn-outline-secondary w-100">Reset</a></div>
  </form>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead><tr><th>PNR</th><th>Passenger</th><th>Route</th><th>Bus</th><th>Date</th><th>Seats</th><th>Total</th><th>Status</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $b): ?>
        <tr>
          <td><code><?= e($b['pnr']) ?></code></td>
          <td><?= e($b['passenger_name']) ?><br><small class="text-muted"><?= e($b['phone']) ?></small></td>
          <td><?= e($b['origin']) ?> &rarr; <?= e($b['destination']) ?></td>
          <td><?= e($b['bus_name']) ?></td>
          <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
          <td><?= e($b['seat_numbers']) ?> <span class="small text-muted">(<?= (int)$b['seats_booked'] ?>)</span></td>
          <td><strong><?= rupees($b['total_fare']) ?></strong></td>
          <td><span class="badge <?= $b['status']==='Confirmed'?'bg-success':'bg-danger' ?>"><?= e($b['status']) ?></span></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="../confirmation.php?pnr=<?= urlencode($b['pnr']) ?>" target="_blank"><i class="bi bi-eye"></i></a>
            <?php if ($b['status']==='Confirmed'): ?>
              <a class="btn btn-sm btn-outline-danger" href="bookings.php?action=cancel&id=<?= (int)$b['id'] ?>" onclick="return confirm('Cancel this booking?')"><i class="bi bi-x-circle"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="9" class="text-center text-muted py-4">No bookings found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
