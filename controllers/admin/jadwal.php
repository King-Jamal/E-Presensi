<?php
require_once '../../config/database.php';

// create
if (isset($_POST['submit_jadwal'])) {
    $mk_id = $_POST['Mk_id'];
    $kelas_id = $_POST['Kelas_id'];
    $dosen_id = $_POST['Dosen_id'];
    $ruangan_id = $_POST['Ruangan_id'];
    $hari = $_POST['Hari'];
    $jam_mulai = $_POST['Jam_mulai'];
    $jam_selesai = $_POST['Jam_selesai'];

    $koneksi->query("INSERT INTO jadwal(Mk_id, Kelas_id, Dosen_id, Ruangan_id, Hari, Jam_mulai, Jam_selesai) 
                    VALUES ('$mk_id','$kelas_id','$dosen_id','$ruangan_id','$hari','$jam_mulai','$jam_selesai')");
}
// delete
if (isset($_GET['hapus_jadwal'])) {
    $id = (int)$_GET['hapus_jadwal'];
    $koneksi->query("DELETE FROM jadwal WHERE Jadwal_id = $id");
}
// Edit data
$edit=False;
$data=[];
if(isset($_GET['edit_jadwal'])){
	$edit=True;
	$id = $_GET['edit_jadwal'];
	$data=$koneksi->query("SELECT * FROM jadwal WHERE Jadwal_id='$id'")->fetch_assoc();
}

// Update data
if(isset($_POST['update_jadwal'])){
    $jadwal_id = $_POST['Jadwal_id'];
	$mk_id = $_POST['Mk_id'];
    $kelas_id = $_POST['Kelas_id'];
    $dosen_id = $_POST['Dosen_id'];
    $ruangan_id = $_POST['Ruangan_id'];
    $hari = $_POST['Hari'];
    $jam_mulai = $_POST['Jam_mulai'];
    $jam_selesai = $_POST['Jam_selesai'];

    $koneksi->query("UPDATE jadwal SET Mk_id='$mk_id', Kelas_id='$kelas_id', Dosen_id='$dosen_id', Ruangan_id='$ruangan_id', Hari='$hari', Jam_mulai='$jam_mulai', Jam_selesai='$jam_selesai' WHERE Jadwal_id='$jadwal_id'");
}

// dropdown data
$mk = $koneksi->query("SELECT * FROM mata_kuliah");
$kelas = $koneksi->query("SELECT * FROM kelas");
$dosen = $koneksi->query("SELECT * FROM dosen");
$ruangan = $koneksi->query("SELECT * FROM ruangan");


$query = "SELECT 
            jadwal.Jadwal_id,
            mata_kuliah.Nama_MK,
            kelas.Nama_kelas,
            dosen.Nama,
            ruangan.Nama_ruangan,
            jadwal.Hari,
            jadwal.Jam_mulai,
            jadwal.Jam_selesai
          FROM jadwal
          JOIN mata_kuliah ON jadwal.Mk_id = mata_kuliah.Mk_id
          JOIN kelas ON jadwal.Kelas_id = kelas.Kelas_id
          JOIN dosen ON jadwal.Dosen_id = dosen.Dosen_id
          JOIN ruangan ON jadwal.Ruangan_id = ruangan.Ruangan_id
          ORDER BY FIELD(Hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";

$result = $koneksi->query($query);


?>
<div class="w-[1000px] max-w-full mx-auto " >
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-2xl font-semibold mb-4"><?= $edit ? "Edit Data Jadwal" : "Tambah Data Jadwal" ?></h2>
      <form action="<?= $_SERVER['PHP_SELF'] ?>#form_jadwal" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="Jadwal_id" value="<?= $edit ? $data['Jadwal_id'] : "" ?>">

            <div>
            	<label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
            	<select name="Mk_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php $mk->data_seek(0); while($row = $mk->fetch_assoc()): ?>
                        <option value="<?= $row['Mk_id'] ?>" <?= $edit && $data['Mk_id'] == $row['Mk_id'] ? 'selected' : '' ?>><?= $row['Nama_MK'] ?></option>
                    <?php endwhile; ?>
            	</select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Kelas</label>
              <select name="Kelas_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">-- Pilih Kelas --</option>
                  <?php $kelas->data_seek(0); while($row = $kelas->fetch_assoc()): ?>
                      <option value="<?= $row['Kelas_id'] ?>" <?= $edit && $data['Kelas_id'] == $row['Kelas_id'] ? 'selected' : '' ?>><?= $row['Nama_kelas'] ?></option>
                  <?php endwhile; ?>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Dosen</label>
              <select name="Dosen_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">-- Pilih Dosen --</option>
                  <?php $dosen->data_seek(0); while($row = $dosen->fetch_assoc()): ?>
                      <option value="<?= $row['Dosen_id'] ?>" <?= $edit && $data['Dosen_id'] == $row['Dosen_id'] ? 'selected' : '' ?>><?= $row['Nama'] ?></option>
                  <?php endwhile; ?>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Ruangan</label>
              <select name="Ruangan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">-- Pilih Ruangan --</option>
                  <?php $ruangan->data_seek(0); while($row = $ruangan->fetch_assoc()): ?>
                      <option value="<?= $row['Ruangan_id'] ?>" <?= $edit && $data['Ruangan_id'] == $row['Ruangan_id'] ? 'selected' : '' ?>><?= $row['Nama_ruangan'] ?></option>
                  <?php endwhile; ?>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Hari</label>
              <select name="Hari" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                  <option value="">-- Pilih Hari --</option>
                  <option value="Senin" <?= $edit && $data['Hari'] == 'Senin' ? 'selected' : '' ?>>Senin</option>
                  <option value="Selasa" <?= $edit && $data['Hari'] == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                  <option value="Rabu" <?= $edit && $data['Hari'] == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                  <option value="Kamis" <?= $edit && $data['Hari'] == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                  <option value="Jumat" <?= $edit && $data['Hari'] == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                  <option value="Sabtu" <?= $edit && $data['Hari'] == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
              <input type="time" name="Jam_mulai" value="<?= $edit ? $data['Jam_mulai'] : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
              <input type="time" name="Jam_selesai" value="<?= $edit ? $data['Jam_selesai'] : '' ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="md:col-span-2 flex gap-4 mt-4">
                <button type="submit" name="<?= $edit ? "update_jadwal" : "submit_jadwal" ?>"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <?= $edit ? "Update" : "Tambahkan" ?>
                </button>
                <?php if ($edit): ?>
					<a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>#form_jadwal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <?php endif; ?>
            </div>
      </form>

    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="h-full">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Mata Kuliah</th>
                        <th class="px-4 py-2 text-left">Kelas</th>
                        <th class="px-4 py-2 text-left">Dosen</th>
                        <th class="px-4 py-2 text-left">Ruangan</th>
                        <th class="px-4 py-2 text-left">Hari</th>
                        <th class="px-4 py-2 text-left">Jam Mulai</th>
                        <th class="px-4 py-2 text-left">Jam Selesai</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2"><?= $row['Nama_MK']; ?></td>
                        <td class="px-4 py-2"><?= $row['Nama_kelas']; ?></td>
                        <td class="px-4 py-2"><?= $row['Nama']; ?></td>
                        <td class="px-4 py-2"><?= $row['Nama_ruangan']; ?></td>
                        <td class="px-4 py-2"><?= $row['Hari']; ?></td>
                        <td class="px-4 py-2"><?= $row['Jam_mulai']; ?></td>
                        <td class="px-4 py-2"><?= $row['Jam_selesai']; ?></td>
                        <td class="px-4 py-2">

                        <a href="?edit_jadwal=<?= $row['Jadwal_id']; ?>#form_jadwal" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <a href="?hapus_jadwal=<?= $row['Jadwal_id']; ?>#form_jadwal" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
		    </table>
        </div>

    </div>


</div>

 


