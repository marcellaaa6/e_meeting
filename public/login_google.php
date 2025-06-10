<?php
require '../config/google-config.php';

$auth_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login dengan Google</title>
</head>
<body>
    <h2>Login dengan Google</h2>
    <a href="<?= htmlspecialchars($auth_url) ?>">
        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Login with Google"/>
    </a>
</body>
</html>