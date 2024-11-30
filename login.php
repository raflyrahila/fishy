<?php
require_once 'koneksi.php';
// Memeriksa apakah form login telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data login dari form
    $username = $_POST['username'];
    $password = $_POST['password'];


    
    // Jika login berhasil, redirect ke halaman yang sesuai
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fishyman</title>
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
        <a href="register.php" class="login">Daftar</a>
    </header>

    <main>
        <div class="login-container">
            <h1>Masuk</h1>
            <form method="POST" id="login-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="cta">Masuk</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            &copy; 2023 Fishyman. All rights reserved.
        </div>
    </footer>
</body>
</html>