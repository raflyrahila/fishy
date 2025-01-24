<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Fishyman</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
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

    /* Main Content Styles */
    .about-section {
      padding: 80px 0;
    }
    .about-section h1 {
      font-size: 36px;
      margin-bottom: 30px;
    }
    .about-section p {
      line-height: 1.6;
      margin-bottom: 20px;
    }

    /* Footer Styles */
    .footer {
      margin-top: auto;
      background-color: #2c3e50 !important;
      color: #ecf0f1;
      padding: 20px 0;
      text-align: center;
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

  <section class="about-section">
    <div class="container">
      <h1>Tentang Fishyman</h1>
      <p>Fishyman adalah platform yang didedikasikan untuk membantu para pembudidaya dan nelayan di Kota Lhokseumawe dan sekitarnya. Kami bertujuan untuk meningkatkan kesejahteraan komunitas perikanan lokal dengan menyediakan berbagai layanan dan dukungan.</p>

      <h2>Apa yang Kami Lakukan</h2>
      <ul>
        <li>Menyediakan akses ke pasar dan mitra bisnis yang lebih luas bagi para pembudidaya dan nelayan</li>
        <li>Memberikan pelatihan dan bimbingan teknis untuk meningkatkan produktivitas dan kualitas hasil tangkapan/budidaya</li>
        <li>Membantu mengembangkan infrastruktur dan fasilitas pendukung bagi industri perikanan setempat</li>
        <li>Mempromosikan produk-produk perikanan lokal ke pasar yang lebih luas</li>
      </ul>

      <h2>Tim Kami</h2>
      <p>Tim kami terdiri dari profesional yang berpengalaman di bidang perikanan, pertanian, dan pengembangan masyarakat. Kami berkomitmen untuk terus berinovasi dan bekerja sama dengan komunitas perikanan demi kemajuan bersama.</p>

      <p>Jika Anda ingin mempelajari lebih lanjut tentang Fishyman atau bergabung dengan kami, jangan ragu untuk menghubungi kami.</p>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <p class="mb-0">&copy; 2024 Fishyman. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>