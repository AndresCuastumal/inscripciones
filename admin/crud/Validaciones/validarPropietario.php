<?php
require_once '../../../config/conexion.php';
session_start(); 

$doc = $_POST['doc'];
$response = ['existe' => false];

if ($doc) {
    $sql = "SELECT * FROM propietario WHERE doc = :doc LIMIT 1";
    $stmt = $conn2->prepare($sql);
    $stmt->bindParam(':doc', $doc, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $response['existe'] = true;  

    }
}

echo json_encode($response);
?>
