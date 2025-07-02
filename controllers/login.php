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
            $_SESSION['user_id'] = $user['id'];
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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
   
</head>
<body>
  <div class="flex justify-center items-center min-h-screen bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">E-PRESENSI</h1>
        <p class="text-sm text-gray-500">Silakan login untuk melanjutkan</p>
      </div>

      <form action="" method="POST" class="space-y-4">
        <div class="flex items-center border rounded-md overflow-hidden">
          <input type="text" id="username" name="username" required placeholder="Username"
            class="w-full px-4 py-2 outline-none text-sm text-gray-700" />
          <div class="bg-gray-100 px-3 flex items-center justify-center">
            <ion-icon name="person-outline" class="text-xl text-gray-500"></ion-icon>
          </div>
        </div>

        <div class="flex items-center border rounded-md overflow-hidden">
          <input type="password" id="password" name="password" required placeholder="Password"
            class="w-full px-4 py-2 outline-none text-sm text-gray-700" />
          <div class="bg-gray-100 px-3 flex items-center justify-center">
            <ion-icon name="lock-closed-outline" class="text-xl text-gray-500"></ion-icon>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
          Login
        </button>
      </form>

      <div class="text-center mt-4">
        <?php if ($error): ?>
          <p class="text-sm text-red-600 font-medium"><?= $error ?></p>
        <?php endif; ?>
      </div>

      <div class="text-center mt-6">
        <p class="text-xs text-gray-400">© 2025 Sistem E-Presensi</p>
      </div>
    </div>
  </div>

    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
