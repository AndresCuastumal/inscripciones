<?php
require '../../config/conexion.php';
if (isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        $sql = "SELECT p.nom_propietario, p.ape_propietario 
                FROM propietario p 
                WHERE p.id = :id";
        $stmt = $conn2->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $regPropieario = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($regPropieario, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en el servidor al obtener datos.']);
    }
} else if (isset($_POST['doc'])) {
    $doc = $_POST['doc'];

    // Consulta para obtener los datos del propietario basado en el documento
    $sql = "SELECT id, nom_propietario, ape_propietario FROM serviciosivc.propietario WHERE doc = :doc";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doc', $doc, PDO::PARAM_STR);
    $stmt->execute();

    $propietario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($propietario) {
        // Si se encuentra el propietario, devolver los datos en formato JSON
        echo json_encode([
            'success' => true,
            'id_propietario' => $propietario['id'],
            'nom_propietario' => $propietario['nom_propietario'],
            'ape_propietario' => $propietario['ape_propietario']
        ]);
    } else {
        // Si no se encuentra, devolver un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Propietario no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó el documento']);
}

?>
