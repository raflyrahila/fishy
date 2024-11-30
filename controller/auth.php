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

    public function logout(){
        session_destroy();
        unset($_SESSION['user']);
        return true;
    }

    public function isLogin(){
        if(isset($_SESSION['user'])){
            return true;
        }else{
            return false;
        }
    }

    public function getError(){
        return $this->error;
    }

}

?>