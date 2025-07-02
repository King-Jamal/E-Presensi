<?php
require_once '../../config/database.php';
$total_mahasiswa = $koneksi->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$total_dosen = $koneksi->query("SELECT COUNT(*) AS total FROM dosen")->fetch_assoc()['total'];
$total_kelas = $koneksi->query("SELECT COUNT(*) AS total FROM kelas")->fetch_assoc()['total'];
$total_mk = $koneksi->query("SELECT COUNT(*) AS total FROM mata_kuliah")->fetch_assoc()['total'];

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}
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
        class="sticky top-0 h-screen w-64 z-20 bg-white shadow-xl p-6"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-bold text-gray-800">Admin Panel</h2>
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
          <a href="#form_mhs" class="block text-gray-800 hover:text-indigo-600">Mahasiswa</a>
          <a href="#form_dosen" class="block text-gray-800 hover:text-indigo-600">Dosen</a>
          <a href="#form_kelas" class="block text-gray-800 hover:text-indigo-600">Kelas</a>
          <a href="#form_jadwal" class="block text-gray-800 hover:text-indigo-600">Jadwal</a>
        </nav>

        <div class="mt-6 border-t pt-4">
          <a href="#" class="text-sm text-gray-500 hover:text-indigo-500">Contact support</a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 p-6 ml-0 md:ml-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-red-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Mahasiswa</div>
            <div class="text-3xl font-bold text-white mt-2"><?= $total_mahasiswa ?></div>
          </div>
          <div class="bg-green-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Dosen</div>
            <div class="text-3xl font-bold text-white mt-2"><?= $total_dosen ?></div>
          </div>
          <div class="bg-blue-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Kelas</div>
            <div class="text-3xl font-bold text-white mt-2"><?= $total_kelas ?></div>
          </div>
          <div class="bg-yellow-600 rounded-2xl shadow p-6 border border-gray-100">
            <div class="text-white text-sm">Total Mata Kuliah</div>
            <div class="text-3xl font-bold text-white mt-2"><?= $total_mk ?></div>
          </div>
        </div>

        <!-- Data Mahasiswa -->
        <div class="mt-8 grid grid-cols-1" id="form_mhs">
            <?php require_once '../../controllers/admin/mahasiswa.php'; ?>
        </div>
        <!-- Data Dosen -->
        <div class="mt-8 grid grid-cols-1" id="form_dosen">
            <?php require_once '../../controllers/admin/dosen.php'; ?>
        </div>
        <div class="mt-8 grid grid-cols-1" id="form_kelas">
            <?php require_once '../../controllers/admin/kelas.php'; ?>
        </div>
        <div class="mt-8 grid grid-cols-1" id="form_jadwal" >
            <?php require_once '../../controllers/admin/jadwal.php'; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Ionicons -->
</body>

<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
 <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</html>
