<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register - E-Meeting</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f2f2f2;
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
      color: #333;
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
      background: #007BFF;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover {
      background: #0056b3;
    }
    .link {
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Registrasi</h2>
    <form action="register_process.php" method="POST">
      <label>Nama:</label>
      <input type="text" name="name" required>
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <button type="submit">Daftar</button>
    </form>
    <div class="link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>
</body>
</html>