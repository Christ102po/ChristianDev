<?php
declare(strict_types=1);
function db(): PDO {
    static $connection = null;
    if ($connection instanceof PDO) return $connection;
    // Copy config.example.php to config.php, then configure your database.
    $file = dirname(__DIR__) . '/config.php';
    if (is_file($file)) require $file;
    $host = getenv('DB_HOST') ?: (defined('DB_HOST') ? DB_HOST : '127.0.0.1');
    $name = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'christian_portfolio');
    $user = getenv('DB_USER') ?: (defined('DB_USER') ? DB_USER : 'root');
    $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (defined('DB_PASS') ? DB_PASS : '');
    $port = getenv('DB_PORT') ?: (defined('DB_PORT') ? DB_PORT : '3306');
    return $connection = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
}
