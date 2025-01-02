<?php
session_start();
ob_start();

if ($_SESSION) {
    require '../../config/conexion.php';
    header('Content-Type: application/json');
    $fecha_actual = date('Y-m-d H:i:s');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =        $_SESSION['id_usuario'];
        $id =               $_POST['id'];
        $nit =              $_POST['nit'] . " - " . $_POST['dv'];
        $nom_comercial =    $_POST['nom_comercial'];
        $dir_establecimiento = $_POST['dir_establecimiento'];
        $tel_establecimiento = $_POST['tel_establecimiento'];
        $nom_barrio =       $_POST['nom_barrio'];
        $nom_comuna =       $_POST['nom_comuna'];
        $id_comuna =        $_POST['id_comuna'];
        $nom_propietario =  $_POST['nom_propietario'];
        $doc_propietario =  $_POST['doc_propietario'];
        $nom_solicitante =  $_POST['nom_solicitante'];
        $nom_clase =        $_POST['nom_clase'];
        $id_sujeto =        $_POST['id_sujeto'];
        $tipo_solicitud =   $_POST['tipo_solicitud'];
        $observacion =      $_POST['observacion'];

        // Verificar qué técnico se asigna para la solicitud
        $sql_consultarTecnico = $conn->prepare("SELECT c.id_usuario 
                                                FROM bdfuid.comunaxusuario AS c
                                                JOIN bdfuid.sujetoxusuario AS s ON c.id_usuario = s.id_usuario
                                                WHERE c.id_comuna = :id_comuna AND s.id_sujeto = :id_sujeto;");
        $sql_consultarTecnico->bindParam(':id_comuna', $id_comuna);
        $sql_consultarTecnico->bindParam(':id_sujeto', $id_sujeto);
        $sql_consultarTecnico->execute();
        $registroTecnico = $sql_consultarTecnico->fetch(PDO::FETCH_ASSOC);
        if ($registroTecnico && isset($registroTecnico['id_usuario'])) {
            // Si el registro existe y tiene 'id_usuario', verifica si es vacío y asigna "0" si es necesario
            if (!$registroTecnico['id_usuario']) {
                $registroTecnico['id_usuario'] = "0";
            }
        } else {
            // Si no hay registro, asigna un valor por defecto
            $registroTecnico = ['id_usuario' => "0"];
        }
        
        $sql_consultar = $conn2->prepare("SELECT COUNT(*) AS total_registros
                                        FROM solicitud
                                        WHERE YEAR(fecha_solicitud) = YEAR(CURRENT_DATE);");
        $sql_consultar->execute();
        $registro = $sql_consultar->fetch(PDO::FETCH_ASSOC);        
        $no_solicitud = date('Y').$registro['total_registros']+1;
        
        // Verificar si ya existe una solicitud para el establecimiento
        //$sql_consultarEstablecimiento = $conn2->prepare("SELECT id, fecha_solicitud FROM solicitud WHERE id_establecimiento = :id_establecimiento");
        //$sql_consultarEstablecimiento->bindParam(':id_establecimiento', $id);
        //$sql_consultarEstablecimiento->execute();
        //$registro = $sql_consultarEstablecimiento->fetch(PDO::FETCH_ASSOC);

        //if ($registro) {
            // Si ya existe, enviar respuesta JSON
            //echo json_encode(['exists' => true, 'fecha_solicitud' => $registro['fecha_solicitud']]);
            
        //} else {
            try {
                // Insertar nueva solicitud
                $sql_guardar = $conn2->prepare("INSERT INTO solicitud(id_establecimiento, id_usuario_registra, id_usuario_actualiza, no_solicitud, fecha_solicitud, tipo_solicitud, nom_solicitante, observacion, visitado,  id_usr_tecnico) 
                VALUES (:id_establecimiento, :id_usuario, :id_usuario, :no_solicitud, :fecha_solicitud, :tipo_solicitud, :nom_solicitante, :observacion,'No visitado', :id_usr_tecnico)");

                $sql_guardar->bindValue(':id_establecimiento', $id);
                $sql_guardar->bindValue(':id_usuario', $idUsuario);
                $sql_guardar->bindValue(':no_solicitud', $no_solicitud);
                $sql_guardar->bindValue(':fecha_solicitud', $fecha_actual);
                $sql_guardar->bindValue(':tipo_solicitud', $tipo_solicitud);
                $sql_guardar->bindValue(':nom_solicitante', $nom_solicitante);
                $sql_guardar->bindValue(':observacion', $observacion);
                $sql_guardar->bindValue(':id_usr_tecnico', $registroTecnico['id_usuario'], PDO::PARAM_INT);

                $sql_guardar->execute();

                $fecha_solicitud = $fecha_actual;
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
                exit();
            }
        //}
        $conn2 = null; // Cerrar la conexión
    }
} else {
    header('Location:../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CERTIFICADO SOLICITUD</title>
    <!-- Bootstrap core CSS -->    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>  
    
    <table class="table table-sm mt-1 table-bordered" style="font-size: 10px; line-height: 0.5; width: 100%; table-layout: fixed;">
        <tr>
            <td rowspan="3" style="width: 15%; white-space: nowrap; line-height: 1; text-align: center; vertical-align: middle;">
                <img src="http://localhost:8011/inscripciones/admin/img/logoAlcaldia.jpg" width="80px" height="80px">
            </td>
            <td colspan="4" class="text-center" style="white-space: nowrap; line-height: 2;">PROCESO SALUD PÚBLICA</td>
        </tr>
        <tr>
            <td colspan="4" class="text-center" style="white-space: nowrap; line-height: 2;">SOLICITUD DE SERVICIOS SALUD AMBIENTAL</td>
        </tr>
        <tr>
            <td style="white-space: nowrap; line-height: 1.2;">VIGENCIA<br>DD-MM-AAA</td>
            <td style="white-space: nowrap; line-height: 1.2;">VERSIÓN<br>01</td>
            <td style="white-space: nowrap; line-height: 1.2;">CODIGO<br>SP-F-XXX</td>
            <td style="white-space: nowrap; line-height: 1.2;">PÁGINA<br>1 de 1</td>
        </tr>
    </table>
    <table class="table table-sm mt-1 table-bordered" style="font-size: 12px; line-height: 1; width: 100%; table-layout: fixed;">
        <tr>
            <td colspan="2">Número de solicitud: <?= $no_solicitud;?></td><td colspan="2">Fecha de solicitud: <?= $fecha_solicitud;?></td>
        </tr>
        <tr>
            <td colspan="2">Sujeto: <?= $nom_clase;?></td><td colspan="2">Tipo solicitud: <?= $tipo_solicitud;?></td>
        </tr>
        <tr>
            <td colspan="2">Representante legal: <?= $nom_propietario;?></td><td colspan="2">No. de identificación: <?= $doc_propietario;?></td>
        </tr>
        <tr>
            <td colspan="2">Nombre del Establecimiento: <?= $nom_comercial;?></td><td colspan="2">NIT: <?= $nit;?></td>
        </tr>
        <tr>
            <td colspan="2">Dirección: <?= $dir_establecimiento;?></td><td colspan="2">Barrio: <?= $nom_barrio;?> </td>
        </tr>
        <tr>
            <td colspan="2">Comuna/corregimiento: <?= $nom_comuna;?></td><td colspan="2">No. telefónico: <?= $tel_establecimiento;?></td>
        </tr>
        <tr>
            <td colspan="4">Solicitado por: <?= $nom_solicitante;?></td>
        </tr>
        <tr>
            <td colspan="2" style="line-height: 2;">firma del solicitante:____________________________________</td>
            <td colspan="2"  style="line-height: 2;">No C.C. Solicitante: ____________________________________</td>
        </tr>
        
        <tr>
            <td colspan="4"><br>Observación:<?= $observacion;?></td>
        </tr>
        <tr>
        <td colspan = "3" style="font-size: 7px; border: none;">                
                <a href="http://localhost:8011/inscripciones/admin/index.php"> >> </a>
            </td>
            <td style="text-align: right; font-size: 6px; border: none;">
                <b>Fecha de impresión: </b><?php echo $fecha_actual; ?>
                
            </td>            
        </tr>
    </table> 
    
</head>
<body>
</body>
</html>
<?php 
    $html= ob_get_clean();
    //echo $html;
    require_once '../../config/dompdf/autoload.inc.php';
    use Dompdf\Dompdf;
    $dompdf= new Dompdf();
    $dompdf->setBasePath(realpath(dirname(__FILE__)));
    $options=$dompdf->getOptions();
    $options->set(array('isRemoteEnabled'=>true));
    $dompdf->setOptions($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter');
    $options = $dompdf->getOptions();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('margin-top', 0);
    $options->set('margin-bottom', 0);
    $options->set('margin-left', 0);
    $options->set('margin-right', 0);
    $dompdf->setOptions($options);
    //$dompdf->setPaper('Legal','landscape'); //para imprimir horizontal
    $dompdf->render();
    $dompdf->stream('archivo_.pdf',array('Attachment'=>false));
    
?>



