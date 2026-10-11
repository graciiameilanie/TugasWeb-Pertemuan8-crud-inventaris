<?php
// READ: daftar produk dengan JOIN ke categories dan suppliers
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = Database::getInstance()->getConnection();

$sql = "SELECT p.id, p.name, p.price, p.stock,
               c.name AS category, s.name AS supplier
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN suppliers s ON p.supplier_id = s.id
        ORDER BY p.name";

try {
    $products = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $products = [];
    set_flash('danger', 'Data produk gagal dimuat.');
}

$pageTitle = 'Daftar Produk';
require 'includes/header.php';
?>

<h1>Daftar produk</h1>

<div class="card table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="num">Harga</th>
                <th class="num">Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$products): ?>
            <tr>
                <td colspan="7" class="empty">Belum ada produk. Klik "Tambah produk" untuk mulai.</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($products as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($p['name']) ?></td>
                <td><?= e($p['category']) ?></td>
                <td><?= e($p['supplier']) ?></td>
                <td class="num"><?= e(rupiah($p['price'])) ?></td>
                <td class="num"><?= (int) $p['stock'] ?></td>
                <td>
                    <div class="actions">
                        <a class="btn btn-sm" href="edit.php?id=<?= (int) $p['id'] ?>">Edit</a>
                        <form method="POST" action="delete.php"
                              onsubmit="return confirm('Hapus produk ini?')">
                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>