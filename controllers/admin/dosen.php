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
	$id = $_POST['id'];
	$NIP = $_POST['NIP'];
	$nama = $_POST['Nama'];
	$username = $_POST['Username'];
	$password = $_POST['Password'];

	$koneksi->query("UPDATE dosen SET NIP='$NIP', Nama='$nama', Username='$username', Password='$password' WHERE Dosen_id='$id'");
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
	<br/>
	<h3><?= $edit ? "Edit Data" : "Tambah Data" ?></h3>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
		<input type="hidden" name="id" value="<?= $edit ? $data['Dosen_id'] : "" ?>">
		NIP :<input type="text" name="NIP" value="<?= $edit ? $data['NIP'] : "" ?>" required>
		<br>
		Nama : <input type="text" name="Nama" value="<?= $edit ? $data['Nama'] : "" ?>" required>
		<br>
		Username :<input type="text" name="Username" value="<?= $edit ? $data['Username'] : "" ?>" required>
		<br>
		Password :<input type="text" name="Password" value="<?= $edit ? $data['Password'] : "" ?>" required>
		<br>
		<button type="submit" name="<?= $edit ? "update" : "submit" ?>"><?= $edit ? "Update" : "Tambahkan" ?></button>
		<?php if($edit): ?>
			<a href="dosen.php">Batal</a>
		<?php endif; ?>

	</form>
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
		$data = mysqli_query($koneksi,"SELECT * FROM dosen");
		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $d['Dosen_id']; ?></td>
				<td><?php echo $d['NIP']; ?></td>
				<td><?php echo $d['Nama']; ?></td>
				<td><?php echo $d['Username']; ?></td>
				<td><?php echo $d['Password']; ?></td>
				<td>
					<a href="<?= $_SERVER['PHP_SELF'] . '?edit=' . $d['Dosen_id'] ?>">EDIT</a>
					<a href="<?= $_SERVER['PHP_SELF'] . '?hapus=' . $d['Dosen_id'] ?>">HAPUS</a>
				</td>
			</tr>
			<?php 
		}
		?>
	</table>  
</body>
</html>
