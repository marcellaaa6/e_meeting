<?php
require '../config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$now = new DateTime();
$checkTime = $now->format('Y-m-d H:i:00');

$stmt = $conn->prepare("SELECT * FROM meetings WHERE datetime = ?");
$stmt->bind_param("s", $checkTime);
$stmt->execute();
$result = $stmt->get_result();

while ($meeting = $result->fetch_assoc()) {
    $emails = explode(",", $meeting['participants']);
    foreach ($emails as $email) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Ganti
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Ganti
        $mail->Password = 'your_password'; // Ganti
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'E-Meeting');
        $mail->addAddress(trim($email));
        $mail->Subject = "Reminder: " . $meeting['title'];
        $mail->Body = "Jangan lupa meeting: " . $meeting['description'] . " pada " . $meeting['datetime'];
        $mail->send();
    }
}
?>