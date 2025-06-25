<?php
session_start();
include '../config/database.php';

$error = '';
$selectedRole = $_POST['role'] ?? '';

// Proses login (saat form login dikirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Tentukan tabel dan field berdasarkan role
    switch ($selectedRole) {
        case 'admin':
            $query = "SELECT * FROM admin_ WHERE username = ?";
            $redirect = "../views/admin/dashboard.php";
            $id_field = "Admin_id";
            break;
        case 'dosen':
            $query = "SELECT * FROM dosen WHERE username = ?";
            $redirect = "../views/dosen/dashboard.php";
            $id_field = "Dosen_id";
            break;
        case 'mahasiswa':
            $query = "SELECT * FROM mahasiswa WHERE username = ?";
            $redirect = "../views/mahasiswa/dashboard.php";
            $id_field = "Mahasiswa_id";
            break;
        default:
            $error = "Role tidak valid!";
            $query = null;
    }

    if (!$error && $stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Cocokkan password secara langsung (plaintext)
        if ($user && $user['Password'] === $password) {
            $_SESSION['id'] = $user[$id_field];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $selectedRole;
            $_SESSION['nama'] = $user['Nama'] ?? '';

            // data user 
            if ($selectedRole === 'dosen') {
                $_SESSION['Dosen_id'] = $user['Dosen_id'];
            }

            header("Location: $redirect");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } elseif (!$stmt) {
        $error = "Query gagal disiapkan.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login E-Presensi</title>
</head>
<body>
    <div>
        <h2>E-PRESENSI MAHASISWA</h2>

        <form method="POST">
            <label for="role">Pilih Role:</label><br>
            <select name="role" id="role" onchange="this.form.submit()" required>
                <option value="">-- Pilih Role --</option>
                <option value="mahasiswa" <?= $selectedRole == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                <option value="dosen" <?= $selectedRole == 'dosen' ? 'selected' : '' ?>>Dosen</option>
                <option value="admin" <?= $selectedRole == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </form>

        <?php if ($selectedRole): ?>
        <form method="POST">
            <input type="hidden" name="role" value="<?= $selectedRole ?>">

            <label>Username:</label><br>
            <input type="text" name="username" required><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <button type="submit">Login sebagai <?= ucfirst($selectedRole) ?></button>
        </form>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
