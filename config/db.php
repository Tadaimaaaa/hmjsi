<?php
$is_local = (isset($_SERVER['HTTP_HOST']) && (str_contains($_SERVER['HTTP_HOST'], 'localhost') || str_contains($_SERVER['HTTP_HOST'], '127.0.0.1'))) || php_sapi_name() === 'cli';

if ($is_local) {
    // Database Lokal (XAMPP)
    $host = 'localhost';
    $db   = 'hmjsi';
    $user = 'root';
    $pass = '';
    $port = '3306';
} else {
    // Database Hosting (InfinityFree / Cloud)
    $host = getenv('MYSQLHOST') ?: 'sql109.infinityfree.com';
    $db   = getenv('MYSQLDATABASE') ?: 'if0_43237968_hmjsi';
    $user = getenv('MYSQLUSER') ?: 'if0_43237968';
    $pass = getenv('MYSQLPASSWORD') ?: 'Arifazuria123';
    $port = getenv('MYSQLPORT') ?: '3306';
}
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
