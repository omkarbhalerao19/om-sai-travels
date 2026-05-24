<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'Bus Routes';

$from = trim($_GET['from'] ?? '');
$to   = trim($_GET['to'] ?? '');
$date = $_GET['date'] ?? date('Y-m-d');
$pax  = (int)($_GET['pax'] ?? 1);

$sql  = "SELECT * FROM routes WHERE active=1";
$args = [];
if ($from !== '') { $sql .= " AND origin = ?"; $args[] = $from; }
if ($to   !== '') { $sql .= " AND destination = ?"; $args[] = $to; }
$sql .= " ORDER BY departure_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$routes = $stmt->fetchAll();

$cities = $pdo->query("SELECT DISTINCT origin AS city FROM routes UNION SELECT DISTINCT destination FROM routes ORDER BY city")
              ->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<section class="mini-hero">
  <div class="container">
    <h1>Available Buses</h1>
    <p class="breadcrumbs"><a href="index.php">Home</a> &raquo; Routes</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <!-- Filter / re-search -->
    <div class="search-card mb-4">
      <form method="get" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label>From</label>
          <select name="from" class="form-select">
            <option value="">Any</option>
            <?php foreach ($cities as $c): ?>
              <option value="<?= e($c) ?>" <?= $c===$from?'selected':'' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>To</label>
          <select name="to" class="form-select">
            <option value="">Any</option>
            <?php foreach ($cities as $c): ?>
              <option value="<?= e($c) ?>" <?= $c===$to?'selected':'' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Travel Date</label>
          <input type="date" name="date" class="form-control" value="<?= e($date) ?>">
        </div>
        <div class="col-md-2">
          <label>Passengers</label>
          <select name="pax" class="form-select">
            <?php for ($i=1;$i<=6;$i++): ?>
              <option value="<?= $i ?>" <?= $i==$pax?'selected':'' ?>><?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-1 d-grid">
          <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">
        <?php if ($from || $to): ?>
          <?= e($from ?: 'Any') ?> <i class="bi bi-arrow-right text-danger mx-1"></i> <?= e($to ?: 'Any') ?>
        <?php else: ?>
          All Routes
        <?php endif; ?>
      </h4>
      <span class="text-muted small"><?= count($routes) ?> bus<?= count($routes)!==1?'es':'' ?> found · <?= date('D, d M Y', strtotime($date)) ?></span>
    </div>

    <?php if (!$routes): ?>
      <div class="bus-card text-center py-5">
        <i class="bi bi-emoji-frown" style="font-size:3rem;color:var(--muted);"></i>
        <h5 class="mt-3">No buses found for this route</h5>
        <p class="text-muted small">Try another date or pair of cities.</p>
        <a href="index.php" class="btn btn-outline-primary rounded-pill">Back to home</a>
      </div>
    <?php else: ?>
      <?php foreach ($routes as $r):
        $final_fare = dynamic_fare($r['fare'], $date, $r['departure_time']);
        $is_discount = $final_fare < $r['fare'];
        $is_peak     = $final_fare > $r['fare'];
      ?>
        <div class="bus-card">
          <div class="row align-items-center g-3">
            <div class="col-lg-4">
              <span class="bus-type"><?= e($r['bus_type']) ?></span>
              <h5 class="mt-2 mb-1"><?= e($r['bus_name']) ?></h5>
              <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?= e($r['origin']) ?> &rarr; <?= e($r['destination']) ?></div>
            </div>
            <div class="col-lg-4">
              <div class="d-flex align-items-center justify-content-between">
                <div class="text-center">
                  <div class="time"><?= fmt_time($r['departure_time']) ?></div>
                  <div class="small text-muted"><?= e($r['origin']) ?></div>
                </div>
                <div class="text-center flex-grow-1 px-3">
                  <div class="duration"><?= e($r['duration']) ?></div>
                  <div style="height:2px;background:#eef3f7;position:relative;margin:6px 0;">
                    <i class="bi bi-bus-front text-primary" style="position:absolute;top:-9px;left:50%;transform:translateX(-50%);background:#fff;padding:0 6px;"></i>
                  </div>
                  <div class="small text-muted">Non-stop</div>
                </div>
                <div class="text-center">
                  <div class="time"><?= fmt_time($r['arrival_time']) ?></div>
                  <div class="small text-muted"><?= e($r['destination']) ?></div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 text-lg-end">
              <div class="fare"><?= rupees($final_fare) ?>
                <?php if ($is_discount): ?>
                  <span class="text-decoration-line-through text-muted" style="font-size:1rem;"><?= rupees($r['fare']) ?></span>
                  <span class="badge bg-success ms-1">Off-peak deal</span>
                <?php elseif ($is_peak): ?>
                  <span class="badge bg-warning text-dark ms-1">Peak rate</span>
                <?php endif; ?>
              </div>
              <div class="small text-muted mb-2"><i class="bi bi-people me-1"></i><?= (int)$r['total_seats'] ?> seats · Per passenger</div>
              <a class="btn btn-danger rounded-pill px-4" href="booking.php?route_id=<?= (int)$r['id'] ?>&date=<?= e($date) ?>&pax=<?= (int)$pax ?>">
                Book Seats <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
