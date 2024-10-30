<?php
require '../../conexion.php';
if (isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        $sql = "SELECT e.id, e.nom_comercial, p.nom_propietario, p.ape_propietario, e.doc 
                FROM establecimiento e 
                JOIN propietario p on e.id_propietario = p.id
                WHERE e.id = :id";
        $stmt = $conn2->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $regFuid = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($regFuid, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en el servidor al obtener datos.']);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó el ID.']);
}

?>
