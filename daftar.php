<?php
require_once 'auth.php';
// Memeriksa apakah form registrasi telah dikirim
if($user->isLogin()){
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Fishyman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo" onclick="location.href='index.php';">Fishyman</div>
        <nav>
            <ul>
                <li><a href="#">Services</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </nav>
        <a href="login.php" class="login">Masuk</a>
    </header>

    <main>
        <div class="register-container">
            <h1>Daftar</h1>
            <form method="POST" id="register-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Konfirmasi Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" class="cta">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Masuk di sini</a>.</p>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            &copy; 2023 Fishyman. All rights reserved.
        </div>
    </footer>
</body>
</html>