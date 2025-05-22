<?php
date_default_timezone_set('America/Bogota');
require_once __DIR__ . '/vendor/autoload.php'; //carga composer al proyecto.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); //crea instancia de la clase dotenv
$dotenv->load(); //va a la carpeta raiz, busca el archivo .env de variables de entorno 
  $dotenv->required(['host', 'db', 'usuario', 'password']);
  $host = $_ENV['host'];
  $name = $_ENV['db'];
  $name2 = $_ENV['db2'];
  $user = $_ENV['usuario'];
  $pass = $_ENV['password'];
try {
        $conn=new PDO("mysql:host=$host;dbname=$name; charset=utf8", $user,$pass);        
    } catch (Exception $errorconexion) {
        echo $errorconexion->getMessage();
    }
    //para actualizasr y enviar a servidor
    try {
      $conn2=new PDO("mysql:host=$host;dbname=$name2; charset=utf8", $user,$pass);        
  } catch (Exception $errorconexion) {
      echo $errorconexion->getMessage();
  }
  //para actualizasr y enviar a servidor
?>