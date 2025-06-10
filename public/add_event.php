<?php
session_start();
require '../config/google-config.php';

if (!isset($_SESSION['access_token'])) {
    die('<div class="text-center mt-5">Harap login dengan Google terlebih dahulu. <a href="login_google.php" class="btn btn-primary">Login dengan Google</a></div>');
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

$message = '';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $summary = $_POST['summary'];
    $location = $_POST['location'];
    $start = $_POST['start'] . ':00+07:00';
    $end = $_POST['end'] . ':00+07:00';

    $event = new Google_Service_Calendar_Event([
        'summary' => $summary,
        'location' => $location,
        'start' => ['dateTime' => $start, 'timeZone' => 'Asia/Jakarta'],
        'end' => ['dateTime' => $end, 'timeZone' => 'Asia/Jakarta'],
    ]);

    $calendarId = 'primary';
    $event = $calendarService->events->insert($calendarId, $event);

    $message = "✅ Acara berhasil ditambahkan: <a href='{$event->htmlLink}' target='_blank'>Lihat di Google Calendar</a>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Acara Google Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">➕ Tambah Acara ke Google Calendar</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="border p-4 rounded shadow-sm bg-light">
        <div class="mb-3">
            <label class="form-label">Judul Acara</label>
            <input type="text" name="summary" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="location" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Mulai</label>
            <input type="datetime-local" name="start" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Selesai</label>
            <input type="datetime-local" name="end" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Acara</button>
        <a href="events.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
