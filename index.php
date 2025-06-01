<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SayurKu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-[#d2f5e3] min-h-screen p-4">

  <!-- Navbar -->
  <nav class="bg-white shadow-md sticky top-0 z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-500">SayurKu</h1>
      <ul class="flex space-x-6 text-gray-600 font-medium items-center">
  <li><a href="#home" class="hover:text-green-500">Home</a></li>
  <li><a href="#about" class="hover:text-green-500">About Us</a></li>
  <li><a href="#catalog" class="hover:text-green-500">Katalog</a></li>
  <li><a href="#testimoni" class="hover:text-green-500">Testimoni</a></li>

  <?php if (isset($_SESSION['user'])): ?>
    <li class="text-green-600 font-semibold">
      👋 <?= htmlspecialchars($_SESSION['user']['username']) ?>
    </li>
    <li><a href="logout.php" class="hover:text-green-500">Logout</a></li>
  <?php else: ?>
    <li><a href="login.php" class="hover:text-green-500">Login</a></li>
  <?php endif; ?>
</ul>

      <a href="cart.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full text-sm">
        🛒 Keranjang
      </a>
    </div>
  </nav>
  <!-- Status Pesanan Pelanggan -->
  <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'pelanggan'): ?>
  <div class="container mx-auto px-6">
    <div class="bg-white p-4 mt-6 shadow rounded">
      <h2 class="text-xl font-semibold mb-2">Status Pesanan Anda</h2>
      <table class="w-full text-sm text-center">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-2">ID</th>
            <th class="p-2">Produk Dibeli</th>
            <th class="p-2">Total</th>
            <th class="p-2">Status Verifikasi</th>
            <th class="p-2">Catatan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $uid = $_SESSION['user']['id'];
          $orders = $conn->query("SELECT * FROM orders WHERE user_id = $uid ORDER BY id DESC");
          while ($o = $orders->fetch_assoc()):
            // Query untuk ambil produk dalam order ini
            $order_id = $o['id'];
            $items_res = $conn->query(
              "SELECT p.name, oi.qty FROM order_items oi 
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.order_id = $order_id"
            );

            // Buat array produk dengan qty
            $products_list = [];
            while ($item = $items_res->fetch_assoc()) {
              $products_list[] = htmlspecialchars($item['name']) . " (x" . $item['qty'] . ")";
            }
          ?>
          <tr class="border-t">
            <td class="p-2"><?= $o['id'] ?></td>
            <td class="p-2"><?= implode(", ", $products_list) ?></td>
            <td class="p-2">Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
            <td class="p-2"><?= htmlspecialchars($o['status_verifikasi']) ?></td>
            <td class="p-2"><?= htmlspecialchars($o['catatan_admin']) ?></td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
  <!-- Hero Section -->
  <section id="home" class="text-center py-16 bg-gradient-to-br from-green-200 to-white">
    <h2 class="text-4xl font-bold mb-4 text-green-600">Selamat Datang di SayurKu</h2>
    <p class="text-lg text-gray-600">Temukan sayuran terbaik di sini!</p>
  </section>
<div class="my-8 border-t border-gray-300"></div>

  <!-- About Section -->
  <section id="about" class="container mx-auto px-6 py-12 text-center">
    <h3 class="text-2xl font-semibold mb-4 text-green-600">Tentang Kami</h3>
    <p class="text-gray-700 leading-relaxed max-w-xl mx-auto">
      Kami menyediakan berbagai produk sayuran yang berkualitas. Produk kami dipetik langsung dari petani lokal untuk menjamin kesegaran dan kualitas terbaik.
    </p>
  </section>
<div class="my-8 border-t border-gray-300"></div>

 <!-- Katalog Produk -->
<section id="catalog" class="container mx-auto px-6 py-12" x-data="{ modalOpen: false, modalImage: '' }">
  <h3 class="text-2xl font-semibold mb-6 text-green-600 text-center">Katalog Produk</h3>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php
    $result = $conn->query("SELECT * FROM products");
    while ($row = $result->fetch_assoc()):
    ?>
    <div class="bg-white rounded shadow p-4 hover:shadow-lg transition">
      <div class="overflow-hidden rounded mb-3 cursor-pointer" @click="modalOpen = true; modalImage = 'assets/uploads/<?= $row['image'] ?>'">
        <img 
          src="assets/uploads/<?= $row['image'] ?>" 
          class="w-full h-48 object-cover transform transition-transform duration-300 hover:scale-110"
          alt="<?= $row['name'] ?>"
        >
      </div>
      <h4 class="text-lg font-bold text-center"><?= $row['name'] ?></h4>
      <p class="text-sm text-gray-600 mb-1 text-center"><?= $row['description'] ?></p>
       <h6 class="text-base font-bold text-red-500 text-center">Stock : <?= $row['stock'] ?></h6>
      <p class="text-green-600 font-bold mb-2 text-center">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
      <form method="post" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="action" value="add">
        <div class="flex justify-center mt-2">
  <button class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
    Tambah ke Keranjang
  </button>
</div>

      </form>
    </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal -->
  <div 
    x-show="modalOpen" 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50"
    x-transition
    x-cloak
  >
    <div class="bg-white p-4 rounded shadow-lg max-w-3xl w-full relative">
      <button @click="modalOpen = false" class="absolute top-2 right-2 text-red-500 text-xl font-bold">&times;</button>
      <img :src="modalImage" alt="Preview" class="w-full h-auto rounded">
    </div>
  </div>
</section>


<div class="my-8 border-t border-gray-300"></div>


  <!-- Testimoni -->
  <section id="testimoni" class="container mx-auto px-6 py-12 text-center">
    <h3 class="text-2xl font-semibold mb-6 text-green-600">Testimoni Pelanggan</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-md">
        <p class="text-gray-700 italic mb-3">"Sayurannya segar banget, pengiriman cepat, dan pelayanannya ramah!"</p>
        <h4 class="font-semibold text-green-600">— Ibu Made, Denpasar</h4>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-md">
        <p class="text-gray-700 italic mb-3">"Suka banget sama paket sayur hematnya. Praktis dan berkualitas."</p>
        <h4 class="font-semibold text-green-600">— Pak Komang, Ubud</h4>
      </div>
      <div class="bg-white p-6 rounded-lg shadow hover:shadow-md">
        <p class="text-gray-700 italic mb-3">"Sekarang saya belanja sayur online terus, ga perlu ke pasar lagi!"</p>
        <h4 class="font-semibold text-green-600">— Ni Luh, Tabanan</h4>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white text-center py-6 mt-10 border-t">
    <p class="text-sm text-gray-500">&copy; <?= date('Y') ?> SayurKu. All rights reserved.</p>
  </footer>

</body>
</html>
