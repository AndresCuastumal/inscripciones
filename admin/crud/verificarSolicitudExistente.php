<?php
require_once '../../config/conexion.php'; 

$id = $_POST['id'];
$response = ['existe' => false];

if ($id) {
    $sql = "SELECT fecha_solicitud FROM solicitud WHERE id_establecimiento = :id_establecimiento and YEAR(fecha_solicitud) = YEAR(CURDATE()) ORDER BY fecha_solicitud DESC LIMIT 1";
    $stmt = $conn2->prepare($sql);
    $stmt->bindParam(':id_establecimiento', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $response['existe'] = true;
        $response['fecha'] = date("d", strtotime($row['fecha_solicitud'])) . " de " .
                     strftime("%B", strtotime($row['fecha_solicitud'])) . " de " .
                     date("Y", strtotime($row['fecha_solicitud']));

    }
}

echo json_encode($response);
?>
