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
    $sentenciaSQL=$conn2->prepare("SELECT e.id, e.nom_comercial, e.nit as NIT, e.fecha_registro,
                                    p.nom_propietario, p.ape_propietario,  p.doc, p.id as id_propietario 
                                      FROM establecimiento e 
                                      RIGHT JOIN propietario p ON e.id_propietario = p.id
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
        <h3>Administrar establecimiento o propietario</h3>
        <p class="text-justify" >El sistema consultará en su base de datos si el establecimiento y/o proietario que está solicitando una nueva inscripción ya están registrados. 
          Si ya se encuentra registrado, no se realizará una nueva inscripción, sólo se permite imprimir el formato, por el contrario; si no se encuentran registrados, 
          se solicitarán los datos del propietario y/o del establecimiento respectivamente. El sistema también presenta opciones de edición del establecimiento y de solicitud de visita</p>      
      </div>    
    </div>
    <div>
    <br></br>  
      <form method ="POST">
        <div class="mb-3" id="input-container">
        <label for="doc" id="dynamicLabel" >Ingrese el número de documento, el nit, el nombre del establecimiento o el nombre del propietario para consultar si se encuentra en nuestra base de datos</label>
          <input type="text" class="form-control" name = "busqueda" id="busqueda" placeholder="" value="" required>
        </div>        
          <div class="mb-3">
              <input type="submit" name="accion" value="Consultar" class="btn btn-sm btn-success btn-block"/>
          </div>
      </form> 
    </div>
  </div>
  <div class="container mt-5 card text-center"> 
    <?php if(isset($bandera)){if($sentenciaSQL->rowCount() > 0){?>
        <table class="table">
          <thead>
            <tr class="text-secondary">
            <th colspan="5" class="text-secondary">DATOS GENERALES</th>
            <th colspan="4" class="border-end text-secondary">ACCIONES PARA ESTABLECIMIENTO</th>
            </tr>
            <tr>
              <th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de Inscripción</th>
              <th class="text-primary">Nombre Propietario</th> <th class="text-primary">Doc Propietario</th>              
              <th class="border-end text-primary">Editar propietario</th>
              <th class="text-primary"> Editar establecimiento</th> <th class="text-primary"> Solicitar visita</th> <th class="text-primary">Nuevo</th>
            </tr>
          </thead>
          <tbody>
            <?php
              while($registro=$sentenciaSQL->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
              <td><?= $registro['nom_comercial'];?></td><td><?= $registro['NIT']; ?></td><td><?= date('d-m-Y', strtotime($registro['fecha_registro'])); ?>
              <td><?= $registro['nom_propietario']." ".$registro['ape_propietario'];?></td><td><?= $registro['doc']; ?></td>
              
              <td class ="border-end">
                <?php if(isset($registro['nom_comercial'])){ ?>
                <a href="#" class="btn btn-sm btn-primary " data-bs-toggle="modal" data-bs-target="#editPropietario" data-bs-id="<?= $registro['id_propietario']; ?>">
                <i class="fa-solid fa-pen-to-square"></i></a>
                <?php } ?>
              </td>
              <td>
                <?php if(isset($registro['nom_comercial'])){ ?>
                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editEstablecimiento" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-pen-to-square"></i></a>
                <?php } ?>
              </td>
              <td>
                <?php if(isset($registro['nom_comercial'])){ ?>
                <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#solicitarVisita" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-bell-concierge"></i>
                <?php } ?>
              </td>
              <td>
              <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#nuevoEstablecimiento2" 
                data-id-propietario="<?= $registro['id_propietario']; ?>"
                data-nomape-propietario="<?= $registro['nom_propietario'].' '.$registro['ape_propietario']; ?>">
              <i class="fa-solid fa-plus"></i></a>
              </td>
            
            </tr>            
            <?php } ?>
            <tr>
            <td class="bg-primary text-white" colspan="8"><br>
              <p>¿No se encuentra el establecimiento y representante legal consultado? Realice una nueva inscripción:</p>
            </td>
            <td td class="bg-primary text-white">
              <a href="#" class="btn btn-sm btn-info bg-primary text-white" data-bs-toggle="modal" data-bs-target="#nuevoPropietario">
              <i class="fa-solid fa-plus bg-primary text-white"></i>Nuevo</a>
            </td>
          </tr>
          </tbody>
        </table>
      <?php } else{ ?>
        <table>
          <tr>
            <td><br><br>
              <p>No existen registros con el parámetro consultado. Desea crear un nuevo establecimiento?</p>
            </td>
            <td>
              <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#nuevoPropietario">
              <i class="fa-solid fa-plus"></i> Nuevo </a>
            </td>
          </tr>
        </table>
      <?php }}   ?>    
  </div>
  <?php include "crud/modals/modalImprimirInscripcion.php";  ?>
  <?php include "crud/modals/modalEditar.php";  ?>
  <?php include "crud/modals/modalEditPropietario.php";  ?>
  <?php include "crud/modals/modalSolicitarVisita.php";  ?>
  <?php include "crud/modals/modalNuevoPropietario.php";  ?> 
  <?php include "crud/modals/modalNuevoEstablecimiento2.php";  ?> 
</body>
</html>

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegEstablecimiento.php -->

<script>
let editEstablecimiento = document.getElementById('editEstablecimiento');
    editEstablecimiento.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let inputId = editEstablecimiento.querySelector('.modal-body #id');
        let inputRazonSocial = editEstablecimiento.querySelector('.modal-body #razon_social')
        let inputNomComercial = editEstablecimiento.querySelector('.modal-body #nom_comercial')
        let inputNit = editEstablecimiento.querySelector('.modal-body #nit')
        let inputDV = editEstablecimiento.querySelector('.modal-body #dv')
        let inputEstado = editEstablecimiento.querySelector('.modal-body #estado')
        let inputDirEstablecimiento = editEstablecimiento.querySelector('.modal-body #dir_establecimiento')
        let inputMailEstablecimiento = editEstablecimiento.querySelector('.modal-body #correo_establecimiento')
        let inputTelEstablecimiento = editEstablecimiento.querySelector('.modal-body #tel_establecimiento')
        let inputNomBarrio = editEstablecimiento.querySelector('.modal-body #id_barrio_vereda')
        let inputIdPropietario = editEstablecimiento.querySelector('.modal-body #id_propietario')
        let inputDocPropietario = editEstablecimiento.querySelector('.modal-body #doc_propietario')
        let inputNomPropietario = editEstablecimiento.querySelector('.modal-body #nom_propietario')
        let inputSucursal = editEstablecimiento.querySelector('.modal-body #sucursal')
        
                
        let url = "crud/getRegEstablecimiento.php";
        let formData = new FormData();
        formData.append('id', id);

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            inputId.value = data.id;
            inputRazonSocial.value = data.razon_social;
            inputNomComercial.value = data.nom_comercial;
            inputNit.value = data.nit;
            inputDV.value = data.digito_verificacion;
            inputEstado.checked = data.estado == 1;
            inputDirEstablecimiento.value = data.dir_establecimiento;
            inputMailEstablecimiento.value = data.correo_establecimiento;
            inputTelEstablecimiento.value = data.tel_establecimiento;
            inputNomBarrio.value = data.id_barrio_vereda;
            inputIdPropietario.value = data.id_propietario;
            inputDocPropietario.value = data.doc;
            inputNomPropietario.value = data.nom_propietario+" "+data.ape_propietario;
            inputSucursal.value = data.sucursal; 

        }).catch(err => {
            console.error('Error en la solicitud fetch:', err);
        });
    });
