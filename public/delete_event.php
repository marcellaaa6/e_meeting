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
    die('ID event tidak ditemukan.');
}

try {
    $calendarService->events->delete($calendarId, $eventId);
    echo "<p>✅ Acara berhasil dihapus. <a href='events.php'>Kembali ke daftar</a></p>";
} catch (Exception $e) {
    echo "<p>❌ Gagal menghapus acara: " . $e->getMessage() . "</p>";
}
