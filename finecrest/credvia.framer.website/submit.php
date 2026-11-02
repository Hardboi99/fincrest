<?php
/**
 * Receives enquiry forms (loan + DSA).
 * Saves each valid enquiry to storage/enquiries.csv (not web-accessible) and,
 * if 'notify_email' is set in includes/config.php, also tries to e-mail it.
 * Returns JSON for the AJAX form, or a plain message page without JavaScript.
 */
declare(strict_types=1);
session_start();

$CONFIG = require __DIR__ . '/includes/config.php';
$wantsJson = stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

function respond(bool $ok, string $message, array $errors, bool $json, int $status = 200): void
{
    if ($json) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => $errors]);
    } else {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<body style="font-family:system-ui;max-width:520px;margin:15vh auto;padding:0 20px"><h1>'
            . ($ok ? 'Thank you' : 'Please check your details') . '</h1><p>' . htmlspecialchars($message) . '</p>'
            . '<p><a href="javascript:history.back()">Go back</a></p></body>';
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(false, 'Invalid request.', [], $wantsJson, 405);
}

/* simple spam checks: honeypot, minimum fill time, per-session rate limit */
if (!empty($_POST['website'])) {
    respond(true, 'Thank you.', [], $wantsJson); // pretend success to bots
}
$ts = (int) ($_POST['ts'] ?? 0);
if ($ts > 0 && time() - $ts < 3) {
    respond(false, 'Please take a moment to fill in the form.', [], $wantsJson, 429);
}
$_SESSION['hits'] = array_filter($_SESSION['hits'] ?? [], fn ($t) => $t > time() - 3600);
if (count($_SESSION['hits']) >= 5) {
    respond(false, 'Too many enquiries from this device. Please try again later.', [], $wantsJson, 429);
}

$clean = function (string $key, int $max): string {
    $v = trim((string) ($_POST[$key] ?? ''));
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $v) ?? '';
    return mb_substr($v, 0, $max);
};

$data = [
    'type'    => $clean('type', 10) === 'dsa' ? 'dsa' : 'loan',
    'name'    => $clean('name', 80),
    'mobile'  => $clean('mobile', 16),
    'email'   => $clean('email', 120),
    'city'    => $clean('city', 60),
    'loan'    => $clean('loan', 40),
    'amount'  => $clean('amount', 20),
    'message' => $clean('message', 600),
];

$errors = [];
if (mb_strlen($data['name']) < 2) $errors['name'] = 'Please enter your full name.';
$mobile = preg_replace('/[\s-]/', '', $data['mobile']) ?? '';
$mobile = preg_replace('/^(\+91|91|0)(?=\d{10}$)/', '', $mobile) ?? '';
if (!preg_match('/^[6-9]\d{9}$/', $mobile)) $errors['mobile'] = 'Enter a valid 10-digit Indian mobile number.';
if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
if (mb_strlen($data['city']) < 2) $errors['city'] = 'Please enter your city.';
if ($data['loan'] === '') $errors['loan'] = 'Please choose a loan type.';
if (empty($_POST['consent'])) $errors['consent'] = 'Please tick the box to continue.';

if ($errors) {
    respond(false, 'Please check the highlighted fields.', $errors, $wantsJson, 422);
}
$data['mobile'] = $mobile;

/* save to CSV (neutralise spreadsheet formulas) */
$safe = fn (string $v): string => preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;
$dir = __DIR__ . '/storage';
if (!is_dir($dir)) @mkdir($dir, 0775, true);
$file = $dir . '/enquiries.csv';
$new = !file_exists($file);
$fh = @fopen($file, 'ab');
if (!$fh) {
    respond(false, 'We could not save your enquiry right now. Please try again shortly.', [], $wantsJson, 500);
}
flock($fh, LOCK_EX);
if ($new) fputcsv($fh, ['received_at', 'type', 'name', 'mobile', 'email', 'city', 'loan', 'amount', 'message', 'ip'], ',', '"', '\\');
fputcsv($fh, [
    date('Y-m-d H:i:s'), $data['type'], $safe($data['name']), $data['mobile'], $safe($data['email']),
    $safe($data['city']), $safe($data['loan']), $safe($data['amount']), $safe($data['message']), $_SERVER['REMOTE_ADDR'] ?? '',
], ',', '"', '\\');
flock($fh, LOCK_UN);
fclose($fh);
$_SESSION['hits'][] = time();

/* optional e-mail notification */
if (!empty($CONFIG['notify_email'])) {
    $body = '';
    foreach ($data as $k => $v) $body .= ucfirst($k) . ': ' . str_replace(["\r", "\n"], ' ', $v) . "\n";
    @mail($CONFIG['notify_email'], 'New ' . $data['type'] . ' enquiry — ' . $CONFIG['name'], $body, 'Content-Type: text/plain; charset=utf-8');
}

respond(true, 'We have received your enquiry and will get in touch with you soon.', [], $wantsJson);
