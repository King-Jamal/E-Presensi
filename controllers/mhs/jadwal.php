<?php
require_once "../../config/database.php";

// Pastikan user adalah mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../controllers/login.php");
    exit;
}

// Ambil Mahasiswa_id dan Kelas_id
$user_id = (int)$_SESSION['user_id'];
$result = $koneksi->query("SELECT Mahasiswa_id, Kelas_id FROM mahasiswa WHERE user_id = $user_id");
if ($result->num_rows === 0) {
    die("Data mahasiswa tidak ditemukan.");
}
$mhs = $result->fetch_assoc();
$kelas_id = $mhs['Kelas_id'];


// Ambil jadwal berdasarkan kelas mahasiswa
$query = "
    SELECT 
        j.Hari,
        j.Jam_mulai,
        j.Jam_selesai,
        mk.Nama_MK,
        k.Nama_kelas
    FROM jadwal j
    JOIN mata_kuliah mk ON j.Mk_id = mk.Mk_id
    JOIN kelas k ON j.Kelas_id = k.Kelas_id
    WHERE j.Kelas_id = $kelas_id
    ORDER BY FIELD(j.Hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), j.Jam_mulai
";

$jadwal = $koneksi->query($query);

$bg_by_day = [
    'Senin' => 'bg-red-600',
    'Selasa' => 'bg-green-600',
    'Rabu' => 'bg-blue-600',
    'Kamis' => 'bg-yellow-600',
    'Jumat' => 'bg-purple-600',
    'Sabtu' => 'bg-pink-600',
    'Minggu' => 'bg-gray-600',
];

?>


  <div class="w-[1000px] max-w-full mx-auto">
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Jadwal Mata Kuliah</h2>
        <?php if ($jadwal->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <?php while ($row = $jadwal->fetch_assoc()): ?>
                    <?php $bg = $bg_by_day[$row['Hari']] ?? 'bg-white'; ?>
                    <div class="max-w-sm mx-auto <?= $bg ?> shadow-lg rounded-xl overflow-hidden border border-gray-200">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-white mb-2">Mata Kuliah: <?= $row['Nama_MK'] ?></h2>
                            <p class="text-md text-white mb-3">Hari: <?= $row['Hari'] ?></p>
                            <p class="text-md text-white mb-3">Jam: <?= $row['Jam_mulai'] ?> - <?= $row['Jam_selesai'] ?></p>
                            <p class="text-md text-white mb-3">Kelas: <?= $row['Nama_kelas'] ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p class="text-center text-gray-600">Tidak ada jadwal yang tersedia.</p>
        </div>
        <?php endif; ?>
       
    </div>

  </div>

