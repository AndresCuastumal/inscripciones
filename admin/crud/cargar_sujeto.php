<?php
// Mostrar errores (puedes quitar esto en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión antes de enviar headers
session_start();

// Configura el header para que el navegador sepa que es JSON
header('Content-Type: application/json');

include '../../config/conexion.php';

// Verificar si la sesión está activa
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'No hay sesión activa.']);
    exit;
}        
try {
    // Consulta
    $stmt = $conn->prepare("SELECT id, nom_sujeto FROM sujeto ORDER BY nom_sujeto ASC");
    $stmt->execute();

    $sujetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar la respuesta en JSON
    echo json_encode($sujetos);
    exit;
} catch (PDOException $e) {
    // Enviar error como JSON
    echo json_encode([
        "error" => "Error al conectarse o ejecutar la consulta: " . $e->getMessage()
    ]);
}
?>