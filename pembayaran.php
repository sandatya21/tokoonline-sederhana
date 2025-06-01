<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$res = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = {$_SESSION['user']['id']}");
if ($res->num_rows == 0) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

$order = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = $conn->real_escape_string($_POST['alamat']);

    $target_dir = "uploads/";
    $filename = "bukti_" . time() . "_" . basename($_FILES["bukti"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
        // Update alamat juga bersama bukti_transfer dan status
        $conn->query("UPDATE orders SET bukti_transfer = '$filename', alamat = '$alamat', status = 'Menunggu Konfirmasi' WHERE id = $order_id");
        echo "<script>alert('Bukti transfer dan alamat berhasil disimpan!'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Upload gagal. Coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran - SayurKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-semibold text-center text-green-600 mb-4">Instruksi Pembayaran</h2>
        <p class="mb-2"><strong>Total:</strong> Rp <?= number_format($order['total']) ?></p>
        <p class="mb-4"><strong>Silakan transfer ke rekening berikut:</strong><br>
            <span class="block mt-1">Bank BNI</span>
            <span class="block">No. Rek: <strong>1234567890</strong></span>
            <span class="block mb-2">a.n. SayurKu Official</span>
        </p>

        <form method="post" enctype="multipart/form-data">
            <label class="block mb-2 font-semibold">Alamat Pengiriman:</label>
            <textarea name="alamat" required class="w-full border p-2 rounded mb-4" placeholder="Masukkan alamat pengiriman"><?= htmlspecialchars($order['alamat']) ?></textarea>

            <label class="block mb-2 font-semibold">Upload Bukti Transfer:</label>
            <input type="file" name="bukti" accept="image/*" class="w-full border p-2 rounded mb-4" required>

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Kirim Bukti & Alamat</button>
        </form>
    </div>
</body>
</html>
