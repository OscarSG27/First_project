<!DOCTYPE html>
<html>


    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nuevo libro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 

        body {
                background-image: url('../IMG/books.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                width: 100%;
                height: 100%;
            }


            .container_titulo{
                font-family: "Caveat", cursive;
                font-optical-sizing: auto;
                font-weight: 700;
                font-style: normal;
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
                <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Alta de nuevos libros</h2>
            </div>
<?php
//Vamos a conectarnos a la base de datos para hacer el CRUD. Lo haremos con PDO, usando una función previamente creada


include_once '../modelo/funciones.php';



$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);


    if($_SERVER["REQUEST_METHOD"]=="POST"){

       //Recogemos en variables los datos del nuevo libro:

        $nuevo_titulo=isset($_POST["titulo"])?$_POST["titulo"] : null;
        $nuevo_autor=isset($_POST["autor"])?$_POST["autor"] : null;
        $disponible=isset($_POST["disponibilidad"])?$_POST["disponibilidad"] : null;
        $autor_ya_registrado=isset($_POST["autor"])?$_POST["autor"] : null;
        
        
        //Comprobamos si el libro introducido ya existe mediante consulta preparada

       $sql="SELECT * FROM libros WHERE Titulo=:nuevo_titulo";
       $sql_prep=$conexion->prepare($sql);
       $sql_prep->bindParam(":nuevo_titulo",$nuevo_titulo, PDO::PARAM_STR);
      
       
       try{

       $sql_prep->execute();
       

       }catch(PDOException $e){
        echo "<div class='alert alert-danger'>Ha ocurrido un error: </div>".$e->getMessage();
       }

        if($sql_prep->rowCount()>0){

            echo "<div class='alert alert-warning'>Este libro ya se encuentra registrado.</div>";
        }else{
            
            if(!$nuevo_autor|| !$nuevo_titulo|| !$disponible){
        
                echo "<div class='alert alert-danger'>Debes cumplimentar todos los campos</div>";                     // <-También validamos en el servidor
                                                                
            }else{
        
            
/*Hacemos la comprobación de si el usuario administrador está escribiendo la cadena SI/NO en disponibilidad en mayúsculas,
ya que hay una consulta SQL en ver_libros.php que 'tira' de estos caracteres en mayúsculas*/


if(ctype_lower($disponible)){

    echo "<div class='alert alert-warning'>Deberás escribir esto en MAYÚSCULAS</div>";
}else{
     
    if($disponible!=null and $disponible=="SI" || $disponible=="NO"){
    
    //Comprobamos si el autor ya existe en la base de datos

    //Consultas sql para crear un nuevo registro. Dado que tenemos 2 tablas por el INNER JOIN, hacemos 3:

    $registro_sql="INSERT INTO libros (Titulo, Disponibilidad) VALUES (:nuevo_titulo, :disponible)";
    $registro_sql_autor="INSERT INTO autores (Nombre) VALUES (:nuevo_autor)";
    $registro_autores_tabla_libros="UPDATE libros SET Autor=(SELECT id FROM autores WHERE Nombre=:nuevo_autor) WHERE Titulo=:nuevo_titulo";
    //Usamos consultas preparadas para evitar inyecciones de SQL

    $sql_prep_nl=$conexion->prepare($registro_sql);
    $sql_prep_nl_autor=$conexion->prepare($registro_sql_autor);
    $sql_prep_nl->bindParam(':nuevo_titulo', $nuevo_titulo, PDO::PARAM_STR);
    $sql_prep_nl->bindParam(':disponible',$disponible,PDO::PARAM_STR);
    $sql_prep_nl_autor->bindParam(':nuevo_autor', $nuevo_autor,PDO::PARAM_STR);
    $sql_prep_autores_tabla_libros=$conexion->prepare($registro_autores_tabla_libros);
    $sql_prep_autores_tabla_libros->bindParam(':nuevo_autor', $nuevo_autor, PDO::PARAM_STR);
    $sql_prep_autores_tabla_libros->bindParam(':nuevo_titulo', $nuevo_titulo, PDO::PARAM_STR);


    try{

        $sql_prep_nl->execute();

        //Comprobamos si el autor ya existe en la base de datos mediante consulta:

        $sql_autor_bd="SELECT * FROM AUTORES WHERE Nombre=:nuevo_autor";
        $sql_prep_autor_bd=$conexion->prepare($sql_autor_bd);
        $sql_prep_autor_bd->bindParam(':nuevo_autor',$nuevo_autor, PDO::PARAM_STR);
        $sql_prep_autor_bd->execute();

        if($sql_prep_autor_bd->rowCount()>0){ //Si existe, crea el nuevo registro para el libro y lo completa con el registro anterior del autor

            echo "<div class='alert alert-success'>El autor ya existe en la base de datos. No se creará un nuevo registro de autor. Sí el nuevo libro</div>";
            $registro_autor_antiguo="UPDATE libros SET Autor=(SELECT id FROM autores WHERE Nombre=:nuevo_autor) WHERE Titulo=:nuevo_titulo";
            $sql_prep_autor_antiguo=$conexion->prepare($registro_autor_antiguo);
            $sql_prep_autor_antiguo->bindParam('nuevo_autor',$autor_ya_registrado, PDO::PARAM_STR);
            $sql_prep_autor_antiguo->bindParam('nuevo_titulo',$nuevo_titulo, PDO::PARAM_STR);
            $sql_prep_autor_antiguo->execute();
            echo "<div class='alert alert-success'>Libro creado satisfactoriamente</div>";
        }else{ //Si no existe, crea el nuevo autor y lo cumplimenta en el nuevo libro

        $sql_prep_nl_autor->execute();
        $sql_prep_autores_tabla_libros->execute();
        echo "<div class='alert alert-success'>Libro creado satisfactoriamente</div>";}

    }catch(PDOException $e){

        echo "<div class='alert alert-danger>Ha ocurrido un error al crear el libro: </div>". $e->getMessage();
    }


    }else{

        echo "<div class='alert alert-warning'>Revisa si has escrito las palabras SI o NO</div>";
    }

    }
}

}          
    
    }

    ?>

            <form action="alta_nuevo_libro.php" method="POST" style="margin-top:60px; width: fit-content;">
            <div class="mb-3">
                <label class="form-label">Introduce título</label>
                <input type="text" name="titulo"  class="form-control" required>     <!-- <-Validamos en cliente mediante formulario html -->
            </div>
            <div class="mb-3">    
                <label class="form-label">Introduce autor</label>
                <input type="text" name="autor" class="form-control" required>
            </div>
            <div class="mb-3">              
                <label class="form-label">Introduce disponibilidad (Mayúculas SI/NO)</label>
                <input type="text" name="disponibilidad" class="form-control" required>
            </div>     
                <input type="submit" class="btn btn-primary" name="enviar" value="Enviar"><br><br>
                <a href="../vista/perfil_administrador.php">Volver a perfil administrador</a>
            </form>
     
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