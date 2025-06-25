<?php 
session_start();

require_once "../../config/database.php";
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'dosen') {
    header("location: ../../controllers/login.php");
    exit;
}
$id=(int)$_SESSION['Dosen_id'];
$count_cls = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Dosen_id = $id")->fetch_assoc()['total'];
$hari = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$today=date('l');
$day = $hari[$today];
$count_s = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Hari = '$day'")->fetch_assoc()['total'];



$query = "SELECT 
    k.Nama_kelas AS Nama_kelas,
    mk.Nama_MK AS Nama_MK,
    COUNT(m.Mahasiswa_id) AS Jumlah_mahasiswa,
    j.Hari,
    j.Jam_mulai,
    j.Jam_selesai
FROM jadwal j
JOIN kelas k ON j.Kelas_id = k.Kelas_id
JOIN mata_kuliah mk ON j.Mk_id = mk.Mk_id
LEFT JOIN mahasiswa m ON m.Kelas_id = k.Kelas_id
WHERE j.Dosen_id = $id
GROUP BY j.Jadwal_id
";

$result = $koneksi->query($query);
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
    <p>Jumlah Kelas : <?=$count_cls?></p>
    <p>Jumlah Kelas Hari Ini : <?=$count_s?></p>
    <p>daftar kelas: </p>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Kelas</th>
                <th>Mata Kuliah</th>
                <th>Jumlah Mahasiswa</th>
                <th>Hari</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Nama_kelas'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['Nama_MK'] ?? '-') ?></td>
                <td><?= $row['Jumlah_mahasiswa'] ?? 0 ?></td>
                <td><?= $row['Hari'] ?? '-' ?></td>
                <td><?= $row['Jam_mulai'] . ' - ' . $row['Jam_selesai'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>