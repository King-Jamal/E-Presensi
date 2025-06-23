<!DOCTYPE html>
<html>
<head>
	<title>CRUD PHP dan MySQLi - WWW.MALASNGODING.COM</title>
</head>
<body>
 
	<h2>DATA KELAS MAHASISWA</h2>
	<br/>
	<a href="tambah.php">+ TAMBAH MAHASISWA</a>
	<br/>
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
		include '../../config/database.php';
		$data = mysqli_query($koneksi,"select * from kelas");
		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $d['Kelas_id']; ?></td>
				<td><?php echo $d['Nama_kelas']; ?></td>
				<td><?php echo $d['Semester']; ?></td>
				<td><?php echo $d['Tahun_ajaran']; ?></td>
				<td>
					<a href="edit.php?id=<?php echo $d['id']; ?>">EDIT</a>
					<a href="hapus.php?id=<?php echo $d['id']; ?>">HAPUS</a>
				</td>
			</tr>
			<?php 
		}
		?>
	</table>
</body>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ("../../config/database.php");
?>