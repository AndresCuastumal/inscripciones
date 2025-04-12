<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    header('Content-Type: application/json');    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =            $_SESSION['id_usuario'];
        $id =                   $_POST['id_edit_propietario'];
        $nom_propietario =      $_POST['edit_nom_propietario'];
        $ape_propietario =      $_POST['edit_ape_propietario'];
        $tipo_doc =             $_POST['edit_td_propietario'];
        $doc_propietario =      $_POST['edit_doc_propietario'];
        $tel_propietario =      $_POST['edit_tel_propietario'];
        $correo_propietario =   $_POST['edit_correo_propietario'];
        $fecha_actualizacion =  date('Y-m-d H:i:s');

        $conn2->query("SET @usuario_modificacion = '$idUsuario'");


        try {
            $sql_actualizar = $conn2->prepare("UPDATE propietario SET 
                nom_propietario=:nom_propietario, ape_propietario=:ape_propietario, tipo_doc=:tipo_doc, doc=:doc, tel_propietario=:tel_propietario,
                correo_propietario=:correo_propietario, id_usr_update=:id_usr_update, fecha_actualizacion=:fecha_actualizacion
                WHERE id=:id");
            $sql_actualizar->bindValue(':id', $id);
            $sql_actualizar->bindValue(':nom_propietario', $nom_propietario);
            $sql_actualizar->bindValue(':ape_propietario', $ape_propietario);
            $sql_actualizar->bindValue(':tipo_doc', $tipo_doc);
            $sql_actualizar->bindValue(':doc', $doc_propietario);
            $sql_actualizar->bindValue(':tel_propietario', $tel_propietario);
            $sql_actualizar->bindValue(':correo_propietario', $correo_propietario);
            $sql_actualizar->bindValue(':fecha_actualizacion', $fecha_actualizacion);
            $sql_actualizar->bindValue(':id_usr_update', $idUsuario);

            if ($sql_actualizar->execute()) {                
                header('Content-Type: text/html');
                echo "<script>alert('Se realizó la actualización de datos del represetante legal exitosamente'); window.location.href='../index_1.php';</script>";
                exit(); // Detén la ejecución después del redireccionamiento
            }
            
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
        }
        
        $conn2 = null; // Cerrar la conexión en PDO
    }
}
else header('Location:../index.php');
?>