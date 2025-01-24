<?php
$password = 'sayaadmin';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;

?>