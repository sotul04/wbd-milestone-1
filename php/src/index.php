<?php

echo 'Hello World!';
echo '<script src="/public/index.js"></script>';

// Coba koneksi ke database PostgreSQL
$host = getenv('DB_HOST');
$dbname = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

echo '<br>';
echo $host;
echo '<br>';
echo $dbname;
echo '<br>';
echo $user;
echo '<br>';
echo $password;
echo '<br>';

try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password);
    echo "Connected to the PostgreSQL database successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}