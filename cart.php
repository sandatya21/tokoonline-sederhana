<?php
session_start();
include 'db.php';

// Tambah produk ke cart
if ($_POST && $_POST['action'] == 'add') {
    $id = (int) $_POST['product_id'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

// Update jumlah
if (isset($_POST['update_qty'])) {
    $id = (int) $_POST['product_id'];
    $new_qty = max(1, (int) $_POST['new_qty']);
    $_SESSION['cart'][$id] = $new_qty;
}

// Tambah Qty
if (isset($_GET['plus'])) {
    $id = (int) $_GET['plus'];
    $_SESSION['cart'][$id]++;
    header("Location: cart.php");
    exit;
}

// Kurangi Qty
if (isset($_GET['minus'])) {
    $id = (int) $_GET['minus'];
    if ($_SESSION['cart'][$id] > 1) {
        $_SESSION['cart'][$id]--;
    } else {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Hapus produk
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4 text-center">Keranjang Belanja</h1>
    <div class="text-center mb-4">
        <a href="index.php" class="text-blue-500 underline">← Kembali ke toko</a>
    </div>

    <table class="w-full mt-4 bg-white rounded shadow text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">Produk</th>
                <th class="p-3">Qty</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Total</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => $qty) {
                    $id = (int) $id;
                    $res = $conn->query("SELECT * FROM products WHERE id = $id");
                    if ($res && $res->num_rows > 0) {
                        $p = $res->fetch_assoc();
                        $subtotal = $p['price'] * $qty;
                        $total += $subtotal;
                        echo "<tr class='border-t'>
                            <td class='py-3'>{$p['name']}</td>
                            <td>
                                <div class='flex justify-center items-center space-x-2'>
                                    <a href='cart.php?minus=$id' class='bg-gray-300 px-2 rounded'>➖</a>
                                    <span>$qty</span>
                                    <a href='cart.php?plus=$id' class='bg-gray-300 px-2 rounded'>➕</a>
                                </div>
                            </td>
                            <td>Rp " . number_format($p['price']) . "</td>
                            <td>Rp " . number_format($subtotal) . "</td>
                            <td><a href='cart.php?remove=$id' class='text-red-500'>Hapus</a></td>
                        </tr>";
                    }
                }
                $_SESSION['total'] = $total;
                echo "<tr class='font-bold bg-gray-100 border-t'>
                        <td colspan='3' class='py-3 text-right pr-4'>Total</td>
                        <td>Rp " . number_format($total) . "</td>
                        <td></td>
                    </tr>";
            } else {
                echo "<tr><td colspan='5' class='text-center py-6'>Keranjang kosong</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="mt-6 text-right">
            <form action="checkout.php" method="post">
                <input type="hidden" name="total" value="<?= $total ?>">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                    Lanjut ke Checkout
                </button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
