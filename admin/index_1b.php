<?php
include("cabecera.php");
// Variables iniciales
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
$busqueda = (isset($_POST['busqueda'])) ? trim($_POST['busqueda']) : "";
$registrosPorPagina = 10;

// Configuración de paginación
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max(1, $paginaActual);

// Fecha para consulta inicial
$fecha_busqueda = new DateTime();
$fecha_busqueda->modify('-90 days');
$fecha_formateada = $fecha_busqueda->format('Y-m-d');

// Determinar si estamos en modo búsqueda
$modoBusqueda = (!empty($busqueda) || (isset($_POST['accion']) && $_POST['accion'] == 'Consultar'));

// Consulta base sin búsqueda
if(!$modoBusqueda) {
    // Primero contar el total de registros
    $countSQL = $conn2->prepare("SELECT COUNT(*) as total 
                               FROM solicitud s 
                               JOIN establecimiento e ON e.id = s.id_establecimiento
                               JOIN propietario p ON e.id_propietario = p.id                                      
                               WHERE s.fecha_solicitud > :fecha_busqueda");
    $countSQL->bindParam(':fecha_busqueda', $fecha_formateada);
    $countSQL->execute();
    $totalRegistros = $countSQL->fetchColumn();
    
    // Consulta con paginación
    $inicio = ($paginaActual - 1) * $registrosPorPagina;
    $sentenciaSQL = $conn2->prepare("SELECT e.id, e.nom_comercial, e.nit, 
                                   s.fecha_solicitud, s.id as id_solicitud,
                                   p.nom_propietario, p.ape_propietario, p.doc, p.id as id_propietario 
                                   FROM solicitud s 
                                   JOIN establecimiento e ON e.id = s.id_establecimiento
                                   JOIN propietario p ON e.id_propietario = p.id                                      
                                   WHERE s.fecha_solicitud > :fecha_busqueda
                                   ORDER BY s.id DESC
                                   LIMIT :inicio, :registrosPorPagina");
    $sentenciaSQL->bindParam(':fecha_busqueda', $fecha_formateada);
    $sentenciaSQL->bindParam(':inicio', $inicio, PDO::PARAM_INT);
    $sentenciaSQL->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
} 
// Consulta con búsqueda
else {
    $busquedaLike = '%'.$busqueda.'%';
    
    // Primero contar el total de registros con filtro
    $countSQL = $conn2->prepare("SELECT COUNT(*) as total 
                               FROM solicitud s 
                               JOIN establecimiento e ON e.id = s.id_establecimiento
                               JOIN propietario p ON e.id_propietario = p.id                                      
                               WHERE e.nit LIKE :busqueda
                               OR p.doc LIKE :busqueda
                               OR e.nom_comercial LIKE :busqueda
                               OR CONCAT(p.nom_propietario,' ',p.ape_propietario) LIKE :busqueda");
    $countSQL->bindParam(':busqueda', $busquedaLike);
    $countSQL->execute();
    $totalRegistros = $countSQL->fetchColumn();
    
    // Consulta con paginación y filtro
    $inicio = ($paginaActual - 1) * $registrosPorPagina;
    $sentenciaSQL = $conn2->prepare("SELECT e.id, e.nom_comercial, e.nit, 
                                   s.fecha_solicitud, s.id as id_solicitud,
                                   p.nom_propietario, p.ape_propietario, p.doc, p.id as id_propietario 
                                   FROM solicitud s 
                                   JOIN establecimiento e ON e.id = s.id_establecimiento
                                   JOIN propietario p ON e.id_propietario = p.id                                      
                                   WHERE e.nit LIKE :busqueda
                                   OR p.doc LIKE :busqueda
                                   OR e.nom_comercial LIKE :busqueda
                                   OR CONCAT(p.nom_propietario,' ',p.ape_propietario) LIKE :busqueda
                                   ORDER BY s.id DESC
                                   LIMIT :inicio, :registrosPorPagina");
    $sentenciaSQL->bindParam(':busqueda', $busquedaLike);
    $sentenciaSQL->bindParam(':inicio', $inicio, PDO::PARAM_INT);
    $sentenciaSQL->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
}

$sentenciaSQL->execute();

// Calcular total de páginas
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>
<body>
  <!-- Encabezado y otros elementos HTML permanecen iguales -->
  
  <div class="container mt-5">
    <div class="row">
      <div class="bg-light text-info ">
        <h3>Listar solicitudes de concepto sanitario</h3>
        <p class="text-justify">En este módulo se listan las solicitudes de los últimos 90 días en el sistema.</p>      
      </div>    
    </div>
    <div>
    <br></br>  
      <form method="POST" action="">
        <div class="mb-3" id="input-container">
          <label for="busqueda" id="dynamicLabel">Si necesita consultar una solicitud en específico, escriba el nombre del establecimiento o NIT.</label>
          <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="" value="<?= htmlspecialchars($busqueda) ?>" required>
        </div>        
        <div>
          <button type="submit" name="accion" value="Consultar" class="btn btn-sm btn-success btn-block">Buscar</button>
          <?php if($modoBusqueda): ?>
            <a href="?" class="btn btn-sm btn-secondary">Limpiar búsqueda</a>
          <?php endif; ?>
        </div>
      </form> 
    </div>
  </div>  
  <div class="container mt-5 card text-center"> 
    <table class="table">
      <thead>
        <tr class="text-secondary">
          <th colspan="6" class="text-secondary">DATOS GENERALES</th>
          <th class="border-end text-secondary">ACCIONES PARA ESTABLECIMIENTO</th>
        </tr>
        <tr>
          <th class="text-primary">ID</th>
          <th class="text-primary">Nombre establecimiento</th>
          <th class="text-primary">NIT establecimiento</th>
          <th class="text-primary">Fecha de solicitud</th>
          <th class="text-primary">Nombre Propietario</th>
          <th class="text-primary">Doc Propietario</th>              
          <th class="border-end text-primary">Imprimir solicitud de visita</th>              
        </tr>
      </thead>
      <tbody>
        <?php while($registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?= htmlspecialchars($registro['id_solicitud']) ?></td>
            <td><?= htmlspecialchars($registro['nom_comercial']) ?></td>
            <td><?= htmlspecialchars($registro['nit']) ?></td>
            <td><?= date('d-m-Y', strtotime($registro['fecha_solicitud'])) ?></td>
            <td><?= htmlspecialchars($registro['nom_propietario']." ".$registro['ape_propietario']) ?></td>
            <td><?= htmlspecialchars($registro['doc']) ?></td>
            <td class="border-end">                
              <a href="../admin/crud/imprimirSolicitudVisita.php?id=<?= $registro['id_solicitud'] ?>" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-print"></i>
              </a>                
            </td>             
          </tr>            
        <?php endwhile; ?>            
      </tbody>
    </table>
    
    <!-- Paginación -->
    <?php if($totalPaginas > 1): ?>
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php if($paginaActual > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?><?= !empty($busqueda) ? '&busqueda='.urlencode($busqueda) : '' ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>
          
          <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?><?= !empty($busqueda) ? '&busqueda='.urlencode($busqueda) : '' ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          
          <?php if($paginaActual < $totalPaginas): ?>
            <li class="page-item">
              <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?><?= !empty($busqueda) ? '&busqueda='.urlencode($busqueda) : '' ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>  

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