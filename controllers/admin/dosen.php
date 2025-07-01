<?php
require_once ("../../config/database.php");

// Create data
if(isset($_POST['submit_dosen'])) {
	$NIP = $_POST['NIP'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	// 1. Insert ke tabel users
	$koneksi->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'dosen')");
	$user_id = $koneksi->insert_id; // Ambil id user terakhir

	// 2. Insert ke tabel mahasiswa
	$koneksi->query("INSERT INTO dosen ( User_id, NIP, Nama,Username,Password) VALUES ('$user_id', '$NIP', '$nama','$username','$password')");

}

// Update data
if(isset($_POST['update_dosen'])) {
	$id = $_POST['id'];
	$NIP = $_POST['NIP'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	// Ambil user_id dari mahasiswa
    $result = $koneksi->query("SELECT User_id FROM dosen WHERE Dosen_id = '$id'");
    $row = $result->fetch_assoc();
    $user_id = $row['User_id'];

    // Update data di tabel mahasiswa
    $koneksi->query("UPDATE dosen 
                     SET NIP = '$NIP',  
                         Nama = '$nama',
						 Username = '$username',
						 Password = '$password'
                     WHERE Dosen_id = '$id'");

    // Update data login di tabel users
    $koneksi->query("UPDATE users 
                     SET username = '$username', 
                         password = '$password' 
                     WHERE id = '$user_id'");
	
}
// Edit data
$edit=False;
$data=[];
if(isset($_GET['edit_dosen'])){
	$edit=True;
	$id = $_GET['edit_dosen'];
	$data=$koneksi->query("SELECT * FROM dosen WHERE Dosen_id='$id'")->fetch_assoc();
}

// Delete data
if(isset($_GET['hapus_dosen'])){
	$id = $_GET['hapus_dosen'];
	// Ambil user_id dari mahasiswa
    $result = $koneksi->query("SELECT User_id FROM dosen WHERE Dosen_id = '$id'");
    $row = $result->fetch_assoc();
    $user_id = $row['User_id'];

    // Hapus user → mahasiswa ikut terhapus karena ON DELETE CASCADE
    $koneksi->query("DELETE FROM users WHERE id = '$user_id'");

}

// Pagination
$limit = 10;
$page = isset($_GET['page_dosen']) ? (int) $_GET['page_dosen'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search_dosen']) ? $koneksi->real_escape_string($_GET['search_dosen']) : "";

// Tambahkan WHERE jika ada keyword
$where = "";
if (!empty($search)) {
    $where = "WHERE Nama LIKE '%$search%' OR NIP LIKE '%$search%' OR Username LIKE '%$search%'";
}
// Hitung total data untuk pagination
$total_data_query = $koneksi->query("SELECT COUNT(*) as total FROM dosen $where");
$total_data = $total_data_query->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data mahasiswa sesuai search + pagination
$data_dosen = $koneksi->query("SELECT * FROM dosen $where LIMIT $limit OFFSET $offset");
?>


<div class="w-[1000px] max-w-full mx-auto"  >
	<div class="bg-white p-6 rounded-lg shadow mb-6">
    	<h2 class="text-2xl font-semibold mb-4"><?= $edit ? "Edit Data Dosen" : "Tambah Data Dosen" ?></h2>
		<form action="<?= $_SERVER['PHP_SELF'] ?>#form_dosen" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        	<input type="hidden" name="id" value="<?= $edit ? $data['Dosen_id'] : "" ?>">
			<div>
				<label class="block text-sm font-medium text-gray-700">NIP</label>
				<input type="text" name="NIP" value="<?= $edit ? $data['NIP'] : "" ?>" required
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">Nama</label>
				<input type="text" name="Nama" value="<?= $edit ? $data['Nama'] : "" ?>" required
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">Username</label>
				<input type="text" name="Username" value="<?= $edit ? $data['Username'] : "" ?>" required
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">Password</label>
				<input type="text" name="Password" value="<?= $edit ? $data['Password'] : "" ?>" required
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
			</div>
			<div class="md:col-span-2 flex gap-4 mt-4">
				<button type="submit" name="<?= $edit ? "update_dosen" : "submit_dosen" ?>"
						class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
					<?= $edit ? "Update" : "Tambahkan" ?>
				</button>
				<?php if ($edit): ?>
					<a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>#form_dosen" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
				<?php endif; ?>
			</div>
			
		</form>
	</div>
    <div class="bg-white p-6 rounded-lg shadow">
    	<div class="h-full">
			<form method="GET" class="mb-4">
				<input type="text" name="search_dosen" placeholder="Cari Dosen..." value="<?= isset($_GET['search_dosen']) ? htmlspecialchars($_GET['search_dosen']) : '' ?>" class="border border-gray-300 rounded px-4 py-2 w-64">
				<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
			</form>
			<table class="min-w-full divide-y divide-gray-200 text-sm">
				<thead class="bg-gray-100">
					<tr>
						<th class="px-4 py-2 text-left">Dosen id</th>
						<th class="px-4 py-2 text-left">NIP</th>
						<th class="px-4 py-2 text-left">Nama</th>
						<th class="px-4 py-2 text-left">Username</th>
						<th class="px-4 py-2 text-left">Password</th>
						<th class="px-4 py-2 text-left">Aksi</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200">
					<?php while ($d = $data_dosen->fetch_assoc()): ?>

					<tr class="hover:bg-gray-50">
						<td class="px-4 py-2"><?= $d['Dosen_id']; ?></td>
						<td class="px-4 py-2"><?= $d['NIP']; ?></td>
						<td class="px-4 py-2"><?= $d['Nama']; ?></td>
						<td class="px-4 py-2"><?= $d['Username']; ?></td>
						<td class="px-4 py-2"><?= $d['Password']; ?></td>
						<td class="px-4 py-2">
						<a href="?edit_dosen=<?= $d['Dosen_id']; ?>#form_dosen" class="text-blue-600 hover:underline mr-2">Edit</a>
						<a href="?hapus_dosen=<?= $d['Dosen_id']; ?>#form_dosen" class="text-red-600 hover:underline">Hapus</a>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			<nav aria-label="Page navigation" class="flex justify-center mt-6">
				<ul class="inline-flex items-center -space-x-px">
					<!-- Tombol Sebelumnya -->
					<li>
					<a href="?page_dosen=<?= max(1, $page - 1) ?>" class="px-2 py-1 ml-0 leading-tight text-gray-500 bg-white  rounded-l-lg  hover:text-gray-700" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
					</li>

					<!-- Tombol Halaman -->
					<?php for ($i = 1; $i <= $total_pages; $i++): ?>
					<li>
						<a href="?page_dosen=<?= $i ?>" class="px-2 py-1 leading-tight  <?= $page == $i ? ' text-blue-500' : ' text-gray-500  hover:text-gray-700' ?>">
						<?= $i ?>
						</a>
					</li>
					<?php endfor; ?>

					<!-- Tombol Selanjutnya -->
					<li>
					<a href="?page_dosen=<?= min($total_pages, $page + 1) ?>" class="px-2 py-1 leading-tight text-gray-500 bg-white   rounded-r-lg  hover:text-gray-700" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a>
					</li>
				</ul>
			</nav>

		</div>
	
	</div>

</div>
	
	