<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nuestros libros</title>
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
             header("Location:../modelo/login.php");
            }
            ?>
<div class="container justify-content-center">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="mt-3"> <!-- Margen superior para separar del borde superior -->
                <div class="container_titulo">
                    <h1 class="fw-bold fs-1 fs-md-5 fs-lg-7">Hola <?php echo $_SESSION["nombre"]?>. Elige el libro que quieras</h1>
                </div>    
           
          
        
        <?php
       

   //Conexión con la base de datos mediante función creada previamente
        

       
   include_once '../modelo/funciones.php';


   
   $conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);



   
        if($_SERVER["REQUEST_METHOD"]=="POST"){

          $titulo_introducido=isset($_POST["titulo"]) ? $_POST["titulo"] : null;

          if (!isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = array();
        }

       

        //Vamos a comprobar si el título está en la base de datos y si tiene disponibilidad

        //Consulta SQL

        $sql_titulo= "SELECT * FROM libros WHERE Titulo=:titulo AND Disponibilidad='SI'";
        $sql_titulo_prep=$conexion->prepare($sql_titulo);
        $sql_titulo_prep->bindParam(":titulo",$titulo_introducido, PDO::PARAM_STR);
        
        try{

            $sql_titulo_prep->execute();

        }catch(PDOException $e){

            echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."</div>";

        }

        if($sql_titulo_prep->rowCount()>0){
            
            //Comprobamos si el libro está ya en el carrito

            if(!in_array($titulo_introducido, $_SESSION["carrito"])){

                echo "<div class='alert alert-success'>Excelente elección. Hemos agregado el libro a tu bolsa</div>";
                $_SESSION["carrito"][]=$titulo_introducido;

            }else{

                echo "<div class='alert alert-warning'>Ya tienes agregado este libro. ¡Compartir es vivir!</div>";
            }
            
           

            }else{

            echo "<div class='alert alert-danger'>El libro NO se encuentra en la biblioteca o NO está disponible</div>";
        }
    
       
    }

        ?>    
        <form action="ver_libros.php" method="POST" style="margin-bottom: 1.5em;">
            <label><b>Escribe aquí el titulo</b></label>
            <input type="text" name="titulo">
            <input type="submit" value="Añadir a bolsa">
        </form>
        
            <?php

            //Mostramos los libros mediante consulta SQL con INNER JOIN

            $sql="SELECT libros.Titulo, libros.Disponibilidad, autores.Nombre as Autor
            FROM libros
            INNER JOIN autores on libros.autor=autores.id";
            $sql_prep=$conexion->prepare($sql);
            try{

                $sql_prep->execute();
                echo "<div class='table-responsive'style='height: 70vh;'>";
                echo "<table class='table table-bordered table-centred'>";
                echo "<tr><th>Título</th><th>Disponibilidad</th><th>Autor</th>";
                $contador_columnas = 0;
                while($fila=$sql_prep->fetch(PDO::FETCH_ASSOC)){
                    if ($contador_columnas == 0) {
                        echo "<tr>";
                    }
                    foreach($fila as $clave=>$valor){
                        echo "<td>$valor</td>";
                        $contador_columnas++;
                        if ($contador_columnas == 3) {
                            echo "</tr>";
                            $contador_columnas = 0;
                        }
                        
                    }
                    
                }
                       
                echo "</table>";
                echo "</div>";
            }catch(PDOException $e){

                echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();


            }


            ?>
            
        <a href="../vista/acceso_usuario.php">Volver a mi perfil</a>        
</div>
    </div>
        </div>
            </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> 
    </body>
    </body>
</html>