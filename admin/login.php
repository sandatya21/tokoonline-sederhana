<?php
session_start();
include '../db.php'; // pastikan path ke koneksi DB benar

$error = '';

if ($_POST) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($pass, $admin['password'])) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_user'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
      body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form method="post" class="bg-white p-6 rounded shadow-md w-80">
        <h2 class="text-xl font-bold mb-4 text-center text-gray-700">Login Admin</h2>
        <?php if ($error): ?>
            <p class="text-red-500 text-sm mb-2"><?= $error ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required class="w-full mb-3 p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 w-full rounded">Login</button>
    </form>
</body>
</html>
