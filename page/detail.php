<?php
require_once '../controller/koneksi.php';
require_once '../controller/auth.php';
require_once '../controller/hakadmin.php';

// Inisialisasi
$auth = new Auth($connection);
$admin = new Aksi($connection);

// Cek otentikasi
if (!$admin->isAdmin()) {
    header('Location: ../login.php');
    exit;
}

// Ambil parameter dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$jenis_transaksi = isset($_GET['jenis']) ? $_GET['jenis'] : '';

// Ambil detail transaksi
$detail = $admin->getDetailTransaksi($id, $jenis_transaksi);

if (!$detail) {
    // Redirect jika transaksi tidak ditemukan
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .detail-header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
        }
        .detail-header i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2c3e50;">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
                <i class="fas fa-fish"></i> Fishy Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card detail-card">
            <div class="detail-header">
                <i class="fas fa-<?php echo $jenis_transaksi == 'Setor' ? 'plus' : 'minus'; ?>"></i>
                <h5 class="mb-0">Detail Transaksi <?php echo $jenis_transaksi; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Transaksi</h6>
                        <table class="table">
                            <tr>
                                <th>ID Transaksi</th>
                                <td><?php echo htmlspecialchars($detail['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Ikan</th>
                                <td><?php echo htmlspecialchars($detail['jenis_ikan']); ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td><?php echo htmlspecialchars($detail['jumlah']); ?> Kg</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td><?php 
                                    echo htmlspecialchars(
                                        $jenis_transaksi == 'Setor' ? 
                                        $detail['tanggal_setor'] : 
                                        $detail['tanggal_jual']
                                    ); 
                                ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Pengguna</h6>
                        <table class="table">
                            <tr>
                                <th>Nama</th>
                                <td><?php echo htmlspecialchars($detail['user_nama']); ?></td>
                            </tr>
                            <?php if ($jenis_transaksi == 'Setor'): ?>
                                <tr>
                                    <th>Catatan</th>
                                    <td><?php echo htmlspecialchars($detail['catatan'] ?? '-'); ?></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th>Harga Total</th>
                                    <td>Rp <?php echo number_format($detail['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>