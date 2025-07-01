<?php
require_once ("../../config/database.php");

// Create
if(isset($_POST['submit_kelas'])){
	$kelas_id = $_POST['Kelas_id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$koneksi->query("INSERT INTO kelas (Kelas_id, Nama_kelas, Semester, Tahun_ajaran) VALUES (null,'$Nama_kelas','$Semester','$Tahun_ajaran')");
	
}

// Update
if(isset($_POST['update_kelas'])){
	$id = $_POST['Kelas_id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$koneksi->query ( "UPDATE kelas SET  Nama_kelas='$Nama_kelas', Semester='$Semester', Tahun_ajaran='$Tahun_ajaran' WHERE Kelas_id='$id'");
	
}

// Edit
$edit=False;
$data=[];
if(isset($_GET['edit_kelas'])){
	$edit=True;
	$id = $_GET['edit_kelas'];
	$data=$koneksi->query("SELECT * FROM kelas WHERE Kelas_id='$id'")->fetch_assoc();
}

// Delete
if(isset($_GET['hapus_kelas'])){
	$id = $_GET['hapus_kelas'];
	$koneksi->query("DELETE FROM kelas WHERE Kelas_id='$id'");
}
// Pagination
$limit = 10;
$page = isset($_GET['page_kelas']) ? (int) $_GET['page_kelas'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search_kelas']) ? $koneksi->real_escape_string($_GET['search_kelas']) : "";

// Tambahkan WHERE jika ada keyword
$where = "";
if (!empty($search)) {
    $where = "WHERE Nama_kelas LIKE '%$search%' OR Semester LIKE '%$search%' OR Tahun_ajaran LIKE '%$search%'";
}
// Hitung total data untuk pagination
$total_data_query = $koneksi->query("SELECT COUNT(*) as total FROM kelas $where");
$total_data = $total_data_query->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data mahasiswa sesuai search + pagination
$data_kls = $koneksi->query("SELECT * FROM kelas $where LIMIT $limit OFFSET $offset");
?>
<div class="w-[1000px] max-w-full mx-auto" >
    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <h2 class="text-2xl font-semibold mb-4"><?= $edit ? "Edit Data Kelas" : "Tambah Data Kelas" ?></h2>
      <form action="<?= $_SERVER['PHP_SELF'] ?>#form_kelas" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
		<input type="hidden" name="Kelas_id" value="<?= $edit ? $data['Kelas_id'] : "" ?>">

		<div>
          <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
          <input type="text" name="Nama_kelas" value="<?= $edit ? $data['Nama_kelas'] : "" ?>" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
        </div>
		<div>
          <label class="block text-sm font-medium text-gray-700">Semester</label>
          <input type="text" name="Semester" value="<?= $edit ? $data['Semester'] : "" ?>" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
        </div>
		<div>
          <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
          <input type="text" name="Tahun_ajaran" value="<?= $edit ? $data['Tahun_ajaran'] : "" ?>" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
        </div>
		<div class="md:col-span-2 flex gap-4 mt-4">
          <button type="submit" name="<?= $edit ? "update_kelas" : "submit_kelas" ?>"
                  class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <?= $edit ? "Update" : "Tambahkan" ?>
          </button>
          <?php if ($edit): ?>

            <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>#form_kelas" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
          <?php endif; ?>
        </div>
	  </form>


	</div>
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="h-full">
        <form method="GET" class="mb-4">
			<input type="text" name="search_kelas" placeholder="Cari Mahasiswa..." value="<?= isset($_GET['search_kelas']) ? htmlspecialchars($_GET['search']) : '' ?>" class="border border-gray-300 rounded px-4 py-2 w-64">
  			<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
		</form>
		<table class="min-w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-100">
			<tr>
				<th class="px-4 py-2 text-left">Kelas id</th>
				<th class="px-4 py-2 text-left">Nama Kelas</th>
				<th class="px-4 py-2 text-left">Semester</th>
				<th class="px-4 py-2 text-left">Tahun Ajaran</th>
				<th class="px-4 py-2 text-left">Aksi</th>
			</tr>
			</thead>
			<tbody class="divide-y divide-gray-200">
			<?php while ($d = $data_kls->fetch_assoc()): ?>
			<tr class="hover:bg-gray-50">
				<td class="px-4 py-2"><?= $d['Kelas_id']; ?></td>
				<td class="px-4 py-2"><?= $d['Nama_kelas']; ?></td>
				<td class="px-4 py-2"><?= $d['Semester']; ?></td>
				<td class="px-4 py-2"><?= $d['Tahun_ajaran']; ?></td>
				<td class="px-4 py-2">
				<a href="?edit_kelas=<?= $d['Kelas_id']; ?>#form_kelas" class="text-blue-600 hover:underline mr-2">Edit</a>
				<a href="?hapus_kelas=<?= $d['Kelas_id']; ?>#form_kelas" class="text-red-600 hover:underline">Hapus</a>
				</td>
			</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
		<nav aria-label="Page navigation" class="flex justify-center mt-6">
			<ul class="inline-flex items-center -space-x-px">
				<!-- Tombol Sebelumnya -->
				<li>
				<a href="?page_kelas=<?= max(1, $page - 1) ?>" class="px-2 py-1 ml-0 leading-tight text-gray-500 bg-white  rounded-l-lg  hover:text-gray-700" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
				</li>

				<!-- Tombol Halaman -->
				<?php for ($i = 1; $i <= $total_pages; $i++): ?>
				<li>
					<a href="?page_kelas=<?= $i ?>" class="px-2 py-1 leading-tight  <?= $page == $i ? ' text-blue-500' : ' text-gray-500  hover:text-gray-700' ?>">
					<?= $i ?>
					</a>
				</li>
				<?php endfor; ?>

				<!-- Tombol Selanjutnya -->
				<li>
				<a href="?page_kelas=<?= min($total_pages, $page + 1) ?>" class="px-2 py-1 leading-tight text-gray-500 bg-white   rounded-r-lg  hover:text-gray-700" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
				</li>
			</ul>
		</nav>
	  </div>

	</div>


</div>


