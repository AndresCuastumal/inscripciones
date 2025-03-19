<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    header('Content-Type: application/json');    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =            $_SESSION['id_usuario'];
        $id_establecimiento =   $_POST['id'];
        $id_clase           =   $_POST['clase_editEstablecimiento'];
        $id_sujeto          =   $_POST['sujeto_editEstablecimiento'];
        $id_propietario =       $_POST['id_propietario'];
        $id_barrio_vereda =     $_POST['id_barrio_vereda'];
        $razon_social =         $_POST['razon_social'];
        $nom_comercial =        $_POST['nom_comercial'];
        $sucursal =             $_POST['sucursal'];
        $fecha_actualizacion =  date('Y-m-d H:i:s');
        $dir_establecimiento =  $_POST['dir_establecimiento'];
        $correo_establecimiento=$_POST['correo_establecimiento'];
        $tel_establecimiento =  $_POST['tel_establecimiento'];
        $estado =               (isset($_POST['estado']))?$_POST['estado']:"0";
        try {
            $sql_actualizar = $conn2->prepare("UPDATE establecimiento SET 
                id_propietario=:id_propietario, id_barrio_vereda=:id_barrio_vereda, id_clase=:id_clase, razon_social=:razon_social, nom_comercial=:nom_comercial,
                sucursal=:sucursal, fecha_actualizacion=:fecha_actualizacion, estado=:estado, dir_establecimiento=:dir_establecimiento, 
                correo_establecimiento=:correo_establecimiento, tel_establecimiento=:tel_establecimiento, id_usr_update=:id_usr_update, id_sujeto=:id_sujeto
                WHERE id=:id_establecimiento");
            
            $sql_actualizar->bindValue(':id_establecimiento', $id_establecimiento);
            $sql_actualizar->bindValue(':id_propietario', $id_propietario);
            $sql_actualizar->bindValue(':id_barrio_vereda', $id_barrio_vereda);
            $sql_actualizar->bindValue(':id_clase', $id_clase);
            $sql_actualizar->bindValue(':razon_social', $razon_social);
            $sql_actualizar->bindValue(':nom_comercial', $nom_comercial);
            $sql_actualizar->bindValue(':sucursal', $sucursal);
            $sql_actualizar->bindValue(':fecha_actualizacion', $fecha_actualizacion);
            $sql_actualizar->bindValue(':dir_establecimiento', $dir_establecimiento);
            $sql_actualizar->bindValue(':estado', $estado);
            $sql_actualizar->bindValue(':correo_establecimiento', $correo_establecimiento);
            $sql_actualizar->bindValue(':tel_establecimiento', $tel_establecimiento);
            $sql_actualizar->bindValue(':id_usr_update', $idUsuario);
            $sql_actualizar->bindValue(':id_sujeto', $id_sujeto);

            if ($sql_actualizar->execute()) {                
                header('Content-Type: text/html');
                echo "<script>alert('Se realizó la actualización de datos del establecimieto exitosamente'); window.location.href='../index_1.php';</script>";
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