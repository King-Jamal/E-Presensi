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

$count_cls = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Dosen_id = $dosen_id")->fetch_assoc()['total'];
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
$count_cls_tdy = $koneksi->query("SELECT COUNT(*) AS total FROM jadwal WHERE Hari = '$day' and Dosen_id = $dosen_id")->fetch_assoc()['total'];



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
<html lang="en" x-data="{ isOpen: true }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  
</head>
<body x-data="{ isOpen: false }" class="bg-gray-100">
  <!-- WRAPPER -->
  <div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow p-4 flex items-center justify-between sticky top-0 z-10">
      <!-- Hamburger Button -->
      <button 
        x-show="!isOpen"
        @click="isOpen = true"
        class="text-gray-700"
      >
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
      </button>

      <!-- Log out tetap di kanan -->
      <a href="../../controllers/logout.php" class="ml-auto text-sm font-semibold text-gray-900">
        Log out <span aria-hidden="true">&rarr;</span>
      </a>
    </header>

    <div class="flex flex-1 relative">
      <!-- SIDEBAR -->
      <aside 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
        class="sticky top-0 h-screen z-20 w-64 bg-white shadow-xl p-6"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-bold text-gray-800">Dosen Panel</h2>
          <button @click="isOpen = false" class="text-gray-500 hover:text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="mb-4 text-gray-700 flex items-center gap-2">
          <ion-icon name="person-circle-outline" size="large"></ion-icon>
          <span><?= $_SESSION['username'] ?></span>
        </div>

        <nav class="space-y-3">
          <a href="#form_jadwal" class="block text-gray-800 hover:text-indigo-600">Jadwal</a>
          <a href="#form_absensi" class="block text-gray-800 hover:text-indigo-600">Absensi</a>
        </nav>

        <div class="mt-6 border-t pt-4">
          <a href="#" class="text-sm text-gray-500 hover:text-indigo-500">Contact support</a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 p-6 ml-0 md:ml-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-blue-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Kelas</div>
            <div class="text-3xl font-bold text-white mt-2"><?=$count_cls?></div>
          </div>
          <div class="bg-green-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Kelas Hari ini</div>
            <div class="text-3xl font-bold text-white mt-2"><?=$count_cls_tdy?></div>
          </div>
          
        </div>

        <!-- Data Absensi -->
        <div class="mt-8 grid grid-cols-1" id="form_absensi">
            <div class="p-6 bg-white rounded-xl shadow-md mb-8">
               <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
                  Daftar Kelas
               </h2>
               <div class="overflow-x-auto">
                   <table class="min-w-full text-sm text-left text-gray-700 border border-gray-300">
                       <thead class="bg-gray-100 text-gray-800">
                           <tr>
                               <th class="px-4 py-3 border-b border-gray-300">Kelas</th>
                               <th class="px-4 py-3 border-b border-gray-300">Mata Kuliah</th>
                               <th class="px-4 py-3 border-b border-gray-300">Hari</th>
                               <th class="px-4 py-3 border-b border-gray-300">Jam</th>
                           </tr>
                       </thead>
                       <tbody class="bg-white">
                           <?php while ($row = $seluruh_jadwal->fetch_assoc()): ?>
                           <tr class="hover:bg-gray-50">
                               <td class="px-4 py-3 border-b border-gray-200"><?= $row['Nama_kelas'] ?></td>
                               <td class="px-4 py-3 border-b border-gray-200"><?= $row['Nama_MK'] ?></td>
                               <td class="px-4 py-3 border-b border-gray-200"><?= $row['Hari'] ?></td>
                               <td class="px-4 py-3 border-b border-gray-200">
                                   <?= $row['Jam_mulai'] ?> - <?= $row['Jam_selesai'] ?>
                               </td>
                           
                           </tr>
                           <?php endwhile; ?>
                       </tbody>
                   </table>
               </div>
            </div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow-md" id="form_jadwal">
            <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
                Jadwal Hari Ini (<?= $day ?>)
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 border border-gray-300">
                    <thead class="bg-gray-100 text-gray-800">
                        <tr>
                            <th class="px-4 py-3 border-b border-gray-300">Kelas</th>
                            <th class="px-4 py-3 border-b border-gray-300">Mata Kuliah</th>
                            <th class="px-4 py-3 border-b border-gray-300">Jam</th>
                            <th class="px-4 py-3 border-b border-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php while ($row = $jadwal_hari_ini->fetch_assoc()): ?>
                        <?php
                            $jadwal_id = $row['Jadwal_id'];
                            $tanggal = date('Y-m-d');
                            $checkAbsensi = $koneksi->prepare("SELECT COUNT(*) as total FROM presensi WHERE Jadwal_id = ? AND Tanggal = ?");
                            $checkAbsensi->bind_param("is", $jadwal_id, $tanggal);
                            $checkAbsensi->execute();
                            $result = $checkAbsensi->get_result()->fetch_assoc();
                            $sudahAbsensi = $result['total'] > 0;
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border-b border-gray-200"><?= $row['Nama_kelas'] ?></td>
                            <td class="px-4 py-3 border-b border-gray-200"><?= $row['Nama_MK'] ?></td>
                            <td class="px-4 py-3 border-b border-gray-200">
                                <?= $row['Jam_mulai'] ?> - <?= $row['Jam_selesai'] ?>
                            </td>
                            <td class="px-4 py-3 border-b border-gray-200">
                                <?php if (!$sudahAbsensi): ?>
                                <a href="../../controllers/dosen/proses_absensi.php?jadwal_id=<?= $row['Jadwal_id'] ?>" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition">Absensi</a>
                                <?php else: ?>
                                <span class="text-green-600 font-semibold"> Sudah Absen</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

      </main>
    </div>
  </div>

  <!-- Ionicons -->
</body>

<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
 <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</html>
