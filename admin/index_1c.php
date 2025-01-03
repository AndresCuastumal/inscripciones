<?php
include("cabecera.php");
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
$option=(isset($_POST['opciones']))?$_POST['opciones']:"";
$registro=0;
switch($accion){
  
  case'Consultar':
    $bandera=1;
    $busqueda=(isset($_POST['busqueda']))?$_POST['busqueda']:"";
    $busqueda='%'.$busqueda.'%';    
    $sentenciaSQL=$conn2->prepare("SELECT e.id, e.nom_comercial, e.nit, e.fecha_registro,                                    
                                    p.nom_propietario, p.ape_propietario,  p.doc, p.id as id_propietario 
                                    FROM establecimiento e                                     
                                    JOIN propietario p ON e.id_propietario = p.id                                      
                                    WHERE e.nit LIKE :busqueda
                                    OR p.doc LIKE :busqueda
                                    OR e.nom_comercial LIKE :busqueda
                                    OR CONCAT(p.nom_propietario,' ',p.ape_propietario) LIKE :busqueda ");    
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
        <h3>Imprimir formato de inscripción de establecimiento</h3>
        <p class="text-justify" >Si existe el establecimiento registrado en la base de datos, usted puede imprimir el formato de Inscripción de Establecimiento.  
        </p>      
      </div>    
    </div>
    <div>
    <br></br>  
      <form method ="POST">
        <div class="mb-3" id="input-container">
        <label for="doc" id="dynamicLabel" >Digite el nombre del establecimiento, NIT, nombre del propietario o número de identificación para listar los registros que
        coincidan con el parámetro consultado.  Selecciones el registro que contiene los datos requeridos y seleccione el ícono de impresión.</label>
          <input type="text" class="form-control" name = "busqueda" id="busqueda" placeholder="" value="" required>
        </div>        
          <div class="mb-3">
              <input type="submit" name="accion" value="Consultar" class="btn btn-sm btn-success btn-block"/>
          </div>
      </form> 
    </div>
  </div>
  <?php
    if(isset($bandera)){if($sentenciaSQL->rowCount() > 0){      
  ?>
  <div class="container mt-5 card text-center">    
        <table class="table">
          <thead>
            <tr class="text-secondary">
            <th colspan="5" class="text-secondary">DATOS GENERALES</th>
            <th colspan="4" class="border-end text-secondary">ACCIONES PARA ESTABLECIMIENTO</th>
            </tr>
            <tr>
              <th class="text-primary">id</th><th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de solicitud</th>
              <th class="text-primary">Nombre Propietario</th> <th class="text-primary">Doc Propietario</th>              
              <th class="border-end text-primary">Imprimir inscripción de establecimiento</th>              
            </tr>
          </thead>
          <tbody>
            <?php              
                while($registro=$sentenciaSQL->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
            <td><?= $registro['id'];?><td><?= $registro['nom_comercial'];?></td><td><?= $registro['nit']; ?></td><td><?= date('d-m-Y', strtotime($registro['fecha_registro'])); ?></td>
              <td><?= $registro['nom_propietario']." ".$registro['ape_propietario'];?></td><td><?= $registro['doc']; ?></td>
              <td>                
              <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#imprimirInscripcion" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-print"></i></a>                
              </td>             
            </tr>            
            <?php } ?>            
          </tbody>
        </table>      
      <?php }}   ?>    
  </div>
  <?php include "crud/modals/modalImprimirInscripcion.php";  ?>

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegSolicitudVisita.php PARA IMPRIMIR INSCRIPCION -->

<script>
let imprimirInscripcion = document.getElementById('imprimirInscripcion');
imprimirInscripcion.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let inputId = imprimirInscripcion.querySelector('.modal-body #id');
        let inputNomComercial = imprimirInscripcion.querySelector('.modal-body #nom_comercial')
        let inputNit = imprimirInscripcion.querySelector('.modal-body #nit')
        let inputDv = imprimirInscripcion.querySelector('.modal-body #dv')
        let inputDirEstablecimiento = imprimirInscripcion.querySelector('.modal-body #dir_establecimiento')
        let inputNomBarrio = imprimirInscripcion.querySelector('.modal-body #nom_barrio')
        let inputNomComuna = imprimirInscripcion.querySelector('.modal-body #nom_comuna')
        let inputNomPropietario = imprimirInscripcion.querySelector('.modal-body #nom_propietario_inscr')
        let inputDocPropietario = imprimirInscripcion.querySelector('.modal-body #doc_propietario_inscr')
        let inputNomSolicitante = imprimirInscripcion.querySelector('.modal-body #nom_solicitante')
        let inputNomClase = imprimirInscripcion.querySelector('.modal-body #nom_clase')
        //let inputObservacion = imprimirInscripcion.querySelector('.modal-body #observacion')
                
        let url = "crud/getRegSolicitudVisita.php";
        let formData = new FormData();
        formData.append('id', id  );

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            inputId.value = data.id;
            inputNomComercial.value = data.nom_comercial;
            inputNit.value = data.nit;
            inputDv.value = data.digito_verificacion;
            inputDirEstablecimiento.value = data.dir_establecimiento;
            inputNomBarrio.value = data.nom_barrio;
            inputNomComuna.value = data.nom_comuna;
            inputNomPropietario.value = data.nom_propietario+" "+data.ape_propietario;
            inputDocPropietario.value = data.doc;
            inputNomSolicitante.value = data.nom_propietario+" "+data.ape_propietario;
            inputNomClase.value = data.nom_clase; 
            //inputObservacion.value = data.observacion; 

        }).catch(err => {
            console.error('Error en la solicitud fetch:', err);
        });
    });
</script>


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