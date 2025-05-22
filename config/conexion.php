<?php
date_default_timezone_set('America/Bogota');

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['host', 'db', 'usuario', 'password']);

$host = $_ENV['host'];
$name = $_ENV['db'];
$name2 = $_ENV['db2'];
$user = $_ENV['usuario'];
$pass = $_ENV['password'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $pass);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error de conexión a la base de datos principal: " . $e->getMessage()]);
    exit;
}

try {
    $conn2 = new PDO("mysql:host=$host;dbname=$name2;charset=utf8", $user, $pass);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error de conexión a la segunda base de datos: " . $e->getMessage()]);
    exit;
}
?>