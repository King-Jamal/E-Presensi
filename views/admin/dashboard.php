<?php
require_once '../../config/database.php';
$total_mahasiswa = $koneksi->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$total_dosen = $koneksi->query("SELECT COUNT(*) AS total FROM dosen")->fetch_assoc()['total'];
$total_kelas = $koneksi->query("SELECT COUNT(*) AS total FROM kelas")->fetch_assoc()['total'];
$total_mk=$koneksi->query("SELECT COUNT(*) AS total FROM mata_kuliah")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../../login.php");
        exit();
    }

    
    ?>
    <h1>Selamat Datang  <?= $_SESSION['username'] ?></h1>
    <div>
        <h2>Total Mahasiswa : <?=$total_mahasiswa?></h2>
    </div>
    <div>
        <h2>Total Dosen : <?=$total_dosen?></h2>
    </div>
    <div>
        <h2>Total Kelas : <?=$total_kelas?></h2>
    </div>
    <div>
        <h2>Total Mata Kuliah : <?=$total_mk?></h2>
    </div>

    <div>
        <p><a href="../../controllers/admin/mahasiswa.php">Kelola Data Mahasiswa</a></p>
    </div>
    <div>
        <p><a href="../../controllers/admin/dosen.php">Kelola Data Dosen</a></p>
    </div>
    <div>
        <p><a href="../../controllers/admin/kelas.php">Kelola Data Kelas</a></p>
    </div>
    <div>
        <h3>Kalender Jadwal Perkuliahan</h3>
        <a href="../../controllers/admin/jadwal.php">Jadwal</a>
    </div>
    <div>
        <p><a href="../../controllers/logout.php">Logout</a></p>
    </div>
</body>
</html>