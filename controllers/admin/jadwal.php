<?php
require_once '../../config/database.php';

// create
if (isset($_POST['submit'])) {
    $mk_id = $_POST['Mk_id'];
    $kelas_id = $_POST['Kelas_id'];
    $dosen_id = $_POST['Dosen_id'];
    $ruangan_id = $_POST['Ruangan_id'];
    $hari = $_POST['Hari'];
    $jam_mulai = $_POST['Jam_mulai'];
    $jam_selesai = $_POST['Jam_selesai'];

    $koneksi->query("INSERT INTO jadwal(Mk_id, Kelas_id, Dosen_id, Ruangan_id, Hari, Jam_mulai, Jam_selesai) 
                    VALUES ('$mk_id','$kelas_id','$dosen_id','$ruangan_id','$hari','$jam_mulai','$jam_selesai')");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $koneksi->query("DELETE FROM jadwal WHERE Jadwal_id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// Edit data
$edit=False;
$data=[];
if(isset($_GET['edit'])){
	$edit=True;
	$id = $_GET['edit'];
	$data=$koneksi->query("SELECT * FROM jadwal WHERE Jadwal_id='$id'")->fetch_assoc();
}

// Update data
if(isset($_POST['update'])){
    $jadwal_id = $_POST['Jadwal_id'];
	$mk_id = $_POST['Mk_id'];
    $kelas_id = $_POST['Kelas_id'];
    $dosen_id = $_POST['Dosen_id'];
    $ruangan_id = $_POST['Ruangan_id'];
    $hari = $_POST['Hari'];
    $jam_mulai = $_POST['Jam_mulai'];
    $jam_selesai = $_POST['Jam_selesai'];

    $koneksi->query("UPDATE jadwal SET Mk_id='$mk_id', Kelas_id='$kelas_id', Dosen_id='$dosen_id', Ruangan_id='$ruangan_id', Hari='$hari', Jam_mulai='$jam_mulai', Jam_selesai='$jam_selesai' WHERE Jadwal_id='$id'");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
	<h3><?= $edit ? "Edit Data" : "Tambah Data" ?></h3>
    <form method="POST">
        <?php if($edit): ?>
            <input type="hidden" name="Jadwal_id" value="<?= $edit ? $data['Jadwal_id'] : "" ?>">
        <?php endif; ?>
       <label>Mata Kuliah:</label>
        <select name="Mk_id" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php $mk->data_seek(0); while($row = $mk->fetch_assoc()): ?>
                <option value="<?= $row['Mk_id'] ?>" <?= $edit && $data['Mk_id'] == $row['Mk_id'] ? 'selected' : '' ?>><?= $row['Nama_MK'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Kelas:</label>
        <select name="Kelas_id" required>
            <option value="">-- Pilih Kelas --</option>
            <?php $kelas->data_seek(0); while($row = $kelas->fetch_assoc()): ?>
                <option value="<?= $row['Kelas_id'] ?>" <?= $edit && $data['Kelas_id'] == $row['Kelas_id'] ? 'selected' : '' ?>><?= $row['Nama_kelas'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Dosen:</label>
        <select name="Dosen_id" required>
            <option value="">-- Pilih Dosen --</option>
            <?php $dosen->data_seek(0); while($row = $dosen->fetch_assoc()): ?>
                <option value="<?= $row['Dosen_id'] ?>" <?= $edit && $data['Dosen_id'] == $row['Dosen_id'] ? 'selected' : '' ?>><?= $row['Nama'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Ruangan:</label>
        <select name="Ruangan_id" required>
            <option value="">-- Pilih Ruangan --</option>
            <?php $ruangan->data_seek(0); while($row = $ruangan->fetch_assoc()): ?>
                <option value="<?= $row['Ruangan_id'] ?>" <?= $edit && $data['Ruangan_id'] == $row['Ruangan_id'] ? 'selected' : '' ?>><?= $row['Nama_ruangan'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Hari:</label>
        <select name="Hari" required>
            <option value="">-- Pilih Hari --</option>
            <?php foreach (["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"] as $hari): ?>
                <option value="<?= $hari ?>" <?= $edit && $data['Hari'] == $hari ? 'selected' : '' ?>><?= $hari ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Jam Mulai:</label>
        <input type="time" name="Jam_mulai" value="<?= $edit ? $data['Jam_mulai'] : '' ?>" required><br>
        <label>Jam Selesai:</label>
        <input type="time" name="Jam_selesai" value="<?= $edit ? $data['Jam_selesai'] : '' ?>" required><br>

        <button type="submit" name="<?= $edit ? 'update' : 'submit' ?>"><?= $edit ? 'Update' : 'Tambah' ?></button>
        <?php if ($edit): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>">Batal</a>
        <?php endif; ?>
    </form>
    <br>
    <h2>Tambah Jadwal</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th>Dosen</th>
                <th>Ruangan</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['Nama_MK'] ?></td>
                    <td><?= $row['Nama_kelas'] ?></td>
                    <td><?= $row['Nama'] ?></td>
                    <td><?= $row['Nama_ruangan'] ?></td>
                    <td><?= $row['Hari'] ?></td>
                    <td><?= $row['Jam_mulai'] ?></td>
                    <td><?= $row['Jam_selesai'] ?></td>
                    <td>
                        <a href="<?= $_SERVER['PHP_SELF'] . '?edit=' . $row['Jadwal_id'] ?>">Edit</a>
                        <a href="<?= $_SERVER['PHP_SELF'] . '?delete=' . $row['Jadwal_id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

