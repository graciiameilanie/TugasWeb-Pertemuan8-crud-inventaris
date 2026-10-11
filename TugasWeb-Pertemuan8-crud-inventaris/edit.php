<?php
// UPDATE: form terisi data lama, lalu simpan perubahan
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = Database::getInstance()->getConnection();
$id = (int) ($_GET['id'] ?? 0);

// Ambil data lama untuk mengisi form
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('danger', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$suppliers  = $pdo->query("SELECT id, name FROM suppliers ORDER BY name")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = product_from_post();
    $errors = validate_product($product);

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE products
                 SET name = ?, category_id = ?, supplier_id = ?, price = ?, stock = ?
                 WHERE id = ?"
            );
            $stmt->execute([
                $product['name'],
                $product['category_id'],
                $product['supplier_id'],
                (float) $product['price'],
                (int) $product['stock'],
                $id,
            ]);
            set_flash('success', 'Produk berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            set_flash('danger', 'Produk gagal diperbarui. Pastikan kategori dan supplier valid.');
            redirect("edit.php?id=$id");
        }
    }
}

$pageTitle = 'Edit Produk';
$submitLabel = 'Simpan perubahan';
require 'includes/header.php';
?>
<h1>Edit produk</h1>
<?php require 'includes/product_form.php'; ?>
<?php require 'includes/footer.php'; ?>