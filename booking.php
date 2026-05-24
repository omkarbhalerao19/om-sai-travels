<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
$page_title = 'Book Seats';

$route_id = (int)($_GET['route_id'] ?? 0);
$date     = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM routes WHERE id = ? AND active = 1");
$stmt->execute([$route_id]);
$route = $stmt->fetch();

if (!$route) {
    flash('error', 'Route not found.');
    header('Location: routes.php');
    exit;
}

$final_fare = dynamic_fare($route['fare'], $date, $route['departure_time']);

// Booked seats for this route on this date
$bk = $pdo->prepare("SELECT seat_numbers FROM bookings WHERE route_id = ? AND travel_date = ? AND status = 'Confirmed'");
$bk->execute([$route_id, $date]);
$booked = [];
foreach ($bk->fetchAll() as $b) {
    foreach (explode(',', $b['seat_numbers']) as $s) {
        $s = trim($s);
        if ($s !== '') $booked[$s] = true;
    }
}

// Handle POST -> create booking
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name']   ?? '');
    $age    = (int)($_POST['age']   ?? 0);
    $gender = $_POST['gender']      ?? '';
    $email  = trim($_POST['email']  ?? '');
    $phone  = trim($_POST['phone']  ?? '');
    $seats  = trim($_POST['seats']  ?? '');

    if ($name === '')                       $errors[] = 'Passenger name is required.';
    if ($age < 1 || $age > 120)             $errors[] = 'Please enter a valid age.';
    if (!in_array($gender, ['Male','Female','Other'])) $errors[] = 'Please select gender.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = 'Please enter a valid email.';
    if (!preg_match('/^[0-9+\- ]{8,15}$/', $phone))    $errors[] = 'Please enter a valid phone number.';
    if ($seats === '')                                  $errors[] = 'Please select at least one seat.';

    $seat_arr = array_filter(array_map('trim', explode(',', $seats)));
    foreach ($seat_arr as $s) {
        if (isset($booked[$s])) { $errors[] = "Seat $s is already booked."; break; }
        if (!preg_match('/^\d{1,2}$/', $s) || (int)$s < 1 || (int)$s > (int)$route['total_seats']) {
            $errors[] = "Invalid seat number: $s"; break;
        }
    }

    if (!$errors) {
        $pnr   = generate_pnr();
        $total = count($seat_arr) * $final_fare;
        $ins   = $pdo->prepare("INSERT INTO bookings (pnr, route_id, passenger_name, passenger_age, passenger_gender, email, phone, travel_date, seats_booked, seat_numbers, total_fare)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $ins->execute([$pnr, $route_id, $name, $age, $gender, $email, $phone, $date, count($seat_arr), implode(',', $seat_arr), $total]);
        header('Location: confirmation.php?pnr=' . urlencode($pnr));
        exit;
    }
}

include 'includes/header.php';
?>

<section class="mini-hero">
  <div class="container">
    <h1>Book Your Seats</h1>
    <p class="breadcrumbs"><a href="index.php">Home</a> &raquo; <a href="routes.php">Routes</a> &raquo; Booking</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <strong>Please fix the following:</strong>
        <ul class="mb-0"><?php foreach ($errors as $err) echo '<li>' . e($err) . '</li>'; ?></ul>
      </div>
    <?php endif; ?>

    <form id="bookingForm" method="post" class="row g-4">
      <!-- LEFT: Route + Seats -->
      <div class="col-lg-8">
        <div class="bus-card mb-4">
          <div class="row align-items-center">
            <div class="col-md-7">
              <span class="bus-type"><?= e($route['bus_type']) ?></span>
              <h5 class="mt-2 mb-1"><?= e($route['bus_name']) ?></h5>
              <div class="small text-muted">
                <i class="bi bi-geo-alt me-1"></i><?= e($route['origin']) ?> &rarr; <?= e($route['destination']) ?> ·
                <i class="bi bi-calendar3 ms-2 me-1"></i><?= date('D, d M Y', strtotime($date)) ?>
              </div>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
              <div class="d-inline-flex align-items-center gap-3">
                <div class="text-center">
                  <div class="time"><?= fmt_time($route['departure_time']) ?></div>
                  <div class="small text-muted">Dep</div>
                </div>
                <i class="bi bi-arrow-right text-danger"></i>
                <div class="text-center">
                  <div class="time"><?= fmt_time($route['arrival_time']) ?></div>
                  <div class="small text-muted">Arr</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bus-card">
          <h5 class="mb-3"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Select your seats</h5>
          <p class="small text-muted">Click seats to select / deselect. Max 6 per booking.</p>

          <div class="seat-legend mb-3">
            <span><span class="swatch" style="background:#eef3f7;"></span>Available</span>
            <span><span class="swatch" style="background:var(--primary);"></span>Selected</span>
            <span><span class="swatch" style="background:#d6dbe0;"></span>Booked</span>
          </div>

          <div id="seatGrid" class="seat-grid" data-fare="<?= e($final_fare) ?>">
            <?php for ($i = 1; $i <= (int)$route['total_seats']; $i++):
              $isBooked = isset($booked[(string)$i]);
            ?>
              <div class="seat <?= $isBooked ? 'booked' : '' ?>" data-seat="<?= $i ?>"><?= $i ?></div>
            <?php endfor; ?>
          </div>
        </div>
      </div>

      <!-- RIGHT: Passenger + summary -->
      <div class="col-lg-4">
        <div class="bus-card mb-4">
          <h5 class="mb-3"><i class="bi bi-person-fill text-primary me-2"></i>Passenger Details</h5>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?= e($_POST['name'] ?? '') ?>">
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label small fw-semibold">Age</label>
              <input type="number" name="age" min="1" max="120" class="form-control" required value="<?= e($_POST['age'] ?? '') ?>">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Gender</label>
              <select name="gender" class="form-select" required>
                <option value="">Select</option>
                <?php foreach (['Male','Female','Other'] as $g): ?>
                  <option <?= ($_POST['gender'] ?? '')===$g?'selected':'' ?>><?= $g ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-3 mt-2">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= e($_POST['email'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Phone</label>
            <input type="tel" name="phone" class="form-control" required value="<?= e($_POST['phone'] ?? '') ?>">
          </div>
        </div>

        <div class="bus-card">
          <h5 class="mb-3"><i class="bi bi-receipt text-primary me-2"></i>Fare Summary</h5>
          <div class="d-flex justify-content-between mb-2"><span>Per-seat fare</span><strong><?= rupees($final_fare) ?></strong></div>
          <div class="d-flex justify-content-between mb-2"><span>Seats selected</span><strong><span id="seatCount">0</span></strong></div>
          <hr>
          <div class="d-flex justify-content-between mb-3"><span class="h6">Total Payable</span><strong class="h4 text-danger mb-0" id="totalAmount">₹0.00</strong></div>
          <input type="hidden" name="seats" id="selectedSeats">
          <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill">Confirm Booking <i class="bi bi-check2-circle ms-1"></i></button>
          <p class="text-center small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Pay at boarding · Free cancellation 6h before departure</p>
        </div>
      </div>
    </form>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
