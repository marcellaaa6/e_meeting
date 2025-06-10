<?php

session_start();
require '../config/google-config.php'; // pastikan file ini ada dan sesuai dengan path kamu

if (isset($_SESSION['access_token'])) {
  header("Location: dashboard.php");
  exit();
}

$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - E-Meeting</title>
  <style>
    /* Style kamu tetap sama */
    body {
      font-family: sans-serif;
      background: #e9f0f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 350px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #444;
    }
    label {
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover {
      background: #1e7e34;
    }
    .remember {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }
    .remember input {
      margin-right: 5px;
    }
    .link {
      text-align: center;
      margin-top: 10px;
    }
    .google-login {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .google-btn {
      display: flex;
      align-items: center;
      background-color: white;
      border: 1px solid #ccc;
      padding: 10px 15px;
      border-radius: 6px;
      text-decoration: none;
      color: #444;
      font-weight: bold;
      transition: 0.2s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .google-btn img {
      width: 20px;
      margin-right: 10px;
    }
    .google-btn:hover {
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Login</h2>
    <form action="login_process.php" method="POST">
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <div class="remember">
        <input type="checkbox" name="remember" value="1"> Remember Me
      </div>
      <button type="submit">Login</button>
    </form>
    <div class="link">
      Belum punya akun? <a href="register.php">Daftar</a>
    </div>
    <div class="google-login">
      <a href="<?= $authUrl ?>" class="google-btn">
        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
        <span>Login dengan Google</span>
      </a>
    </div>
  </div>
</body>
</html>