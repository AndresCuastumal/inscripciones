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
            <th colspan="6" class="text-secondary">DATOS GENERALES</th>
            <th colspan="4" class="border-end text-secondary">ACCIONES PARA ESTABLECIMIENTO</th>
            </tr>
            <tr>
              <th class="text-primary">ID</th> <th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de Inscripción</th>
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
              <td><?= $registro['id'];?></td><td><?= $registro['nom_comercial'];?></td><td><?= $registro['nit']; ?></td><td><?= date('d-m-Y', strtotime($registro['fecha_registro'])); ?>
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
                <button class="btnSolicitarVisita btn btn-sm btn-success" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-bell-concierge"></i>
                </button>
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
            <td class="bg-primary text-white" colspan="9"><br>
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
  <?php include "crud/modals/modalNuevoEstablecimiento.php";  ?>  
  <?php include "crud/modals/modalNuevoEstablecimiento2.php";  ?>
  <?php include "crud/modals/modalAviso.php";  ?> 
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
        let inputSujetoEditEstablecimiento = editEstablecimiento.querySelector('.modal-body #sujeto_editEstablecimiento')
        let inputClaseEditEstablecimiento = editEstablecimiento.querySelector('.modal-body #clase_editEstablecimiento')
        let inputNomBarrio = editEstablecimiento.querySelector('.modal-body #id_barrio_vereda')
        let inputIdBarrioActual= editEstablecimiento.querySelector('.modal-body #id_barrio_vereda_actual');
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
            inputIdBarrioActual.value = data.id_barrio_vereda; // Guardar el id del barrio actual para compararlo después
            

            // Cargar el select de sujeto con el id y nombre
            let selectSujeto = inputSujetoEditEstablecimiento;
            selectSujeto.innerHTML = ''; // Limpia el select antes de cargar
            let optionSujeto = document.createElement('option');
            optionSujeto.value = data.id_sujeto;
            optionSujeto.textContent = data.nom_sujeto;
            optionSujeto.selected = true; // Marcar como seleccionado
            selectSujeto.appendChild(optionSujeto);

            // Ahora traer los demás sujetos desde el backend
            fetch("crud/cargar_sujeto.php")
                .then(response => response.json())
                .then(sujetos => {
                    sujetos.forEach(sujeto => {
                        if (sujeto.id != data.id) {
                            let option = document.createElement('option');
                            option.value = sujeto.id;
                            option.textContent = sujeto.nom_sujeto;
                            selectSujeto.appendChild(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los sujetos:', error);
                });

            // Cargar el select de clase con el id y nombre
            let selectClase = inputClaseEditEstablecimiento;
            selectClase.innerHTML = ''; // Limpia el select antes de cargar
            let option = document.createElement('option');
            option.value = data.id_clase;
            option.textContent = data.nom_clase;
            option.selected = true; // Marcar como seleccionado
            selectClase.appendChild(option);

            


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

// ESTE SCRIPT SE ENCARGA DE VERIFICAR SI EL ESTABLECIMIENTO YA TIENE UNA SOLICITUD REGISTRADA, 
// SI NO LA TIENE SE ABRE EL MODAL PARA SOLICITAR VISITA, SINO SE MUESTRA UN MENSAJE DE AVISO

document.querySelectorAll('.btnSolicitarVisita').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault(); //Previene que el modal se abra antes de verificar
        let id = this.getAttribute('data-bs-id'); // Obtener el ID del establecimiento desde el atributo data-bs-id
        console.log("ID antes de abrir el modal:", id);
        let urlVerificar = "crud/verificarSolicitudExistente.php";
        let formData = new FormData();
        formData.append('id', id);

        fetch(urlVerificar, {
            method: 'POST',
            body: formData
        }).then(res => res.json())
        .then(data => {
            console.log("Respuesta del backend:", data);
           if (data.existe) {
              let mensaje = `Este establecimiento ya tiene una solicitud registrada. Fue realizada el día ${data.fecha}.`;
    
              // Redirigir a tu archivo de alertas
              window.location.href = `crud/alertas/alerta_error.php?mensaje=${encodeURIComponent(mensaje)}`;
            } else {
                // Setear el ID al modal para usarlo en shown.bs.modal
                let modalElement = document.getElementById('solicitarVisita');
                modalElement.setAttribute('data-bs-id', id);

                let modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }).catch(error => {
            console.error("Error al verificar la solicitud:", error);
        });
    });
});

//DE AQUÍ EN ADELANTE SE CARGAN LOS DATOS DEL ESTABLECIMIENTO EN EL MODAL DE SOLICITUD VISITA SI NO FUE ENCONTRADA EN LA TABLA DE SOLICITUD

let solicitarVisita = document.getElementById('solicitarVisita');
solicitarVisita.addEventListener('shown.bs.modal', event => {
    //let button = event.relatedTarget;
    let id = solicitarVisita.getAttribute('data-bs-id');

    console.log("ID dentro del modal:", id); // <-- útil para debug

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

    let url = "crud/getRegSolicitudVisita.php";
    let formData = new FormData();
    formData.append('id', id);

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
        inputNomPropietario.value = data.nom_propietario + " " + data.ape_propietario;
        inputDocPropietario.value = data.doc;
        inputNomSolicitante.value = data.nom_propietario + " " + data.ape_propietario;
        inputNomClase.value = data.nom_clase;
        inputIdSujeto.value = data.id_sujeto;
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
<!-- SCRIPT PARA VALIDAR SI EL PROPIETARIO EXISTE -->

<script src="crud/Validaciones/validarPropietario.js"></script>

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