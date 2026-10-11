<?php
// CREATE: tambah produk dengan prepared statement
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = Database::getInstance()->getConnection();
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$suppliers  = $pdo->query("SELECT id, name FROM suppliers ORDER BY name")->fetchAll();

$product = ['name' => '', 'category_id' => 0, 'supplier_id' => 0, 'price' => '', 'stock' => 0];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = product_from_post();
    $errors = validate_product($product);

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO products (name, category_id, supplier_id, price, stock)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $product['name'],
                $product['category_id'],
                $product['supplier_id'],
                (float) $product['price'],
                (int) $product['stock'],
            ]);
            set_flash('success', 'Produk berhasil ditambahkan.');
            redirect('index.php');
        } catch (PDOException $e) {
            set_flash('danger', 'Produk gagal ditambahkan. Pastikan kategori dan supplier valid.');
            redirect('create.php');
        }
    }
}

$pageTitle = 'Tambah Produk';
$submitLabel = 'Simpan produk';
require 'includes/header.php';
?>
<h1>Tambah produk</h1>
<?php require 'includes/product_form.php'; ?>
<?php require 'includes/footer.php'; ?>