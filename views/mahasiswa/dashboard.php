
<?php
session_start();
require_once "../../config/database.php";

// Autentikasi user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../controllers/login.php");
    exit;
}

// Ambil Mahasiswa_id dari user_id
$user_id = (int)$_SESSION['user_id'];
$result = $koneksi->query("SELECT Mahasiswa_id, Nama FROM mahasiswa WHERE user_id = $user_id");
if ($result->num_rows === 0) {
    die("Data mahasiswa tidak ditemukan.");
}
$mhs = $result->fetch_assoc();
$mahasiswa_id = $mhs['Mahasiswa_id'];
$nama_mahasiswa = $mhs['Nama'];

// Ambil riwayat presensi mahasiswa
$query = "
    SELECT p.Tanggal, p.Status, mk.Nama_MK, j.Jam_mulai, j.Jam_selesai
    FROM presensi p
    JOIN jadwal j ON p.Jadwal_id = j.Jadwal_id
    JOIN mata_kuliah mk ON j.Mk_id = mk.Mk_id
    WHERE p.Mahasiswa_id = $mahasiswa_id
    ORDER BY p.Tanggal DESC
";
$riwayat = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Mahasiswa</title>
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
            <a href="#jadwal" class="block text-gray-800 hover:text-indigo-600">Jadwal</a>
            <a href="#absen" class="block text-gray-800 hover:text-indigo-600">Absensi</a>
        </nav>

        <div class="mt-6 border-t pt-4">
          <a href="#" class="text-sm text-gray-500 hover:text-indigo-500">Contact support</a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 p-6 ml-0 md:ml-0">
       
          <div class=" mt-8 grid grid-cols-1" id="jadwal">
              
            <?php require_once '../../controllers/mhs/jadwal.php'; ?>
        </div>
        <div class="mt-8" id="absen">
          <div class="w-full max-w-4xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Riwayat Kehadiran</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-5 py-3 border-b font-medium">Tanggal</th>
                                <th class="px-5 py-3 border-b font-medium">Mata Kuliah</th>
                                <th class="px-5 py-3 border-b font-medium">Jam</th>
                                <th class="px-5 py-3 border-b font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            <?php if ($riwayat->num_rows > 0): ?>
                            <?php while ($row = $riwayat->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 border-b"><?= $row['Tanggal'] ?></td>
                                <td class="px-5 py-3 border-b"><?= $row['Nama_MK'] ?></td>
                                <td class="px-5 py-3 border-b"><?= $row['Jam_mulai'] ?> - <?= $row['Jam_selesai'] ?></td>
                                <td class="px-5 py-3 border-b">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white
                                        <?= match($row['Status']) {
                                            'Hadir' => 'bg-green-500',
                                            'Izin' => 'bg-yellow-500',
                                            'Sakit' => 'bg-blue-500',
                                            'Alpha' => 'bg-red-500',
                                            default => 'bg-gray-500'
                                        } ?>">
                                        <?= $row['Status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data kehadiran.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>

        
     

   


      </main>
    </div>
  </div>
  

  <!-- Ionicons -->
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
 <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</body>
</html>
