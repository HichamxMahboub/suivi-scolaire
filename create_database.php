<?php

// Script to create MySQL database using PDO

require __DIR__ . '/vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$db   = $_ENV['DB_DATABASE'] ?? '';
$user = $_ENV['DB_USERNAME'] ?? '';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host={$host};port={$port};charset={$charset}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET {$charset} COLLATE utf8mb4_unicode_ci;");
    echo "Database '{$db}' created or already exists.\n";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}
