<?php
// Koneksi PDO dengan Singleton pattern
class Database
{
    private static ?Database $instance = null;
    private PDO $conn;

    // Private supaya tidak bisa dibuat lewat "new Database()" dari luar
    private function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=inventaris_db;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, "root", "", $options);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    // Satu-satunya cara mengambil objek Database
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}