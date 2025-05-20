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
        if(isset($_POST['id_sujeto'])) $id_sujeto = $_POST['id_sujeto'];//Este condicional se da porque viene de dos modales diferentes
        else $id_sujeto = $_POST['id_sujeto2'];
        if(isset($_POST['id_clase'])) $id_clase = $_POST['id_clase'];//Este condicional se da porque viene de dos modales diferentes
        else $id_clase = $_POST['id_clase2'];
        
        $sql_consultar_comuna = $conn->prepare("select id_comuna from barrio where id = :id_barrio");
        $sql_consultar_comuna->bindValue(':id_barrio', $id_barrio_vereda);
        $sql_consultar_comuna->execute();
        $id_comuna = $sql_consultar_comuna->fetch(PDO::FETCH_ASSOC);

        $sucursal =                 $_POST['sucursal'];
        $dir_establecimiento =      $_POST['dir_establecimiento'];
        $correo_establecimiento =   $_POST['correo_establecimiento'];
        $tel_establecimiento =      $_POST['tel_establecimiento'];    
        
        $fecha_registro =           date('Y-m-d H:i:s');

        $sql_consultar = $conn2->prepare("SELECT COUNT(*) AS total_registros
                                        FROM establecimiento
                                        WHERE YEAR(fecha_registro) = YEAR(CURRENT_DATE);");
        $sql_consultar->execute();
        $registro = $sql_consultar->fetch(PDO::FETCH_ASSOC);        
        $no_inscripcion = "52001".date('Y').$registro['total_registros']+1;
        
        try {
            // Insertar en la base de datos con PDO
            $sql_guardar = $conn2->prepare("INSERT INTO establecimiento (id_propietario, id_barrio_vereda, id_clase, no_inscripcion, nit, digito_verificacion, razon_social, nom_comercial, 
                sucursal, fecha_registro, estado, dir_establecimiento, correo_establecimiento, tel_establecimiento, id_usr_registro) 
            VALUES (:id_propietario, :id_barrio_vereda, :id_clase, :no_inscripcion,:nit, :dv, :razon_social, :nom_comercial, 
                :sucursal, :fecha_registro, '1',:dir_establecimiento, :correo_establecimiento, :tel_establecimiento, :id_usr_registro)");

            $sql_guardar->bindValue(':id_propietario', $id_propietario);
            $sql_guardar->bindValue(':id_barrio_vereda', $id_barrio_vereda);
            $sql_guardar->bindValue(':id_clase', $id_clase);
            $sql_guardar->bindValue(':no_inscripcion', $no_inscripcion);
            $sql_guardar->bindValue(':nom_comercial', $nom_comercial);
            $sql_guardar->bindValue(':razon_social', $razon_social);
            $sql_guardar->bindValue(':nit', $nit);
            $sql_guardar->bindValue(':dv', $dv);
            $sql_guardar->bindValue(':sucursal', $sucursal); // Añadir :sucursal en la consulta SQL
            $sql_guardar->bindValue(':fecha_registro', $fecha_registro);
            $sql_guardar->bindValue(':dir_establecimiento', $dir_establecimiento);
            $sql_guardar->bindValue(':correo_establecimiento', $correo_establecimiento);
            $sql_guardar->bindValue(':tel_establecimiento', $tel_establecimiento);
            
            $sql_guardar->bindValue(':id_usr_registro', $idUsuario);
            //$sql_guardar->bindValue(':id_comuna', $id_comuna['id_comuna']);
            //$sql_guardar->bindValue(':id_sujeto', $id_sujeto);
            

            if ($sql_guardar->execute()) {
                //$last_id = $conn2->lastInsertId(); // Obtiene el ID del último registro insertado
                echo "<!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script src='../../config/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado',
                            text: 'El establecimiento fue registrado exitosamente.'
                        }).then(() => {
                            window.location.href = '../index.php';
                        });
                    </script>
                </body>
                </html>";
                exit();
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
