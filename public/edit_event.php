<?php
session_start();
require '../config/google-config.php';

if (!isset($_SESSION['access_token'])) {
    die('Harap login dengan Google terlebih dahulu. <a href="login_google.php">Login</a>');
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

$calendarId = 'primary';
$eventId = $_GET['id'] ?? '';

if (empty($eventId)) {
    die('ID acara tidak ditemukan.');
}

try {
    $event = $calendarService->events->get($calendarId, $eventId);
} catch (Exception $e) {
    die('Gagal mengambil event: ' . $e->getMessage());
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event->setSummary($_POST['summary']);
    $event->setLocation($_POST['location']);

    $start = new Google_Service_Calendar_EventDateTime([
        'dateTime' => $_POST['start'] . ':00+07:00',
        'timeZone' => 'Asia/Jakarta'
    ]);
    $end = new Google_Service_Calendar_EventDateTime([
        'dateTime' => $_POST['end'] . ':00+07:00',
        'timeZone' => 'Asia/Jakarta'
    ]);
    $event->setStart($start);
    $event->setEnd($end);

    $updatedEvent = $calendarService->events->update($calendarId, $eventId, $event);
    $successMessage = "✅ Acara berhasil diperbarui.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Acara</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #4285F4;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3367d6;
        }

        .success {
            margin-top: 15px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            text-align: center;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #4285F4;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Acara Google Calendar</h2>

    <?php if (isset($successMessage)) : ?>
        <div class="success"><?= $successMessage ?> <a href="events.php">Kembali ke daftar</a></div>
    <?php endif; ?>

    <form method="POST">
        <label>Judul Acara:</label>
        <input type="text" name="summary" value="<?= htmlspecialchars($event->getSummary()) ?>" required>

        <label>Lokasi:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($event->getLocation()) ?>">

        <label>Waktu Mulai:</label>
        <input type="datetime-local" name="start" value="<?= date('Y-m-d\TH:i', strtotime($event->getStart()->getDateTime())) ?>" required>

        <label>Waktu Selesai:</label>
        <input type="datetime-local" name="end" value="<?= date('Y-m-d\TH:i', strtotime($event->getEnd()->getDateTime())) ?>" required>

        <button type="submit">💾 Simpan Perubahan</button>
    </form>

    <a class="back-link" href="events.php">⬅ Kembali ke Daftar Acara</a>
</div>

</body>
</html>
