<?php
require_once '../../config/database.php';

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
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
