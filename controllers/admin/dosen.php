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
                    <a href="">Hapus</a>
                    <a href="">Edit</a>
				</td>
			</tr>
			<?php 
		}
		?>
	</table>
</body>
</html>
