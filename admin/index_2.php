<?php
include("cabecera.php");
$anio_actual= date("Y");    
$countSQL = $conn->prepare("SELECT COUNT(*) as total FROM serviciosivc.solicitud WHERE id_usr_tecnico = :id_usuario and YEAR(fecha_solicitud)=:anio_actual");
$countSQL->bindParam(':id_usuario', $idUsuario);
$countSQL->bindParam(':anio_actual', $anio_actual);
$countSQL->execute();
$totalRegistros = $countSQL->fetchColumn();
    
?>
<div class="container mt-5 card text-center"> 
    <?php if($totalRegistros > 0) { 
            // Configuración de paginación
            $registrosPorPagina = 10;            
            $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
            
            // Obtener página actual (si no está definida, será 1)
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $paginaActual = max(1, min($paginaActual, $totalPaginas)); // Asegurar que esté entre 1 y totalPaginas
            
            // Calcular el inicio del LIMIT
            $inicio = ($paginaActual - 1) * $registrosPorPagina;
            
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
                limit :inicio, :registrosPorPagina
            ");    
            $sentenciaSQL->bindParam(':id_usuario',$idUsuario);
            $sentenciaSQL->bindParam(':anio_actual',$anio_actual);
            $sentenciaSQL->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $sentenciaSQL->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
            $sentenciaSQL->execute();
            $registrosPagina = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);            
    ?>
        <table class="table">
          <thead>
            <tr class="text-secondary">
            <th colspan="7" class="text-secondary">ESTABLECIMIENTOS SOLICITANTES DE VISITA</th>
            <th colspan="1" class="border-end text-secondary">ACCIONES</th>
            </tr>
            <tr>
              <th class="text-primary">Nombre establecimiento</th> <th class="text-primary">NIT establecimiento</th> <th class="text-primary">Fecha de solicitud</th>
              <th class="text-primary">Nombre Propietario</th> <th class="text-primary">Comuna</th>
              <th class="text-primary">Sujeto</th><th class="text-primary">Estado de visita</th> <th class="border-end text-primary">Actualizar estado visita</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($registrosPagina as $registro): ?>
            <tr>
              <td><?= htmlspecialchars($registro['nom_comercial']); ?></td>
              <td><?= htmlspecialchars($registro['NIT']); ?></td>
              <td><?= date('d-m-Y', strtotime($registro['fecha_solicitud'])); ?></td>
              <td><?= htmlspecialchars($registro['nom_propietario']." ".$registro['ape_propietario']); ?></td>
              <td><?= htmlspecialchars($registro['nom_comuna']); ?></td>
              <td><?= htmlspecialchars($registro['nom_sujeto']); ?></td>
              <td><?= htmlspecialchars($registro['visitado']); ?></td>
              
              <td class="border-end">                
                <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#actualizarVisita" data-bs-id="<?= $registro['id']; ?>">
                <i class="fa-solid fa-pencil"></i></a>                
              </td>
            </tr>            
            <?php endforeach; ?>            
          </tbody>
        </table>
        
        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if($paginaActual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?= $paginaActual - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if($paginaActual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?= $paginaActual + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        
      <?php } else { ?>
        <table>
          <tr>
            <td>
              <p>No existe ninguna solicitud asignada a usted. Comuníquese con el administrador del sistema</p>
            </td>            
          </tr>
        </table>
      <?php } 
      ?>    
</div>
  <?php include "crud/modals/modalActualizarVisita.php";  ?>
   
</body>
</html>

<!-- SCRIPT PARA CARGAR UN MODAL CON DATOS CONSULTADOS EN getRegSolicitudVisita.php PARA IMPRIMIR INSCRIPCION -->

<script>
let imprimirInscripcion = document.getElementById('actualizarVisita');
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
        let inputFechaVisita = imprimirInscripcion.querySelector('.modal-body #fecha_visita')
        let inputNomClase = imprimirInscripcion.querySelector('.modal-body #nom_clase')
        let inputObservacion = imprimirInscripcion.querySelector('.modal-body #observacion')
                
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
            inputNomBarrio.value = data.nom_barrio;
            inputNomComuna.value = data.nom_comuna;
            inputNomPropietario.value = data.nom_propietario+" "+data.ape_propietario;
            inputDocPropietario.value = data.doc;
            inputFechaVisita.value = data.fecha_visita ? data.fecha_visita : ''; // Si no hay fecha de visita, dejar vacío
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