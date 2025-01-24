<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishyman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
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
                <a href="register.php" class="btn btn-outline-light">Daftar</a>
            </div>
        </div>
    </nav>
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-4">Tumbuh Bersama Lebih dari 1111 Pembudidaya di Kota Lhokseumawe dan sekitarnya</h1>
                    <a href="about.php" class="btn btn-primary btn-lg">Apa itu Fishyman?</a>
                </div>
                <div class="col-md-6 text-center hero-image">
                    <img src="img/fisherman.png" alt="Fisherman" class="img-fluid shadow-lg">
                </div>
            </div>
        </div>
    </section>
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