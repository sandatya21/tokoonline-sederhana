<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $uname = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Validasi pengguna berdasarkan username dan password
    $result = $conn->query("SELECT * FROM users WHERE username='$uname' AND password='$password'");

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;

        // Arahkan berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php"); // untuk pelanggan
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - SayurKu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6 text-green-600 text-center">Login Pengguna</h2>
    <?php if (isset($error)): ?>
      <p class="text-red-500 mb-4"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded mb-4" required>
      <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded mb-4" required>
      <button type="submit" name="login" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Login</button>
      <p class="mt-4 text-sm text-center">Belum punya akun? <a href="register.php" class="text-green-600 hover:underline">Daftar</a></p>
    </form>
  </div>
</body>
</html>
