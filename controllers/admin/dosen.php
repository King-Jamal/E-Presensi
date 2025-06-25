<?php
require_once ("../../config/database.php");

// Create data
if(isset($_POST['submit'])) {
	$NIP = $_POST['NIP'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	$koneksi->query("INSERT INTO dosen VALUES (null,'$NIP','$nama','$username','$password')");
	header("location: dosen.php");
}

// Update data
if(isset($_POST['update'])){
	$NIP = $_POST['NIP'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	$koneksi->query("UPDATE mahasiswa SET NIP='$NIP', Nama='$nama', Username='$username', Password='$password' WHERE Dosen_id='$id'");
	header("location: dosen.php");
}
// Edit data
$edit=False;
$data=[];
if(isset($_GET['edit'])){
	$edit=True;
	$id = $_GET['edit'];
	$data=$koneksi->query("SELECT * FROM dosen WHERE Dosen_id='$id'")->fetch_assoc();
}

// Delete data
if(isset($_GET['hapus'])){
	$id = $_GET['hapus'];
	$koneksi->query("DELETE FROM dosen WHERE Dosen_id='$id'");
	header("location: dosen.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Tabel Dosen</title>
</head>
<body>
 
	<h2>Tabel Dosen</h2>
	<br/>
	<a href="tambah.php">+ Tambah Dosen</a>
	<br/>
	<br/>
	<table border="1">
		<tr>
			<th>Dosen_id</th>
			<th>NIP</th>
			<th>Nama</th>
			<th>Username</th>
			<th>Password</th>
			<th>Aksi</th>
		</tr>
		<?php 
		include '../../config/database.php';
		$no = 1;
		$data = mysqli_query($koneksi,"select * from dosen");
		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $no++ ?></td>
				<td><?php echo $d['NIP']; ?></td>
				<td><?php echo $d['Nama']; ?></td>
				<td><?php echo $d['Username']; ?></td>
				<td><?php echo $d['Password']; ?></td>
				<td>
                   <a href=>EDIT</a>
					<a href=>HAPUS</a>
				</td>
			</tr>
			<?php 
		}
		?>
	</table>  
</body>
</html>
