<?php
    ob_start(); 
    session_start();
    if($_SESSION){
    require('../config/conexion.php');    
    $idUsuario = $_SESSION['id_usuario'];
    $activo = $_SESSION['activo'];
    $idJefe=$_SESSION['jefe'];
    $subdependencia=$_SESSION['subdependencia'];
    $id_rol= $_SESSION['id_rol'];
    
    $sqlJefe = $conn->prepare("SELECT  d.nom_dependencia,sd.nom_subdependencia, u.nom_usuario
                                FROM usuario u
                                JOIN subdependencia sd on u.subdependencia = sd.id 
                                JOIN dependencia d on sd.id_dependencia = d.id
                                WHERE u.id = $idUsuario ");
    
    $sqlJefe->execute();
    $row_jefe = $sqlJefe->fetch(PDO::FETCH_ASSOC);

    setlocale(LC_TIME, 'es_ES.UTF-8');
    date_default_timezone_set('America/Bogota');
?>
    
    <!DOCTYPE html>
    <html lang="es">    
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <head>
        <div class="alert alert-success" style="text-align: right">        
        <strong>Usuario actual: <?php echo $_SESSION['usuario'];?></strong>                             
        </div>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>INSCRIPCIONES SALUD AMBIENTAL</title> 
        <link rel="stylesheet" href="../config/css/bootstrap.rtl.css"/><!--carga la hoja de estilo de bootstrap -->
        <script src="https://kit.fontawesome.com/7b7cf05d30.js" crossorigin="anonymous"></script> <!--carga los íconos --> 
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">      
        <script src="../config/js/jquery.min.js"></script>
        <script src="../config/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Barra de menú -->        
        <nav class="navbar navbar-expand navbar-dark bg-primary">
        <ul class="nav navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php" role="button">Inicio</a>                
            </li>
            <?php if($id_rol=='1' or $id_rol=='4' or $id_rol=='5' or $id_rol=='7'){ ?>                   
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown">Módulo Registrador</a>
                <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index_1.php">Administrar establecimieto</a></li>
                        <li><a class="dropdown-item" href="index_1b.php">Imprimir solicitud de Reg. sanitario</a></li>
                        <li><a class="dropdown-item" href="index_1c.php">imprimir formato de Inscripción</a></li>
                </ul>
            </li>
            <?php } ?>
            <?php if($id_rol=='2' or $id_rol=='4' or $id_rol=='6' or $id_rol=='7'){ ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown">Módulo Técnico visita</a>
                <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index_2.php">Revisar solicitudes</a></li>
                </ul>
            </li>
            <?php } ?>
            <?php if($id_rol=='3' or $id_rol=='5' or $id_rol=='6' or $id_rol=='7'){ ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown">Módulo digitador actas</a>
                <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="crear_usuario.php">Revisar existencia establecimiento</a></li>                        
                        <li><a class="dropdown-item" href="admin_usuario.php">Actualizar cargue establecimieto a SSA</a></li>
                </ul>
            </li>
            <?php } ?>                        
        </li>            
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="cerrar.php">Cerrar sesión</a>
            </li>
        </ul>
        </nav>
        <style>
            .hidden {
                display: none;
            }
        </style>                   
    </head>
    <?php } else header('Location:../index.php');?>