<?php
session_start();
include("../../config/conexion.php");

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['id_sujeto'])) {
    $id_sujeto = $_POST['id_sujeto'];

    // Verificar si hay algo que se imprime antes
    ob_start(); // Comienza a capturar cualquier salida
    $consultaClase = $conn->prepare("SELECT * FROM clase WHERE id_sujeto = :id_sujeto");
    $consultaClase->bindParam(':id_sujeto', $id_sujeto, PDO::PARAM_INT);
    $consultaClase->execute();

    // Limpia cualquier salida capturada que no sea necesaria
    $output = ob_get_clean();
    if ($output) {
        // Si hay alguna salida capturada, puedes depurarla aquí
        error_log("Salida inesperada capturada: $output");
    }

    // Enviar las opciones correctas
    echo '<option value="">Seleccione...</option>';
    while ($row = $consultaClase->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . $row['id'] . '">' . $row['nom_clase'] . '</option>';
    }
}
?>
