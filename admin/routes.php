<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$page_title = 'Routes & Fares';

$action = $_GET['action'] ?? '';
$edit_id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $data = [
        'origin'         => trim($_POST['origin']),
        'destination'    => trim($_POST['destination']),
        'bus_name'       => trim($_POST['bus_name']),
        'bus_type'       => $_POST['bus_type'],
        'departure_time' => $_POST['departure_time'],
        'arrival_time'   => $_POST['arrival_time'],
        'duration'       => trim($_POST['duration']),
        'fare'           => (float)$_POST['fare'],
        'total_seats'    => (int)$_POST['total_seats'],
        'active'         => isset($_POST['active']) ? 1 : 0,
    ];
    if ($id > 0) {
        $sql = "UPDATE routes SET origin=:origin, destination=:destination, bus_name=:bus_name, bus_type=:bus_type,
                departure_time=:departure_time, arrival_time=:arrival_time, duration=:duration, fare=:fare,
                total_seats=:total_seats, active=:active WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge($data, ['id' => $id]));
        flash('success', 'Route updated successfully.');
    } else {
        $sql = "INSERT INTO routes (origin, destination, bus_name, bus_type, departure_time, arrival_time, duration, fare, total_seats, active)
                VALUES (:origin, :destination, :bus_name, :bus_type, :departure_time, :arrival_time, :duration, :fare, :total_seats, :active)";
        $pdo->prepare($sql)->execute($data);
        flash('success', 'Route added successfully.');
    }
    header('Location: routes.php');
    exit;
}

if ($action === 'delete' && $edit_id) {
    $pdo->prepare("DELETE FROM routes WHERE id=?")->execute([$edit_id]);
    flash('success', 'Route deleted.');
    header('Location: routes.php');
    exit;
}

$editing = null;
if ($action === 'edit' && $edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM routes WHERE id=?");
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch();
}

$routes = $pdo->query("SELECT * FROM routes ORDER BY origin, destination, departure_time")->fetchAll();

include __DIR__ . '/_header.php';
?>

<div class="row g-4">
  <div class="col-xl-4">
    <div class="bus-card">
      <h5 class="mb-3"><i class="bi bi-<?= $editing?'pencil-square':'plus-circle-fill' ?> text-primary me-2"></i><?= $editing?'Edit Route':'Add New Route' ?></h5>
      <form method="post" class="row g-2">
        <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0) ?>">
        <div class="col-6"><label class="form-label small">Origin</label>
          <input name="origin" class="form-control" required value="<?= e($editing['origin'] ?? '') ?>"></div>
        <div class="col-6"><label class="form-label small">Destination</label>
          <input name="destination" class="form-control" required value="<?= e($editing['destination'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label small">Bus Name</label>
          <input name="bus_name" class="form-control" required value="<?= e($editing['bus_name'] ?? '') ?>"></div>
        <div class="col-12"><label class="form-label small">Bus Type</label>
          <select name="bus_type" class="form-select" required>
            <?php foreach (['AC Sleeper','Non-AC Sleeper','AC Seater','Non-AC Seater','Volvo Multi-Axle'] as $t): ?>
              <option <?= ($editing['bus_type'] ?? '')===$t?'selected':'' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="col-6"><label class="form-label small">Departure</label>
          <input type="time" name="departure_time" class="form-control" required value="<?= e($editing['departure_time'] ?? '') ?>"></div>
        <div class="col-6"><label class="form-label small">Arrival</label>
          <input type="time" name="arrival_time" class="form-control" required value="<?= e($editing['arrival_time'] ?? '') ?>"></div>
        <div class="col-6"><label class="form-label small">Duration</label>
          <input name="duration" class="form-control" placeholder="e.g. 8h 30m" required value="<?= e($editing['duration'] ?? '') ?>"></div>
        <div class="col-6"><label class="form-label small">Base Fare (₹)</label>
          <input type="number" step="0.01" name="fare" class="form-control" required value="<?= e($editing['fare'] ?? '') ?>"></div>
        <div class="col-6"><label class="form-label small">Total Seats</label>
          <input type="number" name="total_seats" class="form-control" required value="<?= e($editing['total_seats'] ?? 40) ?>"></div>
        <div class="col-6 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" <?= (!$editing || $editing['active'])?'checked':'' ?>>
            <label for="active" class="form-check-label small">Active</label>
          </div>
        </div>
        <div class="col-12 d-grid mt-3">
          <button class="btn btn-danger rounded-pill"><?= $editing?'Update Route':'Add Route' ?></button>
          <?php if ($editing): ?><a href="routes.php" class="btn btn-link mt-1">Cancel</a><?php endif; ?>
        </div>
      </form>
      <div class="alert alert-info small mt-3 mb-0">
        <strong>Dynamic pricing tip:</strong> Final fare on the customer side auto-adjusts (+15% weekends, +10% late-night, −5% off-peak day) based on travel time.
      </div>
    </div>
  </div>

  <div class="col-xl-8">
    <div class="bus-card">
      <h5 class="mb-3">All Routes (<?= count($routes) ?>)</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead><tr><th>Route</th><th>Bus</th><th>Type</th><th>Timing</th><th>Fare</th><th>Status</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($routes as $r): ?>
              <tr>
                <td><strong><?= e($r['origin']) ?> &rarr; <?= e($r['destination']) ?></strong><br><small class="text-muted"><?= e($r['duration']) ?></small></td>
                <td><?= e($r['bus_name']) ?></td>
                <td><span class="bus-type"><?= e($r['bus_type']) ?></span></td>
                <td class="small"><?= fmt_time($r['departure_time']) ?> &rarr; <?= fmt_time($r['arrival_time']) ?></td>
                <td><strong class="text-danger"><?= rupees($r['fare']) ?></strong></td>
                <td><span class="badge <?= $r['active']?'bg-success':'bg-secondary' ?>"><?= $r['active']?'Active':'Inactive' ?></span></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="routes.php?action=edit&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
                  <a class="btn btn-sm btn-outline-danger" href="routes.php?action=delete&id=<?= (int)$r['id'] ?>" onclick="return confirm('Delete this route? Existing bookings will also be removed.')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
