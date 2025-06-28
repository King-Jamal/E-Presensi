<?php
session_start();
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
?>
