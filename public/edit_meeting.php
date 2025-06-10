<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID meeting tidak ditemukan.";
    exit();
}

$id = $_GET['id'];

// Ambil data meeting
$stmt = $conn->prepare("SELECT * FROM meetings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Meeting tidak ditemukan.";
    exit();
}

$meeting = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];

    $update = $conn->prepare("UPDATE meetings SET title=?, description=?, date_time=?, location=? WHERE id=?");
    $update->bind_param("ssssi", $title, $description, $date_time, $location, $id);

    if ($update->execute()) {
        header("Location: meetings.php");
        exit();
    } else {
        echo "Gagal mengupdate meeting.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Meeting</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 40px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 24px;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    button {
      padding: 12px 24px;
      background-color: #1a73e8;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
    }
    button:hover {
      background-color: #0f5bd1;
    }
    a {
      display: inline-block;
      margin-top: 16px;
      text-decoration: none;
      color: #1a73e8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Meeting</h2>
    <form method="POST">
      <input type="text" name="title" value="<?= htmlspecialchars($meeting['title']) ?>" required placeholder="Judul Meeting">
      <textarea name="description" rows="4" required placeholder="Deskripsi"><?= htmlspecialchars($meeting['description']) ?></textarea>
      <input type="datetime-local" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($meeting['date_time'])) ?>" required>
      <input type="text" name="location" value="<?= htmlspecialchars($meeting['location']) ?>" required placeholder="Lokasi">
      <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="meetings.php">⬅ Kembali ke Daftar Meeting</a>
  </div>
</body>
</html>
