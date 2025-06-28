<?php 
session_start();

require_once "../../config/database.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header("location: ../../controllers/login.php");
    exit;
}
$user_id=(int)$_SESSION['user_id'];
$dosen_result=$koneksi->query("SELECT Dosen_id FROM dosen WHERE user_id=$user_id");
if ($dosen_result->num_rows === 0) {
    die("Data dosen tidak ditemukan.");
}
$dosen = $dosen_result->fetch_assoc();
$dosen_id = (int)$dosen['Dosen_id'];

$count_cls = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Dosen_id = $user_id")->fetch_assoc()['total'];
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
$count_s = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Hari = '$day' and Dosen_id = $dosen_id")->fetch_assoc()['total'];



$query_sulurh_jadwal = "SELECT 
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
WHERE j.Dosen_id = $dosen_id
GROUP BY j.Jadwal_id
";

$seluruh_jadwal = $koneksi->query($query_sulurh_jadwal);

$query_jadwal_hari = "SELECT 
    j.Jadwal_id,
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
WHERE j.Dosen_id = $dosen_id AND j.Hari = '$day'
GROUP BY j.Jadwal_id
";

$jadwal_hari_ini = $koneksi->query($query_jadwal_hari);
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
            <?php while ($row = $seluruh_jadwal->fetch_assoc()): ?>
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
    <h2>🕒 Jadwal Hari Ini (<?= $day ?>)</h2>
    <table border="1"  cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>Jam</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row=$jadwal_hari_ini -> fetch_assoc()): ?>
            <tr>
                <td><?= $row['Nama_kelas'] ?></td>
                <td><?= $row['Nama_MK'] ?></td>
                <td><?= $row['Jam_mulai'] ?> - <?= $row['Jam_selesai'] ?></td>
                <td>
                <a href="./absensi.php?jadwal_id=<?= $row['Jadwal_id'] ?>">📝 Absensi</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
       
    </table>
    <a href="./absensi.php">demo</a>
</body>
</html>