</script>

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegPropietario.php -->

<script>
let editPropietario = document.getElementById('editPropietario');
editPropietario.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let inputId = editPropietario.querySelector('.modal-body #id_edit_propietario');
        let inputNomPropietario = editPropietario.querySelector('.modal-body #edit_nom_propietario')
        let inputApePropietario = editPropietario.querySelector('.modal-body #edit_ape_propietario')
        let inputTdPropietario = editPropietario.querySelector('.modal-body #edit_td_propietario')
        let inputDocPropietario = editPropietario.querySelector('.modal-body #edit_doc_propietario')
        let inputTelPropietario = editPropietario.querySelector('.modal-body #edit_tel_propietario')
        let inputCorreoPropietario = editPropietario.querySelector('.modal-body #edit_correo_propietario')                
        let url = "crud/getRegPropietario.php";
        let formData = new FormData();
        formData.append('id', id);

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            inputId.value = data.id;
            inputNomPropietario.value = data.nom_propietario;
            inputApePropietario.value = data.ape_propietario;
            inputTdPropietario.value = data.tipo_doc;
            inputDocPropietario.value = data.doc;
            inputTelPropietario.value = data.tel_propietario;
            inputCorreoPropietario.value = data.correo_propietario;
        }).catch(err => {
            console.error('Error en la solicitud fetch:', err);
        });
    });
</script>

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegSolicitudVisita.php PARA SOLICITUD VISITA-->

