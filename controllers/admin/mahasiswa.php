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
?>


<!DOCTYPE html>
<html>
<head>
	<title>CRUD PHP dan MySQLi - WWW.MALASNGODING.COM</title>
</head>
<body>
	<h3><?= $edit ? "Edit Data" : "Tambah Data" ?></h3>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
		<input type="hidden" name="id" value="<?= $edit ? $data['Mahasiswa_id'] : "" ?>">
		<select name="Kelas_id" required>
			<option value="">-- Pilih Kelas --</option>
			<?php
			$kelas = $koneksi->query("SELECT * FROM kelas");
			while ($k = $kelas->fetch_assoc()) {
				$selected = ($edit && $data['Kelas_id'] == $k['Kelas_id']) ? 'selected' : '';
				echo "<option value='{$k['Kelas_id']}' $selected>{$k['Nama_kelas']}</option>";
			}
			?>
		</select>
		<br>
		NIM :<input type="text" name="NIM" value="<?= $edit ? $data['NIM'] : "" ?>" required>
		<br>
		Jurusan :<input type="text" name="Jurusan" value="<?= $edit ? $data['Jurusan'] : "" ?>" required>
		<br>
		Nama : <input type="text" name="Nama" value="<?= $edit ? $data['Nama'] : "" ?>" required>
		<br>
		Username :<input type="text" name="Username" value="<?= $edit ? $data['Username'] : "" ?>" required>
		<br>
		Password :<input type="text" name="Password" value="<?= $edit ? $data['Password'] : "" ?>" required>
		<br>
		<button type="submit" name="<?= $edit ? "update" : "submit" ?>"><?= $edit ? "Update" : "Tambahkan" ?></button>
		<?php if($edit): ?>
			<a href="mahasiswa.php">Batal</a>
		<?php endif; ?>
	</form>
	<br/>
	<br/>
	<h2>DATA MAHASISWA</h2>
	<br/>
	<table border="1">
		<tr>
			<th>Mahasiswa_id</th>
			<th>Kelas_id</th>
			<th>NIM</th>
			<th>Jurusan</th>
			<th>Nama</th>
			<th>Username</th>
			<th>Password</th>
			<th>Aksi</th>
		</tr>
		<?php 
		include '../../config/database.php';
		$data = $koneksi->query("SELECT * FROM mahasiswa");
		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $d['Mahasiswa_id']; ?></td>
				<td><?php echo $d['Kelas_id']; ?></td>
				<td><?php echo $d['NIM']; ?></td>
				<td><?php echo $d['Jurusan']; ?></td>
				<td><?php echo $d['Nama']; ?></td>
				<td><?php echo $d['Username']; ?></td>
				<td><?php echo $d['Password']; ?></td>
				<td>
					<a href="<?= $_SERVER['PHP_SELF'] . '?edit=' . $d['Mahasiswa_id'] ?>">EDIT</a>
					<a href="<?= $_SERVER['PHP_SELF'] . '?hapus=' . $d['Mahasiswa_id'] ?>">HAPUS</a>
				</td>
			</tr>
			<?php 
		}
		?>
	</table>
</body>


