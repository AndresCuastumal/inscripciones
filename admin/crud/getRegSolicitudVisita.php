<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    $bandera=(isset($_POST['bandera']))?$_POST['bandera']:"";
    if (!$conn) {
        die(json_encode(['error' => 'Fallo la conexión a la base de datos.']));
    }

    if (isset($_POST['id'])) {
        try {
            $id = $_POST['id'];

            //+++ ESTE SQL CONSULTA EN BD FUID -> TABLAS: BARRIO, COMUNA, CLASE Y EN BD SERVICIOSIVC -> TABLAS: ESTABLECIMIENTO Y PROPIETARIO +++
                
            $sql = "SELECT e.id, e.nom_comercial, e.nit, e.digito_verificacion, e.dir_establecimiento, e.tel_establecimiento, 
                        b.nom_barrio, 
                        c.nom_comuna, c.id as id_comuna, 
                        p.nom_propietario, p.ape_propietario, p.doc,
                        cl.nom_clase, cl.id_sujeto,
                        s.observacion
                    FROM serviciosivc.establecimiento e
                    join bdfuid.barrio b on e.id_barrio_vereda = b.id 
                    join serviciosivc.propietario p on e.id_propietario = p.id
                    join bdfuid.comuna c  on b.id_comuna = c.id
                    join bdfuid.clase cl on e.id_clase = cl.id
                    left join serviciosivc.solicitud s on e.id=s.id_establecimiento 
                    WHERE e.id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); //+++ EL id ES EL ID DE TABLA ESTABLECIMIENTO +++
            $stmt->execute();
            $regSolicitudVisita = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode($regSolicitudVisita, JSON_UNESCAPED_UNICODE);
           
        }   catch (Exception $e) {
            echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
        
    } else {
        echo json_encode(['error' => 'No se proporcionó el ID.']);
    }
} else header('Location:../index.php');
?>
