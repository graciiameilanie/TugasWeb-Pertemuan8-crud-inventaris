<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pengaman output HTML (pencegahan XSS), ini yang dimaksud syarat nomor 8
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Ubah angka jadi format rupiah
function rupiah($angka): string
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

// Flash message: disimpan di session, tampil sekali, lalu hilang
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

// Pindah halaman (dipakai untuk pola Post/Redirect/Get)
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

// Validasi input produk, hasilnya berupa daftar pesan error
function validate_product(array $d): array
{
    $errors = [];
    if ($d['name'] === '' || mb_strlen($d['name']) > 150) {
        $errors[] = 'Nama produk wajib diisi (maksimal 150 karakter).';
    }
    if ($d['category_id'] <= 0) {
        $errors[] = 'Kategori wajib dipilih.';
    }
    if ($d['supplier_id'] <= 0) {
        $errors[] = 'Supplier wajib dipilih.';
    }
    if (!is_numeric($d['price']) || (float) $d['price'] < 0) {
        $errors[] = 'Harga harus berupa angka dan tidak boleh negatif.';
    }
    if (!ctype_digit((string) $d['stock'])) {
        $errors[] = 'Stok harus berupa bilangan bulat tidak negatif.';
    }
    return $errors;
}

// Ambil dan bersihkan data form dari $_POST
function product_from_post(): array
{
    return [
        'name'        => trim($_POST['name'] ?? ''),
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'supplier_id' => (int) ($_POST['supplier_id'] ?? 0),
        'price'       => trim($_POST['price'] ?? ''),
        'stock'       => trim($_POST['stock'] ?? ''),
    ];
}