<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../controller/koneksi.php';
require_once '../controller/auth.php';
require_once '../controller/hakadmin.php';

header('Content-Type: application/json');

try {
    $auth = new Auth($connection);
    $admin = new Aksi($connection);

    if (!$admin->isAdmin()) {
        throw new Exception('Akses ditolak');
    }

    $id = $_POST['id'] ?? null;
    $jenis_transaksi = $_POST['jenis_transaksi'] ?? null;

    if (!$id || !$jenis_transaksi) {
        throw new Exception('Data tidak lengkap');
    }

    $result = $admin->hapusTransaksi($id, $jenis_transaksi);
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Transaksi berhasil dihapus' : 'Gagal menghapus transaksi'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
exit;
?>