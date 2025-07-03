<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    header('Content-Type: application/json');    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =                $_SESSION['id_usuario'];
        $id_establecimiento =       $_POST['id'];
        $id_clase           =       $_POST['clase_editEstablecimiento'];
        $id_clase_actual   =       $_POST['clase_actual'];        
        $id_propietario =           $_POST['id_propietario'];
        $id_barrio_vereda =         $_POST['id_barrio_vereda'];
        $id_barrio_vereda_actual =  $_POST['id_barrio_vereda_actual'];
        $razon_social =             $_POST['razon_social'];
        $nom_comercial =            $_POST['nom_comercial'];
        $nit =                      $_POST['nit'];
        $dv =                       $_POST['dv'];
        $sucursal =                 $_POST['sucursal'];
        $fecha_actualizacion =      date('Y-m-d H:i:s');
        $dir_establecimiento =      $_POST['dir_establecimiento'];
        $correo_establecimiento=    $_POST['correo_establecimiento'];
        $tel_establecimiento =      $_POST['tel_establecimiento'];
        $estado =                   (isset($_POST['estado']))?$_POST['estado']:"0";

        $conn2->query("SET @usuario_modificacion = '$idUsuario'");

        try {
            $sql_actualizar = $conn2->prepare("UPDATE establecimiento SET 
                id_propietario=:id_propietario, id_barrio_vereda=:id_barrio_vereda, id_clase=:id_clase, razon_social=:razon_social, nom_comercial=:nom_comercial,
                nit=:nit,digito_verificacion=:dv,sucursal=:sucursal, fecha_actualizacion=:fecha_actualizacion, estado=:estado, dir_establecimiento=:dir_establecimiento, 
                correo_establecimiento=:correo_establecimiento, tel_establecimiento=:tel_establecimiento, id_usr_update=:id_usr_update
                WHERE id=:id_establecimiento");
            
            $sql_actualizar->bindValue(':id_establecimiento', $id_establecimiento);
            $sql_actualizar->bindValue(':id_propietario', $id_propietario);
            $sql_actualizar->bindValue(':id_barrio_vereda', $id_barrio_vereda);
            $sql_actualizar->bindValue(':id_clase', $id_clase);
            $sql_actualizar->bindValue(':razon_social', $razon_social);
            $sql_actualizar->bindValue(':nom_comercial', $nom_comercial);
            $sql_actualizar->bindValue(':nit', $nit);
            $sql_actualizar->bindValue(':dv', $dv);
            $sql_actualizar->bindValue(':sucursal', $sucursal);
            $sql_actualizar->bindValue(':fecha_actualizacion', $fecha_actualizacion);
            $sql_actualizar->bindValue(':dir_establecimiento', $dir_establecimiento);
            $sql_actualizar->bindValue(':estado', $estado);
            $sql_actualizar->bindValue(':correo_establecimiento', $correo_establecimiento);
            $sql_actualizar->bindValue(':tel_establecimiento', $tel_establecimiento);
            $sql_actualizar->bindValue(':id_usr_update', $idUsuario);            

            if ($sql_actualizar->execute()) {                
                if($id_barrio_vereda != $id_barrio_vereda_actual || $id_sujeto != $sujeto_actual) {
                    // Si el barrio/vereda ha cambiado, consulta el id del técnico asignado al nuevo barrio/vereda
                    $sql_consultarTecnico = $conn2->prepare("SELECT c.id_usuario 
                        FROM bdfuid.comunaxusuario AS c
                        JOIN bdfuid.sujetoxusuario AS s ON c.id_usuario = s.id_usuario
                        WHERE c.id_barrio_vereda = :id_barrio_vereda");
                    // Actualizar el barrio/vereda en la tabla de solicitudes
                    $sql_update_solicitud = $conn2->prepare("UPDATE solicitud SET id_barrio_vereda=:id_barrio_vereda WHERE id_establecimiento=:id_establecimiento");
                    $sql_update_solicitud->bindValue(':id_barrio_vereda', $id_barrio_vereda_actual);
                    $sql_update_solicitud->bindValue(':id_establecimiento', $id_establecimiento);
                    $sql_update_solicitud->execute();
                }
                $mensaje = 'El establecimiento fue actualizado satisfactoriamente.';
                require_once "alertas/alerta.php";
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
            }// Detén la ejecución después del redireccionamiento
           
            
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
        }
        
        $conn2 = null; // Cerrar la conexión en PDO
    }
}
else header('Location:../index.php');
?>