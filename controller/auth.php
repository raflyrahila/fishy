<?php
include 'koneksi.php';

class Auth{
    private $connection;
    private $error;

    public function __construct($connection){
        $this->connection = $connection;
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function register($nama, $email, $password, $alamat, $no_telepon){
        try{
            $sql = "INSERT INTO users (nama, email, password, alamat,no_telepon, role ) VALUES (:nama, :email, :password, :alamat, :no_telepon, 'user')";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindParam(':alamat', $alamat);
            $stmt->bindParam(':no_telepon', $no_telepon);
            $stmt->execute();
            return true;
        } catch(PDOException $e){
            if($e->errorInfo[1] == 1062){
                $this->error = "Email sudah terdaftar";
            }else{
            echo $e->getMessage();
            return false;
            }
        }
    }

    public function login($email, $password) {
        try {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
    
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                session_start(); // Mulai sesi
                
                // Simpan data lengkap ke sesi
                $_SESSION['user'] = [
                    'id' => $user['id_user'],         // ID pengguna
                    'email' => $user['email'],   // Email pengguna
                    'role' => $user['role'],     // Peran pengguna, jika ada
                    'nama' => $user['nama']      // Nama pengguna, jika ada
                ];
                
                return true;
            } else {
                $this->error = "Email atau password salah";
                return false;
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    
    public function isLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public function getUserData() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public function logout(){
        session_destroy();
        unset($_SESSION['user']);
        header('Location: login.php');
        exit;
    }

    

    public function getError(){
        return $this->error;
    }

}

?>