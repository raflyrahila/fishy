<?php
class Aksi {
    private $connection;
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Tambah setoran ikan baru
    public function tambahSetoran($data) {
        try {
            // Mulai transaksi database
            $this->connection->beginTransaction();

            $hargaIkan = [
                'Salmon' => 75000,
                'Tuna' => 66000,
                'Bawal' => 45000,
                'Kerapu' => 90000,
            ];
        
            $harga_per_kg = $hargaIkan[$data['jenis_ikan']] ?? 0;
        
            // Hitung total harga
            $total_harga = $data['jumlah_ikan'] * $harga_per_kg;
        
            // Insert setoran
            $stmt = $this->connection->prepare("INSERT INTO setoran_ikan
                (user_id, jenis_ikan, jumlah, tanggal_setor, catatan, harga_per_kg, total_harga)
                VALUES (:user_id, :jenis_ikan, :jumlah, :tanggal_setor, :catatan, :harga_per_kg, :total_harga)");
           
            // Update stok ikan
            $this->updateStokIkan($data['jenis_ikan'], $data['jumlah_ikan'], 'tambah');
        
            // Insert setoran
            $stmt->execute([
                'user_id' => $_SESSION['user']['id'],
                'jenis_ikan' => $data['jenis_ikan'],
                'jumlah' => $data['jumlah_ikan'],
                'tanggal_setor' => $data['tanggal_setor'],
                'catatan' => $data['catatan'] ?? null,
                'harga_per_kg' => $harga_per_kg,
                'total_harga' => $total_harga
            ]);
        
            // Update saldo user
            $stmt_saldo = $this->connection->prepare("
                UPDATE users 
                SET saldo = saldo + :total_harga 
                WHERE id_user = :user_id
            ");
            $stmt_saldo->execute([
                'total_harga' => $total_harga,
                'user_id' => $_SESSION['user']['id']
            ]);

            // Commit transaksi
            $this->connection->commit();
        
            return true;
        } catch (PDOException $e) {
            // Rollback transaksi jika ada error
            $this->connection->rollBack();
            // Logging error atau throw kembali
            throw $e;
        }
    }

    // Update stok ikan
    private function updateStokIkan($jenis_ikan, $jumlah, $aksi = 'tambah') {
        $operator = $aksi == 'tambah' ? '+' : '-';
        $stmt = $this->connection->prepare("
            INSERT INTO stok_ikan (jenis_ikan, jumlah, harga_per_kg) 
            SELECT :jenis_ikan, :jumlah, 
            COALESCE((SELECT harga_per_kg FROM stok_ikan WHERE jenis_ikan = :jenis_ikan), 0)
            ON DUPLICATE KEY UPDATE 
            jumlah = jumlah $operator :jumlah
        ");
        return $stmt->execute([
            'jenis_ikan' => $jenis_ikan,
            'jumlah' => $jumlah
        ]);
    }

    // Tambah penjualan ikan
    public function tambahPenjualan($data) {
        try {
            // Mulai transaksi database
            $this->connection->beginTransaction();

            // Periksa stok tersedia
            $stmt = $this->connection->prepare("
                SELECT jumlah, harga_per_kg FROM stok_ikan 
                WHERE jenis_ikan = :jenis_ikan
            ");
            $stmt->execute(['jenis_ikan' => $data['jenis_ikan']]);
            $stok = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($stok['jumlah'] < $data['jumlah_ikan']) {
                throw new Exception('Stok ikan tidak mencukupi');
            }
            // Periksa saldo user
            $stmt_user = $this->connection->prepare("
                SELECT saldo FROM users 
                WHERE id_user = :user_id
            ");
            $stmt_user->execute(['user_id' => $_SESSION['user']['id']]);
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

            $total_harga = $data['jumlah_ikan'] * $stok['harga_per_kg'];
            
            if ($user['saldo'] < $total_harga) {
                throw new Exception('Saldo tidak mencukupi');
            }
        
            // Kurangi stok
            $this->updateStokIkan($data['jenis_ikan'], $data['jumlah_ikan'], 'kurang');
        
            // Simpan penjualan
            $stmt_jual = $this->connection->prepare("INSERT INTO penjualan_ikan
                (user_id, jenis_ikan, jumlah, total_harga, tanggal_jual, harga_per_kg)
                VALUES (:user_id, :jenis_ikan, :jumlah, :total_harga, :tanggal_jual, :harga_per_kg)");
        
            $stmt_jual->execute([
                'user_id' => $_SESSION['user']['id'],
                'jenis_ikan' => $data['jenis_ikan'],
                'jumlah' => $data['jumlah_ikan'],
                'total_harga' => $total_harga,
                'tanggal_jual' => date('Y-m-d H:i:s'),
                'harga_per_kg' => $stok['harga_per_kg']
            ]);

            // Kurangi saldo user
            $stmt_saldo = $this->connection->prepare("
                UPDATE users 
                SET saldo = saldo - :total_harga 
                WHERE id_user = :user_id
            ");
            $stmt_saldo->execute([
                'total_harga' => $total_harga,
                'user_id' => $_SESSION['user']['id']
            ]);

            // Commit transaksi
            $this->connection->commit();
        
            return true;
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            $this->connection->rollBack();
            // Set session error
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    // Ambil informasi pengguna
    public function getUserInfo($user_id) {
        $stmt = $this->connection->prepare("
            SELECT nama, role, saldo 
            FROM users 
            WHERE id_user = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ambil stok ikan
    public function getStokIkan() {
        $stmt = $this->connection->prepare("
            SELECT jenis_ikan, jumlah, harga_per_kg 
            FROM stok_ikan
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil riwayat transaksi
    public function getRiwayatTransaksi() {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                'Setor' as jenis_transaksi, 
                jenis_ikan, 
                jumlah, 
                tanggal_setor as tanggal 
            FROM setoran_ikan
            UNION
            SELECT 
                id, 
                'Jual' as jenis_transaksi, 
                jenis_ikan, 
                jumlah, 
                tanggal_jual as tanggal 
            FROM penjualan_ikan
            ORDER BY tanggal DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRiwayatSetor(){
        $stmt = $this->connection->prepare("
            SELECT 
                s.id, 
                s.jenis_ikan, 
                s.jumlah, 
                s.tanggal_setor AS tanggal,
                s.user_id,
                u.nama AS user
            FROM setoran_ikan s
            INNER JOIN users u ON s.user_id = u.id_user
            ORDER BY s.tanggal_setor DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getJualIkan(){
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                jenis_ikan, 
                jumlah, 
                tanggal_jual as tanggal,
                user_id
            FROM penjualan_ikan
            ORDER BY tanggal DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hapus transaksi
    public function hapusTransaksi($id, $jenis_transaksi) {
        $tabel = $jenis_transaksi == 'Setor' ? 'setoran_ikan' : 'penjualan_ikan';
        
        // Ambil detail transaksi untuk update stok
        $stmt = $this->connection->prepare("
            SELECT jenis_ikan, jumlah 
            FROM $tabel 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kembalikan stok jika transaksi setor
        if ($jenis_transaksi == 'Setor') {
            $this->updateStokIkan($transaksi['jenis_ikan'], $transaksi['jumlah'], 'kurang');
        } else {
            // Kembalikan stok untuk transaksi jual
            $this->updateStokIkan($transaksi['jenis_ikan'], $transaksi['jumlah'], 'tambah');
        }

        // Hapus transaksi
        $stmt = $this->connection->prepare("
            DELETE FROM $tabel 
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    // Cek apakah user adalah admin
    public function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin';
    }
    
    // Method untuk mengambil detail transaksi
    public function getDetailTransaksi($id, $jenis_transaksi) {
        if (strtolower($jenis_transaksi) == 'setor') {
            $stmt = $this->connection->prepare("
                SELECT 
                    s.*, 
                    u.nama AS user_nama,
                    s.harga_per_kg AS harga_per_kg,
                    s.total_harga AS total_harga
                FROM setoran_ikan s
                JOIN users u ON s.user_id = u.id_user
                WHERE s.id = :id
            ");
        } else {
            $stmt = $this->connection->prepare("
                SELECT 
                    p.*, 
                    u.nama AS user_nama,
                    p.harga_per_kg AS harga_per_kg,
                    p.total_harga AS total_harga
                FROM penjualan_ikan p
                JOIN users u ON p.user_id = u.id_user
                WHERE p.id = :id
            ");
        }
    
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>