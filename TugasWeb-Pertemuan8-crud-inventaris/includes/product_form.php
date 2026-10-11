<?php
// Form dipakai bersama oleh create.php dan edit.php
// Butuh variabel: $product, $categories, $suppliers, $errors, $submitLabel
?>
<form method="POST" class="card form">
    <?php if ($errors): ?>
        <div class="alert danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <label>Nama produk
        <input type="text" name="name" maxlength="150" value="<?= e($product['name']) ?>" required>
    </label>

    <div class="row">
        <label>Kategori
            <select name="category_id" required>
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (int) $product['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Supplier
            <select name="supplier_id" required>
                <option value="">Pilih supplier</option>
                <?php foreach ($suppliers as $s): ?>
                    <option value="<?= (int) $s['id'] ?>" <?= (int) $product['supplier_id'] === (int) $s['id'] ? 'selected' : '' ?>>
                        <?= e($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="row">
        <label>Harga (Rp)
            <input type="number" name="price" min="0" step="0.01" value="<?= e($product['price']) ?>" required>
        </label>
        <label>Stok
            <input type="number" name="stock" min="0" step="1" value="<?= e($product['stock']) ?>" required>
        </label>
    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
        <a class="btn" href="index.php">Batal</a>
    </div>
</form>