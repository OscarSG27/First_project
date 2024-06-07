<?php

/*Función que conecta a la base de datos */

$host="localhost";
$usuario="root";
$contrasenia="1234";
$dbname="biblioteca";

function conexion_bd($host,$dbname,$usuario,$contrasenia){

    try{

    $dsn="mysql:host=$host;dbname=$dbname;charset=utf8";

    $conexion=new PDO($dsn,$usuario,$contrasenia);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conexion;

    }catch(PDOException $e){

   echo "Error de conexión: ". $e->getMessage();
                                                    }
                                                }

?>