<?php
session_start();
if($_SESSION){
    require '../../config/conexion.php';
    header('Content-Type: application/json');
    $fecha_actual = date('Y-m-d H:i:s');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idUsuario =            $_SESSION['id_usuario'];
        $id =                   $_POST['id'];
        $nit =                  $_POST['nit']." - ".$_POST['dv'];
        $nom_comercial =        $_POST['nom_comercial'];
        $dir_establecimiento =  $_POST['dir_establecimiento'];
        $nom_barrio =           $_POST['nom_barrio'];
        $nom_comuna =           $_POST['nom_comuna'];
        $nom_propietario =      $_POST['nom_propietario_inscr'];
        $doc_propietario =      $_POST['doc_propietario_inscr'];
        $nom_solicitante =      $_POST['nom_solicitante'];
        $nom_clase =            $_POST['nom_clase'];
        $tipo_solicitud =       $_POST['tipo_solicitud'];
        $observacion =          $_POST['observacion'];

        // Consultar datos faltantes para imprimir inscripción

        $sql_consultarEstablecimiento= $conn2->prepare("select no_inscripcion, fecha_registro, tel_establecimiento 
                                                        from establecimiento 
                                                        where id = :id_establecimiento");
        $sql_consultarEstablecimiento->bindParam(':id_establecimiento', $id);
        $sql_consultarEstablecimiento->execute();
        $registro = $sql_consultarEstablecimiento->fetch(PDO::FETCH_ASSOC);
        $conn2 = null; // Cerrar la conexión en PDO
    }
}
else header('Location:../index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CERTIFICADO DE INSCRIPCIÓN DE ESTABLECIMIENTO</title>
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
            <td colspan="4" class="text-center" style="white-space: nowrap; line-height: 2;">INSCRIPCIÓN DE ESTABLECIMIENTOS - SALUD AMBIENTAL</td>
        </tr>
        <tr>
            <td style="white-space: nowrap; line-height: 1.2;">VIGENCIA<br>15 dic 2017</td>
            <td style="white-space: nowrap; line-height: 1.2;">VERSIÓN<br>01</td>
            <td style="white-space: nowrap; line-height: 1.2;">CODIGO<br>SP-F-022</td>
            <td style="white-space: nowrap; line-height: 1.2;">PÁGINA<br>1 de 1</td>
        </tr>
    </table>
    <table class="table table-sm mt-1 table-bordered" style="font-size: 12px; line-height: 1; width: 100%; table-layout: fixed;">
        <tr>
            <td colspan="2">Fecha de inscripción: <?= $registro['fecha_registro'];?></td><td colspan="2">Número de inscripción: <?= $registro['no_inscripcion'];?> </td>
        </tr>
        <tr>
            <td colspan="4">Razón social: <?= $nom_comercial;?> <?= $nit;?></td></td>
        </tr>
        <tr>
            <td colspan="2">Propietario: <?= $nom_propietario;?></td><td colspan="2">No. de identificación: <?= $doc_propietario;?></td>
        </tr>
        <tr>
            <td colspan="2">Dirección: <?= $dir_establecimiento;?></td><td colspan="2">Barrio: <?= $nom_barrio;?> </td>
        </tr>
        <tr>
            <td colspan="2">Comuna/corregimiento: <?= $nom_comuna;?></td><td colspan="2">No. telefónico: <?= $registro['tel_establecimiento'];?></td>
        </tr>
        <tr>
            <td colspan="4">Sujeto: <?= $nom_clase;?></td>
        </tr>
        <tr>
            <td colspan="4"><br>Observaciones:<?= $observacion;?></td>
        </tr>
        <tr>
            <td colspan="4">Solicitado por: <?= $nom_solicitante;?></td>
        </tr>
        <tr>
            
            <td colspan="2" style="line-height: 2;">firma del solicitante:____________________________________</td>
            <td colspan="2"  style="line-height: 2;">No C.C. Solicitante: ____________________________________</td>
        </tr>
        
        
        <tr>
        <td colspan = "3" style="font-size: 7px; border: none;">                
                <a href="http://localhost/inscripciones/admin/index.php"> >> </a>
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