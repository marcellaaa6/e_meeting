<?php
require 'vendor/autoload.php';
require 'config/db.php';

$now = date('Y-m-d H:i:s');
$next_hour = date('Y-m-d H:i:s', strtotime('+1 hour'));
$sql = "SELECT * FROM meetings WHERE datetime BETWEEN '$now' AND '$next_hour'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  // Kirim email menggunakan PHPMailer ke semua peserta
}
?>