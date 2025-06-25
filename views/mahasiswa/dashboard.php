<?php 
session_start();
require_once "../../config/database.php";
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'mahasiswa') {
    header("location: ../../controllers/login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Selamat Datang <?= $_SESSION['username'] ?></h1>
</body>
</html>