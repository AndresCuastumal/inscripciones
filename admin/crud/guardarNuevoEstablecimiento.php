<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {        
        $idUsuario =        $_SESSION['id_usuario'];        
        $nom_comercial =    $_POST['nom_comercial'];
        $razon_social =     $_POST['razon_social'];
        $nit =              $_POST['nit'];
        $dv =              $_POST['dv'];
        $id_barrio_vereda = $_POST['id_barrio_vereda']; 
        //$id_propietario =   $_POST['idP'];       
        if(isset($_POST['idP'])) $id_propietario = $_POST['idP'];//Este condicional se da porque viene de dos modales diferentes
        else $id_propietario = $_POST['id_propietario2'];
        
        if(isset($_POST['id_clase'])) $id_clase = $_POST['id_clase'];//Este condicional se da porque viene de dos modales diferentes
        else $id_clase = $_POST['id_clase2'];
        $sucursal =                 $_POST['sucursal'];
        $dir_establecimiento =      $_POST['dir_establecimiento'];
        $correo_establecimiento =   $_POST['correo_establecimiento'];
        $tel_establecimiento =      $_POST['tel_establecimiento'];    
        $nuevo =                    isset($_POST['nuevo']) ? 1 : 0;
        $fecha_registro =           date('Y-m-d H:i:s');
        echo $id_propietario; echo $idUsuario; echo $nom_comercial; echo $razon_social;
        try {
            // Insertar en la base de datos con PDO
            $sql_guardar = $conn2->prepare("INSERT INTO establecimiento (id_propietario, id_barrio_vereda, id_clase, nit, digito_verificacion, razon_social, nom_comercial, 
                sucursal, fecha_registro, dir_establecimiento, correo_establecimiento, tel_establecimiento, nuevo, id_usr_registro) 
            VALUES (:id_propietario, :id_barrio_vereda, :id_clase, :nit, :dv, :razon_social, :nom_comercial, 
                :sucursal, :fecha_registro, :dir_establecimiento, :correo_establecimiento, :tel_establecimiento, :nuevo, :id_usr_registro)");

            $sql_guardar->bindValue(':id_propietario', $id_propietario);
            $sql_guardar->bindValue(':id_barrio_vereda', $id_barrio_vereda);
            $sql_guardar->bindValue(':id_clase', $id_clase);
            $sql_guardar->bindValue(':nom_comercial', $nom_comercial);
            $sql_guardar->bindValue(':razon_social', $razon_social);
            $sql_guardar->bindValue(':nit', $nit);
            $sql_guardar->bindValue(':dv', $dv);
            $sql_guardar->bindValue(':sucursal', $sucursal); // Añadir :sucursal en la consulta SQL
            $sql_guardar->bindValue(':fecha_registro', $fecha_registro);
            $sql_guardar->bindValue(':dir_establecimiento', $dir_establecimiento);
            $sql_guardar->bindValue(':correo_establecimiento', $correo_establecimiento);
            $sql_guardar->bindValue(':tel_establecimiento', $tel_establecimiento);
            $sql_guardar->bindValue(':nuevo', $nuevo);
            $sql_guardar->bindValue(':id_usr_registro', $idUsuario);
            

            if ($sql_guardar->execute()) {
                $last_id = $conn2->lastInsertId(); // Obtiene el ID del último registro insertado
                echo json_encode(['success' => true, 'id' => $last_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
            }

        } catch (PDOException $e) {
            echo $id_propietario;
            echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
            echo $id_propietario;
        }

        header('Content-Type: application/json');
        $conn2 = null; // Cerrar la conexión en PDO
    }
} else header('Location:../index.php');
?>
