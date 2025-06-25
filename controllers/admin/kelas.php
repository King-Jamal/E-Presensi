<?php
require_once ("../../config/database.php");

// Create
if(isset($_POST['submit'])){
	$kelas_id = $_POST['Kelas_id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$query = "INSERT INTO kelas (Kelas_id, Nama_kelas, Semester, Tahun_ajaran) VALUES (null,'$Nama_kelas','$Semester','$Tahun_ajaran')";
	header("location: kelas.php");
}

// Update
if(isset($_POST['update'])){
	$id = $_POST['id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$query = "UPDATE kelas SET  Nama_kelas='$Nama_kelas', Semester='$Semester', Tahun_ajaran='$Tahun_ajaran' WHERE Kelas_id='$id'";
	header("location: kelas.php");
	
}

// Edit
$edit=False;
$data=[];
if(isset($_GET['edit'])){
	$edit=True;
	$id = $_GET['edit'];
	$data=$koneksi->query("SELECT * FROM kelas WHERE Kelas_id='$id'")->fetch_assoc();
}

// Delete
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$koneksi->query("DELETE FROM kelas WHERE Kelas_id='$id'");
	header("location: kelas.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>CRUD PHP dan MySQLi</title>
</head>
<body>
 
	<h2>DATA KELAS</h2>
	<br/>
	<br/>
	<h3><?= $edit ? "Edit Data" : "Tambah Data" ?></h3>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
		<input type="hidden" name="id" value="<?= $edit ? $data['Kelas_id'] : "" ?>">
		Nama_kelas : <input type="text" name="Nama_kelas" value="<?= $edit ? $data['Nama_kelas'] : "" ?>" required>
		<br>
		Semester :<input type="text" name="Semester" value="<?= $edit ? $data['Semester'] : "" ?>" required>
		<br>
		Tahun_ajaran :<input type="text" name="Tahun_ajaran" value="<?= $edit ? $data['Tahun_ajaran'] : "" ?>" required>
		<br>
		<button type="submit" name="<?= $edit ? "update" : "submit" ?>"><?= $edit ? "Update" : "Tambahkan" ?></button>
		<?php if($edit): ?>
			<a href="kelas.php">Batal</a>
		<?php endif; ?>
	</form>
	<br/>
	<table border="1">
		<tr>
			<th>Kelas_id</th>
			<th>Nama_kelas</th>
			<th>Semester</th>
			<th>Tahun_ajaran</th>
			<th>Aksi</th>
		</tr>
		<?php 
		$data = mysqli_query($koneksi,"SELECT * FROM kelas");
		while($d = mysqli_fetch_array($data)){
		?>
			<tr>
				<td><?php echo $d['Kelas_id']; ?></td>
				<td><?php echo $d['Nama_kelas']; ?></td>
				<td><?php echo $d['Semester']; ?></td>
				<td><?php echo $d['Tahun_ajaran']; ?></td>
				<td>
					<a href="<?= $_SERVER['PHP_SELF'] . '?edit=' . $d['Kelas_id'] ?>">EDIT</a>
					<a href="<?= $_SERVER['PHP_SELF'] . '?hapus=' . $d['Kelas_id'] ?>">HAPUS</a>
				</td>
			</tr>
		<?php 
		}
		?>
	</table>
</body>
