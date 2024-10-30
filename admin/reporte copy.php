<?php    
    ob_start();    
    include("conexion.php");
    $doc=(isset($_GET['doc']))?$_GET['doc']:"";
    //$doc=($_GET['doc']); 
    $sentenciaSQL=$conexion->prepare("SELECT * FROM ppna WHERE DOC=:doc and ACTIVO_MOSTRAR = 1 limit 1");
    $sentenciaSQL->bindParam(':doc',$doc);
    $sentenciaSQL->execute();
    $registro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); //MÉTODO QUE PERMITE CARGAR UNO A UNO LOS CAMPOS DE UN REGISTRO SEGÚN LA CONSULTA HECHA   
    date_default_timezone_set("America/Bogota"); 
    $leyendaHead="Fecha de consulta:"." ".date('d-m-Y h:i:sa');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/favicon.ico">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificación PPNA</title>
    <!-- Bootstrap core CSS -->    
    <link href="../config/css/bootstrap.min.css" rel="stylesheet"/>
        
</head>
<body>
    <div class="container">
        <div class="row"></div>
            <div class="header">
                <center><img src="http://127.0.0.1:8011/consultasweb/img/logoAlcaldia.png"alt="" width="250"></center>
                
                <br><br>
                <p align ="center"><b>Consulta PPNA</b></p> 
                <code ><b>Fecha actualizada de la base de datos: <?php echo $registro['FECREPORTE'];?></b></code><br><br>
                <p class="text-align-justify"><small><?php echo $leyendaHead;?></small> </p>

                <p><small>Se deberá consultar  su estado actual en el SGSSS para evitar multiples afiliaciones o duplicidad de la información en el link: 
                    https://www.adres.gov.co/consulte-su-eps</small></p> 
                
            <div class="card-body">
                <table class="table-lg striped">
                    
                        <tr><th>Tipo doc:</th>              <td> <?php echo $registro['TD'];?></td></tr> 
                        <tr><th>Doc:</th>                   <td> <?php echo $registro['DOC'];?></td></tr>   
                        <tr><th>Nombres y Apellidos:</th>   <td> <?php echo $registro['NOM1']." ".$registro['NOM2']
                                                            ." ".$registro['APE1']." ".$registro['APE2'];?></td></tr>
                        <tr><th>Fecha de nacimiento:</th>   <td><?php echo $registro['FECHANACE'];?></td></tr>
                        <tr><th>Parentesco:</th>            <td><?php echo $registro['PARENTESCO'];?></td></tr>                        
                        <tr><th>Ficha:</th>                 <td><?php echo $registro['FICHA'];?></td></tr>
                        <tr><th>Mensaje:</th>               <td><?php echo $registro['MENSAJE'];?></td></tr>
                        <tr><th>Discapacidad:</th>          <td><?php echo $registro['DISCAPACIDAD'];?></td></tr>
                        <tr><th>Nivel:</th>       <td><?php echo $registro['NIVEL SISBEN'];?></td></tr>
                        <tr><th>Grupo Poblacional:</th>           <td><?php echo $registro['GP'];?></td></tr>
                        <tr><th>Grp Poblacional SISBEN:</th>    <td><?php echo $registro['GP_SISBEN'];?></td></tr>
                            
                </table>
            </div>
            <div class="footer" text align ="center">
                <br><br><br><br><br><br><br><br>
                <p class="mb-1">&copy; 2023</p>                
                <p><small> Secretaria de Salud | Alcaldía de Pasto </small> </p>
                <p></p>               
            </div>
        </div>    
    </div>
</body>

</html>
<?php 
    $html= ob_get_clean();
    //echo $html;
    require_once 'config/libreria/dompdf/autoload.inc.php';
    use Dompdf\Dompdf;
    $dompdf= new Dompdf();

    $options=$dompdf->getOptions();
    $options->set(array('isRemoteEnabled'=>true));
    $dompdf->setOptions($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter');
    //$dompdf->setPaper('Legal','landscape'); //para imprimir horizontal
    $dompdf->render();
    $dompdf->stream('archivo_.pdf',array('Attachment'=>false));
    
?>