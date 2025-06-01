<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alamat = $_POST['alamat'];
    $total = $_POST['total'];

$conn->query("INSERT INTO orders (user_id, alamat, total, status) VALUES ($user_id, '$alamat', $total, 'Menunggu Pembayaran')");

$order_id = $conn->insert_id; // Ambil ID pesanan yang baru saja dibuat

// Simpan item produk ke order_items
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $product_id = (int) $product_id;
    $qty = (int) $qty;
    $conn->query("INSERT INTO order_items (order_id, product_id, qty) VALUES ($order_id, $product_id, $qty)");
}

// Kosongkan keranjang setelah checkout (opsional)
unset($_SESSION['cart']);

// Jangan ambil lagi insert_id di sini! Gunakan yang sudah didapat
header("Location: pembayaran.php?order_id=$order_id");
exit;

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout - SayurKu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6 text-green-600 text-center">Checkout</h2>
    <form method="post">
      <label class="block mb-2">Alamat Pengiriman:</label>
      <textarea name="alamat" class="w-full border rounded p-2 mb-4" required></textarea>

      <label class="block mb-2">Total:</label>
      <input type="number" name="total" class="w-full border rounded p-2 mb-4" value="<?= $_SESSION['total'] ?? 0 ?>" readonly>

      <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Bayar Sekarang</button>
    </form>
  </div>
</body>
</html>
