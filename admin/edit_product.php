<?php
include '../db.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-[#d2f5e3] min-h-screen p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-green-700 text-center">Edit Produk</h2>

    <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
      <div>
        <label class="block font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="name" value="<?= $product['name'] ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-300">
      </div>

      <div>
        <label class="block font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-300"><?= $product['description'] ?></textarea>
      </div>

       <div>
        <label class="block font-medium text-gray-700">Stock</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-300">
      </div>


      <div>
        <label class="block font-medium text-gray-700">Harga</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-300">
      </div>

      <div>
        <label class="block font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
        <img src="../assets/uploads/<?= $product['image'] ?>" width="150" class="rounded shadow">
      </div>

      <div>
        <label class="block font-medium text-gray-700">Ganti Gambar</label>
        <input type="file" name="image" class="w-full text-sm text-gray-600">
      </div>

      <div class="text-center">
        <button type="submit" name="update" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded transition">
          Update Produk
        </button>
      </div>
    </form>
  </div>

<?php
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $imageName = $product['image']; // default gambar lama

    if ($_FILES['image']['name']) {
        $uploadDir = "assets/uploads/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, stock=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssddsi", $name, $desc, $stock, $price, $imageName, $id);
    $stmt->execute();

    echo "<script>window.location.href = 'dashboard.php';</script>";
}
?>
</body>
</html>
