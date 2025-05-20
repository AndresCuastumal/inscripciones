<?php
    session_start();       
    error_reporting(E_ALL );
    error_reporting(E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', 1);
    include("config/conexion.php"); 
    $accion=(isset($_POST['accion']))?$_POST['accion']:"";
    switch($accion){   
        case 'Ingresar':              
            $user=(isset($_POST['user']))?$_POST['user']:"";            
            $password=(isset($_POST['password']))?$_POST['password']:"";            
            $sentenciaSQL=$conn->prepare("SELECT * FROM usuario WHERE usuario=:user");            
            $sentenciaSQL->bindParam(':user',$user);            
            $sentenciaSQL->execute();                      
            if($registro=$sentenciaSQL->fetch(PDO::FETCH_ASSOC)){ 
            if(password_verify($password, $registro['password'])){
                $_SESSION['usuario']=$registro['nom_usuario'].' '.$registro['ape_usuario'];
                $_SESSION['id_usuario']=$registro['id'];
                $_SESSION['activo']=$registro['activo'];
                $_SESSION['cargo']=$registro['cargo'];
                $_SESSION['jefe']=$registro['jefe'];
                $_SESSION['subdependencia']=$registro['subdependencia'];
                $sentenciaSQLrol=$conn->prepare("SELECT id_rol FROM rolxusuario WHERE id_usuario=:id_usuario");            
                $sentenciaSQLrol->bindParam(':id_usuario',$registro['id']);            
                $sentenciaSQLrol->execute();
                if($registroRol=$sentenciaSQLrol->fetch(PDO::FETCH_ASSOC)){ 
                    $_SESSION['id_rol'] = $registroRol['id_rol'];
                    header("Location: admin/index.php");
                }
                else{?>
                    <div class="alert alert-danger" role="alert">
                        <strong>No existe ningún rol asignado, comuniquese con el administrador del sistema</strong> 
                    </div>
                <?php

                }
                }
            else{ ?>
                <div class="alert alert-danger" role="alert">
                    <strong>El usuario o la contraseña no coinciden, intente nuevamente</strong> 
                </div>
                
                
        <?php }
            }
            else{
                ?>
                <div class="alert alert-danger" role="alert">
                    <strong>El usuario no existe, revise los datos ingresados</strong> 
                </div>
        <?php  }                                  
        break;
    }
?>
<!DOCTYPE html>
<html lang="en">
<link rel="icon" href="admin/img/favicon.ico" type="image/x-icon">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Inscripciones Salud Ambiental</title>        
</head>
<body font-size: 1rem  >
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>        
            <div class="col-md-7">
                <br><br><br><br>    
                <div class="card">
                    <div class="card-header" align="center">
                    <img class="img-fluid d-block mx-auto mb-0" src="admin/img/inscripciones_largoHorizontal.png" alt="800" width="715">
                        
                        <p class="lead"></p>                        
                        </b></code>
                    </div>
                    <div class="card-body lead">
                                <form method ="POST">
                                    <div class="mb-3">
                                        <label for="user">Nombre de Usuario:</label>
                                        <input type="text" class="form-control" name = "user" id="user" placeholder="" value="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password">Contraseña:</label>
                                        <input type="password" class="form-control" name = "password" id="password" placeholder="" value="" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <input type="submit" name="accion" value="Ingresar" class="btn btn-sm btn-primary btn-block"/>
                                    </div>
                                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>                            
</body>
</html>
