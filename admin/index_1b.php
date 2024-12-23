<?php
include("cabecera.php");
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
$option=(isset($_POST['opciones']))?$_POST['opciones']:"";
$registro=0;
$fecha_busqueda = new DateTime();
$fecha_busqueda->modify('-10 days');
$fecha_formateada = $fecha_busqueda->format('Y-m-d'); 

$sentenciaSQL2=$conn2->prepare("SELECT e.id, e.nom_comercial, e.nit, 
                              s.fecha_solicitud,s.id as id_solicitud,
                              p.nom_propietario, p.ape_propietario,  p.doc, p.id as id_propietario 
                              FROM solicitud s 
                              JOIN establecimiento e ON e.id = s.id_establecimiento
                              JOIN propietario p ON e.id_propietario = p.id                                      
                              WHERE s.fecha_solicitud > :fecha_busqueda
                              ORDER BY s.id DESC");    
$sentenciaSQL2->bindParam(':fecha_busqueda',$fecha_formateada);
$sentenciaSQL2->execute();

switch($accion){
  
  case'Consultar':
    $bandera=1;
    $busqueda=(isset($_POST['busqueda']))?$_POST['busqueda']:"";
    $busqueda='%'.$busqueda.'%';    
    $sentenciaSQL=$conn2->prepare("SELECT e.id, e.nom_comercial, e.nit, 
                                    s.fecha_solicitud,s.id as id_solicitud,
                                    p.nom_propietario, p.ape_propietario,  p.doc, p.id as id_propietario 
                                    FROM solicitud s 
                                    JOIN establecimiento e ON e.id = s.id_establecimiento
                                    JOIN propietario p ON e.id_propietario = p.id                                      
                                    WHERE e.nit LIKE :busqueda
                                    OR p.doc LIKE :busqueda
                                    OR e.nom_comercial LIKE :busqueda
                                    OR CONCAT(p.nom_propietario,' ',p.ape_propietario) LIKE :busqueda 
                                    ORDER BY s.id DESC");    
    $sentenciaSQL->bindParam(':busqueda',$busqueda);
    $sentenciaSQL->execute();

    //$registro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); //MÉTODO QUE PERMITE CARGAR UNO A UNO LOS CAMPOS DE UN REGISTRO SEGÚN LA CONSULTA HECHA    
    break;
}
?>
<body>
  <div class="container-fluid p-5 bg-primary text-white text-center">
    <div class="row">
      <div class="col-sm-6 text-right" >
        <h2>Sistema de Registro de Establecimientos</h2>
        <p>Secretaría de Salud Municipal - Salud Ambiental</p>
      </div>
      <div class="col-sm-6">
        <img src="img/inscripciones_largoHorizontal.png" width= "450px">
      </div>
    </div>
  </div>
    
  <div class="container mt-5">
    <div class="row">
      <div class="bg-light text-info ">
        <h3>Listar solicitudes de concepto sanitario</h3>
        <p class="text-justify" >En este módulo se listan las solicitudes de la última semana registradas en el sistema.  
        </p>      
      </div>    
    </div>
    <div>
    <br></br>  
      <form method ="POST">
        <div class="mb-3" id="input-container">
        <label for="doc" id="dynamicLabel" >Si necesita consultar una solicitud en específico, escriba el nombre del establecimiento o NIT.</label>
          <input type="text" class="form-control" name = "busqueda" id="busqueda" placeholder="" value="" required>
        </div>        
          <div class="mb-3">
              <input type="submit" name="accion" value="Consultar" class="btn btn-sm btn-success btn-block"/>
          </div>
      </form> 
    </div>
  </div>
  <div class="container mt-5 card text-center"> 
    <?php ?>
        <table class="table">
          <thead>
            <tr class="text-secondary">
            <th colspan="6" class="text-secondary">DATOS GENERALES</th>
            <th class="border-end text-secondary">ACCIONES PARA ESTABLECIMIENTO</th>
            </tr>
            <tr>
              <th class="text-primary">ID</th><th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de solicitud</th>
              <th class="text-primary">Nombre Propietario</th> <th class="text-primary">Doc Propietario</th>              
              <th class="border-end text-primary">Imprimir solicitud de visita</th>              
            </tr>
          </thead>
          <tbody>
            <?php
              if(isset($bandera)){
                while($registro=$sentenciaSQL->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
              <td><?= $registro['id_solicitud'];?></td><td><?= $registro['nom_comercial'];?></td><td><?= $registro['nit']; ?></td><td><?= date('d-m-Y', strtotime($registro['fecha_solicitud'])); ?>
              <td><?= $registro['nom_propietario']." ".$registro['ape_propietario'];?></td><td><?= $registro['doc']; ?></td>
              <td class="border-end">                
                <a href="../admin/crud/imprimirSolicitudVisita.php?id=<?= $registro['id_solicitud']; ?>" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-print"></i></a>                
              </td>             
            </tr>            
            <?php } ?>            
          </tbody>
        </table>
      <?php } 
      else{ ?>        
          <tr>
            <td>
            <?php
              while($registro2=$sentenciaSQL2->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
              <td><?= $registro2['id_solicitud'];?></td><td><?= $registro2['nom_comercial'];?></td><td><?= $registro2['nit']; ?></td><td><?= date('d-m-Y', strtotime($registro2['fecha_solicitud'])); ?>
              <td><?= $registro2['nom_propietario']." ".$registro2['ape_propietario'];?></td><td><?= $registro2['doc']; ?></td>
              <td class = "border-end">                
                <a href="../admin/crud/imprimirSolicitudVisita.php?id=<?= $registro2['id_solicitud']; ?>" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-print"></i></a>                
              </td>             
            </tr>            
            <?php } ?>            
          </tbody>
        </table>            
      <?php } ?>    
  </div>  

<!-- SCRIPT PARA MOSTRAR UN MENSAJE INFORMATIVO EN EL ÍCONO "i" -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>


<?php
include("pie.php");
?>