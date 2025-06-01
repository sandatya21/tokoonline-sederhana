<?php
include 'db.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $uname = $_POST['username'];
    $password = $_POST['password'];

    $conn->query("INSERT INTO users (name, username, password) VALUES ('$name', '$uname', '$password')");
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - SayurKu</title>
  <script src="https://cdn.tailwindcss.com"></script>
   <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6 text-green-600 text-center">Daftar Akun</h2>
    <form method="post">
      <input type="text" name="name" placeholder="Nama Lengkap" class="w-full p-2 border rounded mb-4" required>
      <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded mb-4" required>
      <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded mb-4" required>
      <button type="submit" name="register" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Daftar</button>
      <p class="mt-4 text-sm text-center">Sudah punya akun? <a href="login.php" class="text-green-600 hover:underline">Login</a></p>
    </form>
  </div>
</body>
</html>
