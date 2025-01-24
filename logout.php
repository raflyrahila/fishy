<?php
require_once 'controller/auth.php';
$auth = new Auth($connection);
$auth->logout();
?>
