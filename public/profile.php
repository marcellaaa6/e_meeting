<?php
session_start();

// Fallback dari cookie jika session kosong
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['user_name'] = $_COOKIE['user_name'];
}

// Proteksi halaman
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .profile-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 400px;
      text-align: center;
    }
    h2 {
      margin-bottom: 10px;
      color: #333;
    }
    p {
      font-size: 16px;
      color: #555;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: white;
      background: #007bff;
      padding: 10px 20px;
      border-radius: 6px;
    }
    a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="profile-box">
    <h2>Profil Pengguna</h2>
    <p><strong>ID Pengguna:</strong> <?php echo $_SESSION['user_id']; ?></p>
    <p><strong>Nama:</strong> <?php echo $_SESSION['user_name']; ?></p>
    <a href="dashboard.php">Kembali ke Dashboard</a><br><br>
    <a href="logout.php" style="background: #dc3545;">Logout</a>
  </div>
</body>
</html>