<script>
let solicitarVisita = document.getElementById('solicitarVisita');
solicitarVisita.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let inputId = solicitarVisita.querySelector('.modal-body #id');
        let inputNomComercial = solicitarVisita.querySelector('.modal-body #nom_comercial')
        let inputNit = solicitarVisita.querySelector('.modal-body #nit')
        let inputDv = solicitarVisita.querySelector('.modal-body #dv')
        let inputDirEstablecimiento = solicitarVisita.querySelector('.modal-body #dir_establecimiento')
        let inputTelEstablecimiento = solicitarVisita.querySelector('.modal-body #tel_establecimiento')
        let inputNomBarrio = solicitarVisita.querySelector('.modal-body #nom_barrio')
        let inputNomComuna = solicitarVisita.querySelector('.modal-body #nom_comuna')
        let inputIdComuna = solicitarVisita.querySelector('.modal-body #id_comuna')
        let inputNomPropietario = solicitarVisita.querySelector('.modal-body #nom_propietario')
        let inputDocPropietario = solicitarVisita.querySelector('.modal-body #doc_propietario')
        let inputNomSolicitante = solicitarVisita.querySelector('.modal-body #nom_solicitante')
        let inputNomClase = solicitarVisita.querySelector('.modal-body #nom_clase')
        let inputIdSujeto = solicitarVisita.querySelector('.modal-body #id_sujeto_visita')
        //let inputObservacion = solicitarVisita.querySelector('.modal-body #observacion')
                
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
            inputTelEstablecimiento.value = data.tel_establecimiento;
            inputNomBarrio.value = data.nom_barrio;
            inputNomComuna.value = data.nom_comuna;
            inputIdComuna.value = data.id_comuna;
            inputNomPropietario.value = data.nom_propietario+" "+data.ape_propietario;
            inputDocPropietario.value = data.doc;
            inputNomSolicitante.value = data.nom_propietario+" "+data.ape_propietario;
            inputNomClase.value = data.nom_clase; 
            inputIdSujeto.value = data.id_sujeto; 
            //inputObservacion.value = data.observacion; 

        }).catch(err => {
            console.error('Error en la solicitud fetch:', err);
        });
    });
</script>

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

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegPropietario.php -->

<script>
let nuevoEstablecimiento2 = document.getElementById('nuevoEstablecimiento2');
nuevoEstablecimiento2.addEventListener('shown.bs.modal', event => {
  
    let button = event.relatedTarget;
    let id2 = button.getAttribute('data-id-propietario');  
    let nomapePropietario = button.getAttribute('data-nomape-propietario'); 
    
    // Asignar directamente los valores al modal
    let inputId = nuevoEstablecimiento2.querySelector('.modal-body #id_propietario2');
    let inputNomapePropietario = nuevoEstablecimiento2.querySelector('.modal-body #nomape_propietario');
    

    inputId.value = id2;
    inputNomapePropietario.value = nomapePropietario;

    console.log(id2, nomapePropietario); 
});
</script>

<!-- SCRIPT PARA CARGAR UN MODAL CON UN FORMULARIO NUEVO PARA REGISTRAR EN LA BASE DE DATOS UN NUEVO PROPIETARIO -->

<script>
let nuevoPropietario = document.getElementById('nuevoPropietario');
        nuevoPropietario.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;         
    });
</script>

<!-- SCRIPT PARA CARGAR UN PRIMER MODAL, GUARDAR DATOS Y DESPUÉS ABRIR UN SEGUNDO MODAL 
CONDICIONADO CON GUARDAR SÓLO EL MODAL PROPIETARIO Y TERMIAR O CONTINUAR CON EL MODAL DEL ESTABLECIMIENTO-->

<script>
document.getElementById('formPropietario').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de manera tradicional
    const formData = new FormData(this);
    const accion = event.submitter.value;

    fetch('crud/guardarPropietario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            // Cierra el modal actual
            var modalActual = bootstrap.Modal.getInstance(document.getElementById('nuevoPropietario')); 
            if (modalActual) {
                modalActual.hide();
            }
            if (accion === "guardar") {
                alert('Datos del representante legal guardados correctamente.');
                window.location.href = 'index_1.php'; // Redirige a index.php
            } else if (accion === "continuar") {
              // Aquí se obtiene el id y el nombre del nuevo propietario
              const nuevoId = data.id;
              const nom_propietario = data.nom_propietario;
              const ape_propietario = data.ape_propietario;
              console.log(nuevoId, nom_propietario, ape_propietario); 

              // Abre el modal de nuevo establecimiento
              var nuevoEstablecimiento = new bootstrap.Modal(document.getElementById('nuevoEstablecimiento'));
              nuevoEstablecimiento.show(); 

              // Espera un breve momento para asegurarte de que está cargado
              setTimeout(function() {
                  const idPropietarioField = document.getElementById('idP');
                  if (idPropietarioField) {
                      idPropietarioField.value = nuevoId;
                      document.getElementById('nomape_propietario').value = nom_propietario + ' ' + ape_propietario + ' ' + nuevoId;
                      console.log("Valor asignado al campo idP:", idPropietarioField.value);
                  } else {
                      console.error("Campo id_propietario no encontrado.");
                  }
              }, 500); // Ajusta el tiempo según sea necesario
              }
        } else {
          alert('Hubo un error al guardar el propietario.');
        }
    })
    .catch(error => console.error('Error:', error));
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