<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jadwal_id = (int) $_POST['jadwal_id'];
    $tanggal = $_POST['tanggal'];
    $statusList = $_POST['status'];
    $waktuInput = date('Y-m-d H:i:s');

    foreach ($statusList as $mahasiswa_id => $status) {
        // Cek duplikat: jika sudah ada data untuk mahasiswa + jadwal + tanggal → skip
        $check = $koneksi->prepare("SELECT * FROM presensi WHERE Mahasiswa_id = ? AND Jadwal_id = ? AND Tanggal = ?");
        $check->bind_param("iis", $mahasiswa_id, $jadwal_id, $tanggal);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows == 0) {
            $stmt = $koneksi->prepare("INSERT INTO presensi (Mahasiswa_id, Jadwal_id, Tanggal, Status, Waktu_input) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $mahasiswa_id, $jadwal_id, $tanggal, $status, $waktuInput);
            $stmt->execute();
        }
    }

    echo "<script>alert('Absensi berhasil disimpan.'); window.location.href='../../views/dosen/dashboard.php';</script>";
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
$kelasQuery = $koneksi->query("SELECT Nama_kelas FROM kelas WHERE Kelas_id = $kelas_id");
$kelasData = $kelasQuery->fetch_assoc();
$nama_kelas = $kelasData['Nama_kelas'];

$mahasiswaList = $koneksi->query("SELECT Mahasiswa_id, Nama, NIM FROM mahasiswa WHERE Kelas_id = $kelas_id ORDER BY NIM");

$tanggalHariIni = date('Y-m-d');

// Cek apakah absensi sudah dilakukan hari ini untuk jadwal ini
$cek_absensi = $koneksi->prepare("SELECT COUNT(*) as jumlah FROM presensi WHERE Jadwal_id = ? AND Tanggal = ?");
$cek_absensi->bind_param("is", $jadwal_id, $tanggalHariIni);
$cek_absensi->execute();
$hasil_cek = $cek_absensi->get_result()->fetch_assoc();
$absensi_sudah_dilakukan = $hasil_cek['jumlah'] > 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" >
    <div class="mt-12 flex flex-col " >

        <div class="w-[1000px] max-w-full mx-auto" >
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <?php if ($absensi_sudah_dilakukan): ?>
                <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 mb-4 rounded">
                    Absensi untuk jadwal ini pada tanggal <strong><?= $tanggalHariIni ?></strong> sudah dilakukan.
                </div>
                <?php else: ?>
                <h2 class="text-2xl font-semibold my-5"> Absensi Mahasiswa Kelas <?= $nama_kelas ?></h2>
                <form action="<?= $_SERVER['PHP_SELF'] ?>#form_absensi" method="POST" class="grid grid-cols-1  gap-4">
                    <input type="hidden" name="jadwal_id" value="<?= $jadwal_id ?>">
                    <input type="hidden" name="tanggal" value="<?= $tanggalHariIni ?>">
                    <table class="min-w-full divide-y divide-gray-200 text-sm ">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">NIM</th>
                            <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <?php while ($mhs = $mahasiswaList->fetch_assoc()): ?>
        
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= htmlspecialchars($mhs['NIM']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($mhs['Nama']) ?></td>
                            <td class="px-4 py-2">
                                <select name="status[<?= $mhs['Mahasiswa_id'] ?>]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" >
                                    <option value="Hadir">Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Alpha">Alpha</option>
                                </select>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit"  class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </form>
                <?php endif; ?>
        
        
            
            </div>
            
        
            
        </div>
    </div>
    
</body>
</html>