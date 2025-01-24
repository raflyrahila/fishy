<?php
require_once '../controller/koneksi.php';
require_once '../controller/auth.php';
require_once '../controller/hakadmin.php';

// Inisialisasi
$auth = new Auth($connection);
$admin = new Aksi($connection);

// Cek otentikasi
if (!$user->isLogin()){
    header('Location: ../login.php');
}

// Cek role pengguna
if ($_SESSION['user']['role'] != 'user'){
    header('Location: admin.php');
}

// Ambil informasi pengguna
$user = $admin->getUserInfo($_SESSION['user']['id']);

// Ambil stok ikan
$fish_stock = $admin->getStokIkan();

// Ambil riwayat transaksi
$transactions = $admin->getRiwayatTransaksi();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishy Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
        }
        .dashboard-header i {
            margin-right: 10px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.075);
        }
        .badge-primary {
            background-color: #3498db;
        }
        .badge-success {
            background-color: #2ecc71;
        }
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
            <a class="navbar-brand" href="../index.php">Fishyman</a>
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
                            <li><a class="dropdown-item" href="setorikan.php">Setor Ikan</a></li>
                            <li><a class="dropdown-item" href="jualikan.php">Jual Ikan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../about.php">Tentang</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="dashboard-header">
                        <i class="fas fa-user-circle"></i>
                        <h5 class="mb-0">Informasi Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <h5><?php echo htmlspecialchars($user['nama']); ?></h5>
                        <p>
                            <span class="badge badge-primary">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($user['role']); ?>
                            </span>
                        </p>
                        <p>
                            <span class="badge badge-success">
                                <i class="fas fa-wallet"></i> Rp <?php echo number_format($user['saldo'], 0, ',', '.'); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="dashboard-header">
                        <i class="fas fa-fish"></i>
                        <h5 class="mb-0">Stok Ikan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Jenis Ikan</th>
                                        <th>Jumlah (Kg)</th>
                                        <th>Harga/Kg</th>
                                        <th>Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fish_stock as $stock): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-fish text-primary"></i> 
                                            <?php echo htmlspecialchars($stock['jenis_ikan']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($stock['jumlah']); ?> Kg</td>
                                        <td>Rp <?php echo number_format($stock['harga_per_kg'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($stock['jumlah'] * $stock['harga_per_kg'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card dashboard-card">
            <div class="dashboard-header">
                <i class="fas fa-history"></i>
                <h5 class="mb-0">Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Jenis Transaksi</th>
                                <th>Jenis Ikan</th>
                                <th>Jumlah (Kg)</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                <td>
                                    <?php if($transaction['jenis_transaksi'] == 'Setor'): ?>
                                        <span class="badge bg-success"><i class="fas fa-plus"></i> Setor</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-minus"></i> Jual</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($transaction['jenis_ikan']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['jumlah']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['tanggal']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>