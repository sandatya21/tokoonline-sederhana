<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-[#d2f5e3] min-h-screen p-4">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
    <h1 class="text-3xl font-bold text-green-700 mb-2 sm:mb-0">Dashboard Produk</h1>
    <div class="flex gap-4">
      <a href="add_product.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm transition">
        + Tambah Produk
      </a>
      <a href="verifikasi_pembayaran.php" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm transition">
  Verifikasi Pembayaran
</a>
      <a href="logout.php" class="text-red-600 hover:underline text-sm">Logout</a>
    </div>
  </div>

  <!-- Tabel Produk -->
  <div class="overflow-x-auto">
    <table class="w-full bg-white rounded-lg shadow overflow-hidden">
      <thead class="bg-green-100 text-green-700">
        <tr>
          <th class="text-left px-4 py-2">Gambar</th>
          <th class="text-left px-4 py-2">Nama</th>
          <th class="text-left px-4 py-2">Harga</th>
           <th class="text-left px-4 py-2">Stock</th>
          <th class="text-left px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php
        $res = $conn->query("SELECT * FROM products");
        while ($p = $res->fetch_assoc()):
        ?>
        <tr class="border-t">
          <td class="px-4 py-2"><img src="../assets/uploads/<?= $p['image'] ?>" width="80" class="rounded shadow"></td>
          <td class="px-4 py-2"><?= $p['name'] ?></td>
          <td class="px-4 py-2">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
           <td class="px-4 py-2"><?= $p['stock'] ?></td>
          <td class="px-4 py-2 space-x-2">
            <a href="edit_product.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline text-sm">Edit</a>
            <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="text-red-600 hover:underline text-sm">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
