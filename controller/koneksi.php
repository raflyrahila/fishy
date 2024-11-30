<?php

try{
    $connection = new PDO('mysql:host=localhost;dbname=db_nelayan', 'root', '', array(PDO::ATTR_PERSISTENT => true));
}catch(PDOException $e){
    echo $e->getMessage();
}

include_once 'auth.php';
$user = new Auth($connection);
?>