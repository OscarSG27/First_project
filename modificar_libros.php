<!DOCTYPE html>
<html>

<!-- Creamos el espacio reservado para modificar los datos de los libros, título y autor, haciendo update en la bbdd --> 

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Modificar libros</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 

body {
                background-image: url('../IMG/books.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                width: 100%;
                height: 100%;
            }

            body, html{
                    margin: 0;
                    padding: 0;
                    width: 100%;
                    height: 100%;
                }

            .container_titulo{
                font-family: "Caveat", cursive;
                font-optical-sizing: auto;
                font-weight: 700;
                font-style: normal;
                font-size: 1.5em;
                margin-bottom: 1em;
                }        
           
        </style>
    </head>
    <body>
        <?php
            session_start();
            if(!isset($_SESSION["nombre"])){
                header("Location:../modelo/login_administrador.php");
            }
        ?>
<div class="container justify-content-center">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
            <div class="container_titulo">
                <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Modifica los libros de la biblioteca</h2>
            </div>


<?php

// Hacemos la conexión con la bbdd para poder hacer la modificación mediante función ya creada

include_once '../modelo/funciones.php';



$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);

// Recogemos en una variable los nuevos datos de los libros:

if($_SERVER["REQUEST_METHOD"]=="GET"){

$titulo_antiguo= isset($_GET["titulo_antiguo"]) ? $_GET["titulo_antiguo"] : null;
$nuevo_titulo= isset($_GET["nuevo_titulo"]) ? $_GET["nuevo_titulo"] : null;
$autor_nuevo=isset($_GET["nuevo_autor"]) ? $_GET["nuevo_autor"] : null;
$disponibilidad=isset($_GET["disponibilidad"]) ? $_GET["disponibilidad"] : null;

//También validamos en el server para que introduzcan el único valor obligatorio

if(!$titulo_antiguo){

    echo "<div class='alert alert-info' style='width: 25em; margin-top: 2em;'>Recuerda que el campo 'Título' es obligatorio</div>";

}else{

//Comprobamos si el titulo antiguo introducido está en la base de datos:

       $sql="SELECT * FROM libros WHERE Titulo=:titulo_anterior";
       $sql_prep=$conexion->prepare($sql);
       $sql_prep->bindParam(":titulo_anterior",$titulo_antiguo, PDO::PARAM_STR);
      
       
       try{

       $sql_prep->execute();
       

       }catch(PDOException $e){
        echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();
       }

    if($sql_prep->rowCount()==0){

    echo "<div class='alert alert-danger'>Libro no encontrado en la base de datos</div>";

}else{

    //Comprobamos si se ha dejado algún campo en blanco, excepto el título, para imprimir error:

    if(!$nuevo_titulo and !$autor_nuevo and !$disponibilidad){

        echo "<div class='alert alert-danger'>Campos de modificación vacíos. No se harán modificaciones</div>";
    }
// consulta preparada para realizar las modificaciones

//Para modificar el título siempre que se haya cumplimentado ese campo

if($nuevo_titulo!=null){
    $sql_mod="UPDATE libros SET Titulo=:nuevo_titulo WHERE Titulo=:titulo_anterior";
    $sql_prep=$conexion->prepare($sql_mod);
    $sql_prep->bindParam(":nuevo_titulo",$nuevo_titulo, PDO::PARAM_STR);
    $sql_prep->bindParam(":titulo_anterior",$titulo_antiguo, PDO::PARAM_STR);


    try{

    $sql_prep->execute();
    echo "<div class='alert alert-success'>Título modificado con éxito</div>";

    }catch(PDOException $e){
        echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."/div>";
    }
}

//Para modificar el autor siempre que se haya cumplimentado ese campo. Como hemos usado INNER JOIN, usamos ahora una subconsulta para modificar la tabla autores
    
if($autor_nuevo!=null){

    $sql_mod_autor="UPDATE autores 
    SET Nombre=:nuevo_autor 
    WHERE id IN (SELECT Autor FROM libros WHERE Titulo=:titulo_anterior)";
    $sql_prep_autor=$conexion->prepare($sql_mod_autor);
    $sql_prep_autor->bindParam(":nuevo_autor",$autor_nuevo, PDO::PARAM_STR);
    $sql_prep_autor->bindParam(":titulo_anterior",$titulo_antiguo, PDO::PARAM_STR);

    
    try{
    $sql_prep_autor->execute();
    echo "<div class='alert alert-success'>Autor modificado con éxito</div>";

    }catch(PDOException $e){
        echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."/div>";
    }

}

/*Hacemos la comprobación de si el usuario administrador está escribiendo la cadena SI/NO en disponibilidad en mayúsculas,
ya que hay una consulta SQL en ver_libros.php que 'tira' de estos caracteres en mayúsculas*/


if($disponibilidad!=null and ctype_lower($disponibilidad)){

    echo "<div class='alert alert-info'>Deberás escribir esto en MAYÚSCULAS</div>";


}else if($disponibilidad!=null and ($disponibilidad=="SI" || $disponibilidad=="NO")){

    $sql_mod_disp="UPDATE libros SET Disponibilidad=:disponibilidad WHERE Titulo=:titulo_anterior";
    $sql_prep_disp=$conexion->prepare($sql_mod_disp);
    $sql_prep_disp->bindParam(":disponibilidad",$disponibilidad, PDO::PARAM_STR);
    $sql_prep_disp->bindParam(":titulo_anterior",$titulo_antiguo, PDO::PARAM_STR);

    
    try{
    $sql_prep_disp->execute();
    echo "<div class='alert alert-success'>Disponibilidad modificada con éxito</div>";

    }catch(PDOException $e){
        echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."</div>";
    }

}else if($disponibilidad!=null and ($disponibilidad!="SI" || $disponibilidad!="NO")){

    echo "<div class='alert alert-danger'>Revisa si has escrito las palabras SI o NO</div>";
}
    
}
}
}


?>

               
                <form action="modificar_libros.php" method="GET" style="margin-top:60px; width: fit-content;">
                    <div class="mb-3">
                        <label class="form-label">Introduce el título del libro que deseas modificar</label>
                        <input type="text" name="titulo_antiguo"  class="form-control" required> <!--Este será el único valor obligatorio. Validamos en HTML-->
                    </div>
                    <div class="mb-3">    
                        <label class="form-label">Nuevo nombre de título</label>
                        <input type="text" name="nuevo_titulo" class="form-control">
                    </div>
                    <div class="mb-3">    
                        <label class="form-label">Nuevo nombre de autor</label>
                        <input type="text" name="nuevo_autor" class="form-control">
                    </div>
                    <div class="mb-3">    
                        <label class="form-label">Modificar la disponibilidad (En mayúsculas, SI/NO)</label>
                        <input type="text" clas="form-control" name="disponibilidad"><br>
                    </div>    
                        <input type="submit" name="enviar" class="btn btn-primary" value="Enviar"><br><br><br>
                        <a href="../vista/perfil_administrador.php">Volver a mi espacio</a>
                </form>
</div>
    </div>
        </div>
            </div>                

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>