<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../../controllers/login.php");
    exit;
}

if (!isset($_GET['jadwal_id'])) {
    echo "Jadwal tidak ditemukan.";
    exit;
}

$jadwal_id = (int) $_GET['jadwal_id'];

// Ambil data kelas & mahasiswa
$kelasQuery = $koneksi->query("SELECT Kelas_id FROM jadwal WHERE Jadwal_id = $jadwal_id");
$kelasData = $kelasQuery->fetch_assoc();
$kelas_id = $kelasData['Kelas_id'];

$mahasiswaList = $koneksi->query("SELECT Mahasiswa_id, Nama FROM mahasiswa WHERE Kelas_id = $kelas_id ORDER BY Nama");

$tanggalHariIni = date('Y-m-d');
?>

<h2>Form Absensi</h2>
<form action="../../controllers/dosen/proses_absensi.php" method="POST">
    <input type="hidden" name="jadwal_id" value="<?= $jadwal_id ?>">
    <input type="hidden" name="tanggal" value="<?= $tanggalHariIni ?>">

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Status</th>
        </tr>
        <?php while ($mhs = $mahasiswaList->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($mhs['Nama']) ?></td>
            <td>
                <select name="status[<?= $mhs['Mahasiswa_id'] ?>]" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpha">Alpha</option>
                </select>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <button type="submit">Simpan Absensi</button>
</form>
