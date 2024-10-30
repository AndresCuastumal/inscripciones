<?php
    ob_start(); 
    session_start();
    if($_SESSION){
    require('../config/conexion.php');    
    $idUsuario = $_SESSION['id_usuario'];
    $activo = $_SESSION['activo'];
    $idJefe=$_SESSION['jefe'];
    $subdependencia=$_SESSION['subdependencia'];
    
    $sqlJefe = $conn->prepare("SELECT  d.nom_dependencia,sd.nom_subdependencia, u.nom_usuario
                                FROM usuario u
                                JOIN subdependencia sd on u.subdependencia = sd.id 
                                JOIN dependencia d on sd.id_dependencia = d.id
                                WHERE u.id = $idUsuario ");
    
    $sqlJefe->execute();
    $row_jefe = $sqlJefe->fetch(PDO::FETCH_ASSOC);
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

        <!-- Barra de menú -->        
        <nav class="navbar navbar-expand navbar-dark bg-primary">
        <ul class="nav navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown">Nueva Inscripción</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index_consulta.php?consulta=fuid">Consulta general</a></li>
                    <li><a class="dropdown-item" href="index_usuarios.php">Consulta por usuario</a></li>
                    <li><a class="dropdown-item" href="index_consulta.php?consulta=noexistentes">Consulta no existentes</a></li>
                </ul>
            </li>                   
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown">Modificar inscripción</a>
                <ul class="dropdown-menu">                
                    <?php if($_SESSION['cargo']==3){  ?>               
                        <li><a class="dropdown-item" href="crear_usuario.php">Crear usuario</a></li>                        
                        <li><a class="dropdown-item" href="admin_usuario.php">Administrar usuario</a></li>
                        <li><a class="dropdown-item" href="updatePuser.php">Restablecer contraseña</a></li>
                    <?php } ?>                
                </ul>
            </li>
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