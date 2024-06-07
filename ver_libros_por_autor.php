<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ver libros por autor</title>
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
                header("Location:../modelo/login.php");
            }
        ?>

<div class="container justify-content-center">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
            <div class="container_titulo">
                <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Encuentra los libros de tu autor favorito</h2>
            </div>
            <form action="ver_libros_por_autor.php" method="POST">
                <label>Introduce el nombre del autor para encontrar sus libros</label>
                <input type="text" class="form-control" name="autor"><br>
                <input type="submit" name="enviar"><br><br>
            </form>
        <?php
            
            //Conectamos con la base de datos mediante función ya creada:

            include_once '../modelo/funciones.php';


            
            $conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);


            //Si recibimos algo a través de POST:
            
            if($_SERVER["REQUEST_METHOD"]=="POST") {

                $autor=isset($_POST["autor"]) ? $_POST["autor"] : null;

                //Hacemos la comprobación sobre si ese autor está en la base de datos

                $sql="SELECT * FROM autores WHERE Nombre=:autor";
                $sql_prep=$conexion->prepare($sql);
                $sql_prep->bindParam(":autor",$autor, PDO::PARAM_STR);
               
                
                try{
         
                $sql_prep->execute();
                
         
                }catch(PDOException $e){
                 echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();
                }
         
             if($sql_prep->rowCount()==0){
         
             echo "<div class='alert alert-danger'>Autor no encontrado en la base de datos</div>";
         
         }else{

            //Hacemos la consulta para mostrar todos los libros de un mismo autor mediante INNER JOIN

            $sql_aut="SELECT libros.id, libros.Titulo, libros.Disponibilidad, autores.Nombre as Autor 
            FROM libros
            INNER JOIN autores on libros.Autor=autores.id
            WHERE autores.Nombre=:autor";
            $sql_prep_aut=$conexion->prepare($sql_aut);
            $sql_prep_aut->bindParam(":autor",$autor, PDO::PARAM_STR);
          
            try{

                $sql_prep_aut->execute();
                if($sql_prep_aut->rowCount()==0){  //Comprbamos si el autor está dado de alta pero no tiene libros
         
                    echo "<div class='alert alert-danger'>Este autor no tiene libros ahora mismo</div>";
                
                }else{
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered'>";
                echo "<tr><th>Id</th><th>Título</th><th>Disponibilidad</th><th>Autor</th>";
                $contador_columnas = 0;
                
                while ($fila = $sql_prep_aut->fetch(PDO::FETCH_ASSOC)) {
                    if ($contador_columnas == 0) {
                        echo "<tr>";
                    }
                
                    foreach ($fila as $clave => $valor) {
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
            }
    

            }catch(PDOException $e){

                echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();
            }
        }       
            
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