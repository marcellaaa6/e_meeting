<?php
session_start();
require '../config/google-config.php';

if (!isset($_SESSION['access_token'])) {
    die('Harap login dengan Google terlebih dahulu. <a href="login_google.php">Login dengan Google</a>');
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);
$calendarId = 'primary';

// Ambil semua event dari Google Calendar
$events = $calendarService->events->listEvents($calendarId);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Acara Google Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 14px;
            background-color: #4285F4;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #3367d6;
        }

        .event-card {
            background-color: #fff;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .event-actions a {
            margin-right: 10px;
            font-size: 14px;
        }

        .no-events {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .bottom-tools {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>📅 Daftar Acara dari Google Calendar</h2>
    <a class="btn" href="add_event.php">+ Tambah Acara</a>
</div>

<?php
if (count($events->getItems()) == 0) {
    echo "<div class='no-events'>Tidak ada acara yang ditemukan.</div>";
} else {
    foreach ($events->getItems() as $event) {
        $start = $event->getStart()->getDateTime();
        if (empty($start)) {
            $start = $event->getStart()->getDate(); // untuk acara seharian
        }
        $eventId = $event->getId();
        ?>
        <div class="event-card">
            <strong><?= htmlspecialchars($event->getSummary()) ?></strong><br>
            <small>🕒 <?= htmlspecialchars($start) ?></small><br>
            <small>📍 <?= htmlspecialchars($event->getLocation() ?? 'Tidak ada lokasi') ?></small><br><br>
            <div class="event-actions">
                <a class="btn" href="edit_event.php?id=<?= urlencode($eventId) ?>">✏️ Edit</a>
                <a class="btn" href="delete_event.php?id=<?= urlencode($eventId) ?>" onclick="return confirm('Yakin ingin menghapus acara ini?')">🗑️ Hapus</a>
            </div>
        </div>
        <?php
    }
}
?>

<!-- Tombol Kembali ke Dashboard di Bawah -->
<div class="bottom-tools">
    <a class="btn" href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>

</body>
</html>
