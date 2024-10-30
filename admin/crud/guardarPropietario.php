<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =        $_SESSION['id_usuario'];
        $nom_propietario =      $_POST['nom_propietario'];
        $ape_propietario =      $_POST['ape_propietario'];
        $tipo_doc =             $_POST['tipo_doc'];
        $doc =                  $_POST['doc'];
        $tel_propietario =      $_POST['tel_propietario'];
        $correo_propietario =   $_POST['correo_propietario'];
        $fecha_registro =       date('Y-m-d H:i:s');

        try {
            // Insertar en la base de datos con PDO
            $sql_guardar = $conn2->prepare("INSERT INTO propietario (nom_propietario, ape_propietario, tipo_doc, doc, tel_propietario, correo_propietario, id_usr_registro, fecha_registro) 
            VALUES (:nom_propietario, :ape_propietario ,:tipo_doc, :doc, :tel_propietario, :correo_propietario, :id_usr_registro, :fecha_registro)");
            
            $sql_guardar->bindValue(':nom_propietario', $nom_propietario);
            $sql_guardar->bindValue(':ape_propietario', $ape_propietario);
            $sql_guardar->bindValue(':tipo_doc', $tipo_doc);
            $sql_guardar->bindValue(':doc', $doc);
            $sql_guardar->bindValue(':tel_propietario', $tel_propietario);
            $sql_guardar->bindValue(':correo_propietario', $correo_propietario);
            $sql_guardar->bindValue(':id_usr_registro', $idUsuario);
            $sql_guardar->bindValue(':fecha_registro', $fecha_registro);
            
            if ($sql_guardar->execute()) {
                $last_id = $conn2->lastInsertId(); // Obtiene el ID del último registro insertado
                echo json_encode(['success' => true, 
                'id' => $last_id,
                'nom_propietario'=> $nom_propietario,
                'ape_propietario'=> $ape_propietario
            ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
            }

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
        }
        
        $conn2 = null; // Cerrar la conexión en PDO
    }
}
else header('Location:../index.php');
?>
