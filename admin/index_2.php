<?php
include("cabecera.php");
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
$option=(isset($_POST['opciones']))?$_POST['opciones']:"";
$registro=0;
    $bandera=1;
    $anio_actual= date("Y");
    
    $sentenciaSQL=$conn->prepare("SELECT e.id, e.nom_comercial, e.nit as NIT, e.fecha_registro, e.dir_establecimiento,
                                    c.nom_comuna,
                                    sj.nom_sujeto,
                                    p.nom_propietario, p.ape_propietario,  p.doc, p.id as id_propietario,
                                    s.fecha_solicitud, s.visitado, s.id_usr_tecnico
                                      FROM serviciosivc.establecimiento e 
                                      INNER JOIN serviciosivc.propietario p ON e.id_propietario = p.id
                                      INNER JOIN serviciosivc.solicitud s ON s.id_establecimiento = e.id
                                      INNER JOIN bdfuid.barrio b ON b.id = e.id_barrio_vereda
                                      inner JOIN bdfuid.comuna c ON c.id = b.id_comuna
                                      INNER JOIN bdfuid.clase cl ON cl.id = e.id_clase
                                      INNER JOIN bdfuid.sujeto sj ON sj.id = cl.id_sujeto
                                      INNER JOIN bdfuid.usuario u ON u.id = s.id_usr_tecnico                                                                            
                                      WHERE s.id_usr_tecnico = :id_usuario and YEAR(fecha_solicitud)=:anio_actual
                                  ");    
    $sentenciaSQL->bindParam(':id_usuario',$idUsuario);
    $sentenciaSQL->bindParam(':anio_actual',$anio_actual);
    $sentenciaSQL->execute();

    //$registro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); //MÉTODO QUE PERMITE CARGAR UNO A UNO LOS CAMPOS DE UN REGISTRO SEGÚN LA CONSULTA HECHA
    
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
        <h3>Control de visitas</h3>
        <p class="text-justify" >El sistema muestra un listado de establecimientos asignados a usted según sujeto y comuna que se encuentra registrado en el aplicativo FUID que han solicitado visita en el presente año.  
          Usted debe verificar si ya ha realizado la visita correspondiente e ingresar la fecha de visita, y, si desea; alguna observación.</p>      
      </div>    
    </div>    
  </div>
  <div class="container mt-5 card text-center"> 
    <?php if(isset($bandera)){if($sentenciaSQL->rowCount() > 0){?>
        <table class="table">
          <thead>
            <tr class="text-secondary">
            <th colspan="8" class="text-secondary">DATOS GENERALES</th>
            <th colspan="1" class="border-end text-secondary">ACCIONES</th>
            </tr>
            <tr>
              <th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de solicitud</th>
              <th class="text-primary">Nombre Propietario</th> <th class="text-primary">Doc Propietario</th><th class="text-primary">Comuna</th>
              <th class="text-primary">Sujeto</th><th class="text-primary">Estado de visita</th> <th class="border-end text-primary">Actualizar estado visita</th>
            </tr>
          </thead>
          <tbody>
            <?php
              while($registro=$sentenciaSQL->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
              <td><?= $registro['nom_comercial'];?></td><td><?= $registro['NIT']; ?></td><td><?= date('d-m-Y', strtotime($registro['fecha_solicitud'])); ?>
              <td><?= $registro['nom_propietario']." ".$registro['ape_propietario'];?></td><td><?= $registro['doc']; ?></td><td><?= $registro['nom_comuna']; ?></td>
              <td><?= $registro['nom_sujeto']; ?></td><td><?= $registro['visitado']; ?></td>
              
              <td class="border-end">                
                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#imprimirInscripcion" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-pen-to-square"></i></a>                
              </td>
            </tr>            
            <?php } ?>            
          </tbody>
        </table>
      <?php } else{ ?>
        <table>
          <tr>
            <td>
              <p>No existe ninguna solicitud asignada a usted.  Comuníquese con su supervisor</p>
            </td>            
          </tr>
        </table>
      <?php }}   ?>    
  </div>
  <?php include "crud/modals/modalImprimirInscripcion.php";  ?>
   
</body>
</html>

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
        let inputObservacion = imprimirInscripcion.querySelector('.modal-body #observacion')
                
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
            inputObservacion.value = data.observacion; 

        }).catch(err => {
            console.error('Error en la solicitud fetch:', err);
        });
    });
</script>

<?php
include("pie.php");
?>