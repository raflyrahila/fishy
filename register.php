<?php
require_once 'controller/auth.php';
// Memeriksa apakah form registrasi telah dikirim
if($user->isLogin()){
    header('Location: index.php');
    exit;
}
if(isset($_POST['username'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    if($user->register($username, $email, $password, $alamat, $no_telepon)){
        header('Location: login.php');
        exit;
    }else{
        $error = $user->getError();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Fishyman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 100px 0;
        }
        .footer {
            margin-top: auto;
            background-color: #2c3e50 !important;
            color: #ecf0f1;
            padding: 20px 0;
            text-align: center;
        }
        /* Navbar Styles */
        .navbar-fishyman {
            background-color: #2c3e50 !important;
        }
        .navbar-fishyman .navbar-brand {
            color: #ecf0f1 !important;
            font-weight: bold;
        }
        .navbar-fishyman .nav-link {
            color: #bdc3c7 !important;
            position: relative;
            transition: color 0.3s ease;
        }
        .navbar-fishyman .nav-link:hover {
            color: #ecf0f1 !important;
        }
        .navbar-fishyman .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #3498db;
            transition: all 0.3s ease;
        }
        .navbar-fishyman .nav-link:hover::after {
            width: 100%;
            left: 0;
        }
        /* Register Container Styles */
        .register-container {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .register-container .form-control {
            background-color: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3);
            color: white;
        }
        .register-container .form-control:focus {
            background-color: rgba(255,255,255,0.2);
            border-color: white;
            color: white;
            box-shadow: none;
        }
        .register-container .form-label {
            color: #bdc3c7;
        }
        /* Dropdown Styles */
        .dropdown-menu {
            background-color: #2c3e50;
        }
        .dropdown-menu a {
            color: #bdc3c7 !important;
        }
        .dropdown-menu a:hover {
            background-color: #3498db;
            color: #ecf0f1 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-fishyman navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Fishyman</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarServicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Layanan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarServicesDropdown">
                            <li><a class="dropdown-item" href="page/setorikan.php">Setor Ikan</a></li>
                            <li><a class="dropdown-item" href="page/jualikan.php">Jual Ikan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Tentang</a>
                    </li>
                </ul>
                <a href="login.php" class="btn btn-outline-light">Masuk</a>
            </div>
        </div>
    </nav>

    <div class="register-container">
        <h2 class="text-center mb-4">Daftar</h2>
        <form method="POST" id="register-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="no-telepon" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" id="no-telepon" name="no_telepon" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="text-center mt-3">
            <p class="small">Sudah punya akun? <a href="login.php" class="text-light">Masuk di sini</a></p>
        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <p class="mb-0">&copy; 2024 Fishyman. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>