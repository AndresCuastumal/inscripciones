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
                    
                        <tr><th>Tipo doc:</th>              <td> <?php echo $regFuid['id'];?></td></tr> 
                        <tr><th>Doc:</th>                   <td> <?php echo $regFuid['nom_comercial'];?></td></tr>                     
                            
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