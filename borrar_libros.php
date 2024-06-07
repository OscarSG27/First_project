<!DOCTYPE html>
<html>



    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Borrar libros</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 
        
        body {
                background-image: url('../IMG/borrar.jpg');
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
                color: black;
                font-size: 1.5em;
                text-align: center;
                margin-top: 15vh;
            
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
<?php

// Recogemos en variables la información del libro que queremos borrar. Usaremos el titulo:

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $titulo_borrar=isset($_POST["borrar_libro"]) ? $_POST["borrar_libro"] : null;
   
if($titulo_borrar==null){

    echo "<div class='alert alert-info'>El campo título es obligatorio.</div>";
    
}else{
// Hacemos la conexión con la bbdd para poder hacer la modificación usando función ya creada

include_once '../modelo/funciones.php';



$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);


//Comprobamos si el libro introducido está en la base de datos

$sql="SELECT * FROM libros WHERE Titulo=:nuevo_titulo";
$sql_prep=$conexion->prepare($sql);
$sql_prep->bindParam(":nuevo_titulo",$titulo_borrar, PDO::PARAM_STR);

try{

    $sql_prep->execute();
    

    }catch(PDOException $e){
     echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();
    }

    if($sql_prep->rowCount()==0){

        echo "<div class='alert alert-warning'>Este libro no se encuentra registrado.</div>";
    }else{

        // consulta preparada para borrar libro una vez que hemos comprobado que existe en la base de datos

        $sql_bor="DELETE FROM libros WHERE Titulo=:nuevo_titulo";
        $sql_prep_bor=$conexion->prepare($sql_bor);
        $sql_prep_bor->bindParam(":nuevo_titulo",$titulo_borrar, PDO::PARAM_STR);
        
        try{
    
        $sql_prep_bor->execute();
        echo "<div class='alert alert-success'>Libro borrado con éxito</div>";

    
        }catch(PDOException $e){
            echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."</div>";
        }

    }


}
}
?>


               
            <form action="borrar_libros.php" method="POST">
                <label>Escribe el titulo del libro que quieres borrar</label>
                <input type="text" name="borrar_libro" required><br><br>
                <input type="submit" name="enviar" value="Enviar"><br><br><br>
                <a href="../vista/perfil_administrador.php">Volver al perfil de administrador</a>
            </form>

</div>
    </div>
        </div>
            </div>
                </div>      


           


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>