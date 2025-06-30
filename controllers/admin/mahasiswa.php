<?php
require_once ("../../config/database.php");

// Create data
if(isset($_POST['submit'])){
	$kelas_id = $_POST['Kelas_id'];
	$NIM = $_POST['NIM'];
	$jurusan = $_POST['Jurusan'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	$koneksi->query("INSERT INTO mahasiswa VALUES (null,'$kelas_id','$NIM','$jurusan','$nama','$username','$password')");
	header("location: mahasiswa.php");
}

// Update data
if(isset($_POST['update'])){
	$id = $_POST['id'];
	$kelas_id = $_POST['Kelas_id'];
	$NIM = $_POST['NIM'];
	$jurusan = $_POST['Jurusan'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	$koneksi->query("UPDATE mahasiswa SET Kelas_id='$kelas_id', NIM='$NIM', Jurusan='$jurusan', Nama='$nama', Username='$username', Password='$password' WHERE Mahasiswa_id='$id'");
	header("location: mahasiswa.php");
}

// Edit data
$edit=False;
$data=[];
if(isset($_GET['edit'])){
	$edit=True;
	$id = $_GET['edit'];
	$data=$koneksi->query("SELECT * FROM mahasiswa WHERE Mahasiswa_id='$id'")->fetch_assoc();
}

// Delete data
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$koneksi->query("DELETE FROM mahasiswa WHERE Mahasiswa_id='$id'");
	header("location: mahasiswa.php");
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $koneksi->real_escape_string($_GET['search']) : "";

// Tambahkan WHERE jika ada keyword
$where = "";
if (!empty($search)) {
    $where = "WHERE Nama LIKE '%$search%' OR NIM LIKE '%$search%' OR Jurusan LIKE '%$search%'";
}
// Hitung total data untuk pagination
$total_data_query = $koneksi->query("SELECT COUNT(*) as total FROM mahasiswa $where");
$total_data = $total_data_query->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data mahasiswa sesuai search + pagination
$data_mhs = $koneksi->query("SELECT * FROM mahasiswa $where LIMIT $limit OFFSET $offset");
?>


<div class="max-w-5xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow mb-6">
      <h2 class="text-2xl font-semibold mb-4"><?= $edit ? "Edit Data Mahasiswa" : "Tambah Data Mahasiswa" ?></h2>
      <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="id" value="<?= $edit ? $data['Mahasiswa_id'] : "" ?>">

        <div>
        	<label class="block text-sm font-medium text-gray-700">Kelas</label>
        	<select name="Kelas_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
				<option value="">-- Pilih Kelas --</option>
				<?php
				$kelas = $koneksi->query("SELECT * FROM kelas");
				while ($k = $kelas->fetch_assoc()) {
				$selected = ($edit && $data['Kelas_id'] == $k['Kelas_id']) ? 'selected' : '';
				echo "<option value='{$k['Kelas_id']}' $selected>{$k['Nama_kelas']}</option>";
				}
				?>
          	</select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">NIM</label>
          <input type="text" name="NIM" value="<?= $edit ? $data['NIM'] : "" ?>" required
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Jurusan</label>
          <input type="text" name="Jurusan" value="<?= $edit ? $data['Jurusan'] : "" ?>" required
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
          <button type="submit" name="<?= $edit ? "update" : "submit" ?>"
                  class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <?= $edit ? "Update" : "Tambahkan" ?>
          </button>
          <?php if ($edit): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
      <div class="overflow-x-auto">
        <form method="GET" class="mb-4">
  			<input type="text" name="search" placeholder="Cari Mahasiswa..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="border border-gray-300 rounded px-4 py-2 w-64">
  			<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
		</form>
		<table class="min-w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-100">
			<tr>
				<th class="px-4 py-2 text-left">ID</th>
				<th class="px-4 py-2 text-left">Kelas</th>
				<th class="px-4 py-2 text-left">NIM</th>
				<th class="px-4 py-2 text-left">Jurusan</th>
				<th class="px-4 py-2 text-left">Nama</th>
				<th class="px-4 py-2 text-left">Username</th>
				<th class="px-4 py-2 text-left">Password</th>
				<th class="px-4 py-2 text-left">Aksi</th>
			</tr>
			</thead>
			<tbody class="divide-y divide-gray-200">
			<?php while ($d = $data_mhs->fetch_assoc()): ?>
			<tr class="hover:bg-gray-50">
				<td class="px-4 py-2"><?= $d['Mahasiswa_id']; ?></td>
				<td class="px-4 py-2"><?= $d['Kelas_id']; ?></td>
				<td class="px-4 py-2"><?= $d['NIM']; ?></td>
				<td class="px-4 py-2"><?= $d['Jurusan']; ?></td>
				<td class="px-4 py-2"><?= $d['Nama']; ?></td>
				<td class="px-4 py-2"><?= $d['Username']; ?></td>
				<td class="px-4 py-2"><?= $d['Password']; ?></td>
				<td class="px-4 py-2">
				<a href="?edit=<?= $d['Mahasiswa_id']; ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
				<a href="?hapus=<?= $d['Mahasiswa_id']; ?>" class="text-red-600 hover:underline">Hapus</a>
				</td>
			</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
		<nav aria-label="Page navigation" class="flex justify-center mt-6">
			<ul class="inline-flex items-center -space-x-px">
				<!-- Tombol Sebelumnya -->
				<li>
				<a href="?page=<?= max(1, $page - 1) ?>" class="px-2 py-1 ml-0 leading-tight text-gray-500 bg-white  rounded-l-lg  hover:text-gray-700" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
				</li>

				<!-- Tombol Halaman -->
				<?php for ($i = 1; $i <= $total_pages; $i++): ?>
				<li>
					<a href="?page=<?= $i ?>" class="px-2 py-1 leading-tight  <?= $page == $i ? ' text-blue-500' : ' text-gray-500  hover:text-gray-700' ?>">
					<?= $i ?>
					</a>
				</li>
				<?php endfor; ?>

				<!-- Tombol Selanjutnya -->
				<li>
				<a href="?page=<?= min($total_pages, $page + 1) ?>" class="px-2 py-1 leading-tight text-gray-500 bg-white   rounded-r-lg  hover:text-gray-700" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
				</li>
			</ul>
		</nav>


	</div>

    </div>
</div>


