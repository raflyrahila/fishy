<?php
require_once '../controller/koneksi.php';
require_once '../controller/auth.php';
require_once '../controller/hakadmin.php';

// Inisialisasi
$auth = new Auth($connection);
$admin = new Aksi($connection);

// Cek otentikasi
if (!$user->isLogin()) {
    header('Location: ../login.php');
    exit;
}

// Ambil informasi user
$userInfo = $admin->getUserInfo($_SESSION['user']['id']);

// Ambil stok ikan
$stockInfo = $admin->getStokIkan();

// Definisi harga ikan per kg
$hargaIkan = [
    'Salmon' => 75000,
    'Tuna' => 66000,
    'Bawal' => 45000,
    'Kerapu' => 90000,
];

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validasi input
        $errors = [];
        
        if (empty($_POST['tanggal_setor'])) {
            $errors[] = 'Tanggal setor harus diisi';
        }
        
        if (!isset($_POST['jenis_ikan']) || !in_array($_POST['jenis_ikan'], array_keys($hargaIkan))) {
            $errors[] = 'Jenis ikan tidak valid';
        }
        
        if (empty($_POST['jumlah_ikan']) || $_POST['jumlah_ikan'] <= 0) {
            $errors[] = 'Jumlah ikan harus lebih dari 0';
        }
        
        if (empty($errors)) {
            $_POST['harga_per_kg'] = $hargaIkan[$_POST['jenis_ikan']];
            
            if ($admin->tambahSetoran($_POST)) {
                $_SESSION['message'] = 'Setoran berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan setoran.';
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Kesalahan database: ' . $e->getMessage();
    }
    header('Location: setorikan.php');
    exit();
}

// Ambil riwayat transaksi
$transactions = $admin->getRiwayatSetor();

// Hitung total penghasilan dari setoran
$totalPenghasilan = array_reduce($transactions, function($carry, $transaction) use ($hargaIkan) {
    return $carry + ($transaction['jumlah'] * $hargaIkan[$transaction['jenis_ikan']]);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishyman | Setoran Ikan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
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
        .footer {
            margin-top: auto;
            background-color: #2c3e50 !important;
            color: #ecf0f1;
            padding: 20px 0;
            text-align: center;
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
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
                <a href="../logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php 
                    foreach ($_SESSION['errors'] as $error) {
                        echo "<p>$error</p>";
                    }
                    unset($_SESSION['errors']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-person-circle"></i> Informasi Pengguna
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> <?php echo htmlspecialchars($userInfo['nama']); ?></p>
                        <p><strong>Role:</strong> <?php echo htmlspecialchars($userInfo['role']); ?></p>
                        <p><strong>Saldo:</strong> Rp. <?php echo number_format($userInfo['saldo'], 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-box"></i> Stok Ikan
                    </div>
                    <div class="card-body">
                        <?php foreach ($stockInfo as $stock): ?>
                            <p>
                                <strong><?php echo htmlspecialchars($stock['jenis_ikan']); ?>:</strong> 
                                <?php echo htmlspecialchars($stock['jumlah']); ?> Kg
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-cash-coin"></i> Total Setoran (Rp)
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-success">
                            Rp. <?php echo number_format($totalPenghasilan, 0, ',', '.'); ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-clipboard-plus"></i> Input Setoran Ikan
            </div>
            <div class="card-body">
                <form id="setoranForm" action="" method="POST" onsubmit="return validateForm()">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal_setor" class="form-label">
                                    <i class="bi bi-calendar-date"></i> Tanggal Setor
                                </label>
                                <input type="text" class="form-control" id="tanggal_setor" name="tanggal_setor" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="jenis_ikan" class="form-label">
                                    <i class="bi bi-fish"></i> Jenis Ikan
                                </label>
                                <select class="form-control" id="jenis_ikan" name="jenis_ikan" required onchange="updateHargaJual()">
                                    <option value="">Pilih Jenis Ikan</option>
                                    <?php foreach ($hargaIkan as $jenis => $harga): ?>
                                        <option value="<?php echo $jenis; ?>" data-harga-jual="<?php echo $harga; ?>">
                                            <?php echo $jenis; ?> - Rp. <?php echo number_format($harga, 0, ',', '.'); ?>/Kg
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jumlah_ikan" class="form-label">
                                <i class="bi bi-calculator"></i> Jumlah Ikan (Kg)
                            </label>
                            <input type="number" class="form-control" id="jumlah_ikan" name="jumlah_ikan" min="0.1" step="0.1" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="total_harga" class="form-label">
                                <i class="bi bi-cash"></i> Total Harga
                            </label>
                            <input type="number" class="form-control" id="total_harga" name="total_harga" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="catatan" class="form-label">
                                <i class="bi bi-journal-text"></i> Catatan
                            </label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Setoran
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-task"></i> Riwayat Setoran Ikan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Jenis Ikan</th>
                                <th>Jumlah (Kg)</th>
                                <th>Tanggal Setor</th>
                                <th>Disetor Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['jenis_ikan']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['jumlah']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['tanggal']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['user']); ?></td>
                                <td>
                                    <a href="detail.php?id=<?php echo $transaction['id']; ?>&jenis=Setor" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Inisialisasi flatpickr untuk tanggal setor
    flatpickr("#tanggal_setor", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "today"
    });

    function updateHargaJual() {
    const jenisIkanSelect = document.getElementById('jenis_ikan');
    const jumlahIkanInput = document.getElementById('jumlah_ikan');
    const totalHargaInput = document.getElementById('total_harga');
    
    const selectedOption = jenisIkanSelect.options[jenisIkanSelect.selectedIndex];
    const hargaJual = selectedOption.getAttribute('data-harga-jual');
    
    // Perbarui total harga jika jumlah ikan sudah diisi
    if (jumlahIkanInput.value) {
        const totalHarga = hargaJual * jumlahIkanInput.value;
        totalHargaInput.value = totalHarga;
        updateTotalHargaDisplay(totalHarga);
    }
}

    document.getElementById('jumlah_ikan').addEventListener('input', function() {
        const jenisIkanSelect = document.getElementById('jenis_ikan');
        const selectedOption = jenisIkanSelect.options[jenisIkanSelect.selectedIndex];
        const hargaJual = parseFloat(selectedOption.getAttribute('data-harga-jual')) || 0;
        const jumlahIkan = parseFloat(this.value) || 0;
        
        const totalHarga = hargaJual * jumlahIkan;
        document.getElementById('total_harga').value = totalHarga;
        updateTotalHargaDisplay(totalHarga);
    });
    // Validasi form sebelum submit
    function validateForm() {
        const tanggalSetor = document.getElementById('tanggal_setor').value;
        const jenisIkan = document.getElementById('jenis_ikan').value;
        const jumlahIkan = parseFloat(document.getElementById('jumlah_ikan').value);

        if (!tanggalSetor) {
            alert('Tanggal setor harus diisi!');
            return false;
        }

        if (!jenisIkan) {
            alert('Jenis ikan harus dipilih!');
            return false;
        }

        if (isNaN(jumlahIkan) || jumlahIkan <= 0) {
            alert('Jumlah ikan harus lebih dari 0!');
            return false;
        }

        // Konfirmasi sebelum submit
        return confirm('Apakah Anda yakin ingin menambahkan setoran ikan ini?');
    }
</script>
</body>
</html>