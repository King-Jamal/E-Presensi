<!DOCTYPE html>
<html>
<head>
	<title>CRUD PHP dan MySQLi - WWW.MALASNGODING.COM</title>
</head>
<body>
 
	<h2>CRUD DATA MAHASISWA - WWW.MALASNGODING.COM</h2>
	<br/>
	<a href="tambah.php">+ TAMBAH MAHASISWA</a>
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
		include 'database.php';
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
