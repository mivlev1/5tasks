<?php
$user = 'u41731';
$pass = '7439940';
$db = new PDO('mysql:host=localhost;dbname=u41731', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

if(!isset($_SESSION)){
    session_start();
}
?>