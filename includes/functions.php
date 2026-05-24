<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

function generate_pnr() {
    return 'OST' . strtoupper(bin2hex(random_bytes(4)));
}

function rupees($v) {
    return '₹' . number_format((float)$v, 2);
}

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function flash($type, $msg = null) {
    if ($msg !== null) {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
        return;
    }
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $cls = $f['type'] === 'error' ? 'danger' : $f['type'];
        return '<div class="alert alert-' . e($cls) . ' alert-dismissible fade show" role="alert">'
             . e($f['msg'])
             . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    return '';
}

function fmt_time($t) {
    return date('h:i A', strtotime($t));
}

/**
 * Auto-adjust fare based on time of travel (peak / off-peak).
 * Admin sets the base fare; final fare is multiplied by this factor.
 *  - Weekend (Fri/Sat/Sun) : +15%
 *  - Late night (00-05)    : +10%
 *  - Day off-peak (10-16)  :  -5%
 */
function dynamic_fare($base_fare, $travel_date, $departure_time) {
    $ts   = strtotime($travel_date . ' ' . $departure_time);
    $dow  = (int)date('N', $ts);   // 1=Mon ... 7=Sun
    $hour = (int)date('G', $ts);

    $factor = 1.0;
    if ($dow >= 5)                $factor += 0.15;   // Fri-Sun
    if ($hour >= 0 && $hour < 5)  $factor += 0.10;   // late night
    if ($hour >= 10 && $hour < 16) $factor -= 0.05;  // day off-peak

    return round($base_fare * $factor, 2);
}
