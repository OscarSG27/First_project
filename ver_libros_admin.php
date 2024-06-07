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
             header("Location:../modelo/login_administrador.php");
            }?>

<div class="container justify-content-center">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="mt-3"> <!-- Margen superior para separar del borde superior -->
                <div class="container_titulo">
                    <h1 class="fw-bold fs-1 fs-md-5 fs-lg-7">Hola <?php echo $_SESSION["nombre"]?>. Estos son los libros de nuestra biblioteca</h1>
                </div>
               
           
        
        <?php
        

   //Conexión con la base de datos mediante función ya creada
        

       
   include_once '../modelo/funciones.php';


   
   $conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);

            //Mostramos los libros mediante consulta SQL INNER JOIN

            $sql="SELECT libros.id, libros.Titulo, libros.Disponibilidad, autores.Nombre as Autor
            FROM libros
            INNER JOIN autores on libros.autor=autores.id";
            $sql_prep=$conexion->prepare($sql);
            try{

                $sql_prep->execute();
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered'>";
                echo "<tr><th>Id</th><th>Título</th><th>Disponibilidad</th><th>Autor</th>";
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

                echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();


            }


            ?>
            
            <a href="../vista/perfil_administrador.php">Volver al perfil de administrador</a>

</div>
    </div>
        </div>
            </div>
    
</body>
</html>