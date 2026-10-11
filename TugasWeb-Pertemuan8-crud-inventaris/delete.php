<?php
// DELETE: hanya boleh lewat POST, bukan lewat link biasa
require_once 'config/database.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$pdo = Database::getInstance()->getConnection();
$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        set_flash('success', 'Produk berhasil dihapus.');
    } else {
        set_flash('danger', 'Produk tidak ditemukan.');
    }
} catch (PDOException $e) {
    set_flash('danger', 'Produk gagal dihapus.');
}

redirect('index.php');