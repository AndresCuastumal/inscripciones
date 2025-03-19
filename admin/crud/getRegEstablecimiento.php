<?php
require '../../config/conexion.php';
if (isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        $sql = "SELECT e.*,
                b.nom_barrio, b.id as id_barrio_vereda,
                p.id as id_propietario, p.nom_propietario, p.ape_propietario, p.doc,
                c.nom_clase
                FROM serviciosivc.establecimiento e
                JOIN bdfuid.barrio b on e.id_barrio_vereda = b.id 
                JOIN serviciosivc.propietario p on e.id_propietario = p.id
                JOIN bdfuid.clase c on e.id_clase = c.id                
                WHERE e.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $regEstablecimiento = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($regEstablecimiento, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en el servidor al obtener datos.']);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó el ID.']);
}

?>
