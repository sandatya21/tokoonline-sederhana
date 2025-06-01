<?php
session_start();
if (!isset($_SESSION['admin'])) header('Location: login.php');
include '../db.php';

if ($_POST) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];
    $target = "../assets/uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO products (name, description, stock,price, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdds", $name, $desc, $stock, $price, $image);
    $stmt->execute();

    header('Location: dashboard.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
   <body class="bg-[#d2f5e3] min-h-screen p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-green-700 text-center">Tambah Produk</h2>
    <form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-md">
        <input name="name" placeholder="Nama Produk" class="w-full p-2 mb-3 border rounded"><br>
        <textarea name="description" placeholder="Deskripsi" class="w-full p-2 mb-3 border rounded"></textarea><br>
        <input name="stock" type="number" placeholder="Stock" class="w-full p-2 mb-3 border rounded"><br>

        <input name="price" type="number" placeholder="Harga" class="w-full p-2 mb-3 border rounded"><br>
        <input name="image" type="file" class="mb-3"><br>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</body>
</html>
