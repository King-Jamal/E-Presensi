<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once ("../../config/database.php");

// Create
if(isset($_POST['submit'])){
	$kelas_id = $_POST['Kelas_id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$query = "INSERT INTO kelas (Kelas_id, Nama_kelas, Semester, Tahun_ajaran) VALUES ('$kelas_id','$Nama_kelas','$Semester','$Tahun_ajaran')";
	if (!$koneksi->query($query)) {
		echo "SQL Error: " . $koneksi->error;
	} else {
		header("location: kelas.php");
	}
}

// Update
if(isset($_POST['update'])){
	$id = $_POST['id'];
	$kelas_id = $_POST['Kelas_id'];
	$Nama_kelas = $_POST['Nama_kelas'];
	$Semester = $_POST['Semester'];
	$Tahun_ajaran = $_POST['Tahun_ajaran'];

	$query = "UPDATE kelas SET Kelas_id='$kelas_id', Nama_kelas='$Nama_kelas', Semester='$Semester', Tahun_ajaran='$Tahun_ajaran' WHERE Kelas_id='$id'";
	if (!$koneksi->query($query)) {
		echo "SQL Error: " . $koneksi->error;
	} else {
		header("location: kelas.php");
	}
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
	<a href="tambah.php">+ TAMBAH KELAS</a>
	<br/><br/>
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
