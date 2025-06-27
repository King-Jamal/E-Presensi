<?php
session_start();
include '../config/database.php';

$error = '';

// Proses login (saat form login dikirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // jika username ditemukan
     if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // jika password cocok (plaintext)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // redirect sesuai role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../../views/admin/dashboard.php");
                    break;
                case 'dosen':
                    header("Location: ../../views/dosen/dashboard.php");
                    break;
                case 'mahasiswa':
                    header("Location: ../../views/mahasiswa/dashboard.php");
                    break;
                default:
                    echo "Role tidak dikenali.";
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
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
            <label>Username:</label><br>
            <input type="text" name="username" required><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
