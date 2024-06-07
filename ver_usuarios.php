<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nuestros usuarios</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 
          body {
            background-image: url('../IMG/macbook.jpg');
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
            }    
        </style>
    </head>
    <body>
        <?php
         session_start();
          if(!isset($_SESSION["nombre"])){
             header("Location:../modelo/login_administrador.php");
            }?>


        
        <?php
        

   //Conexión con la base de datos mediante función creada previamente
        

       
   include_once '../modelo/funciones.php';


   
   $conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);
        

        ?>  
        
<div class="container justify-content-center">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
                <div class="container_titulo">
                    <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Datos de usuarios de la biblioteca</h2>
                </div>    

            <?php

            //Mostramos los usuarios mediante consulta SQL

            $sql="SELECT idusuarios as ID, Nombre, Apellidos, usuario as Usuario FROM usuarios";
            $sql_prep=$conexion->prepare($sql);
            try{

                $sql_prep->execute();
                echo "<div class='table-responsive'style='height: 70vh;'>";
                echo "<table class='table table-bordered table-centred'>";
                echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Usuario</th>";
                $contador_columnas = 0;
                while($fila=$sql_prep->fetch(PDO::FETCH_ASSOC)){
                    if ($contador_columnas == 0) {
                        echo "<tr>";
                    }
                    foreach($fila as $clave=>$valor){
                        echo "<td>$valor</td>";
                        $contador_columnas++;
                        if ($contador_columnas == 4) {
                            echo "</tr>";
                            $contador_columnas = 0;
                        }
                        
                    }
                 
                    
                }
                       
    
    
                echo "</table>";
                echo "</div>";
            }catch(PDOException $e){

                echo "<div class='alert alert-danger''>Ha ocurrido un error: </div>".$e->getMessage();


            }


            ?>
            

           <a href="../vista/perfil_administrador.php">Volver al perfil de administrador</a><br>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> 
    </body>
</html>