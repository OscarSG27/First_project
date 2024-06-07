<!DOCTYPE html>
<html>



    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Borrar cuenta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 

            .container_mensaje{
                
                height: 10vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

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
                header("Location:../vista/inicio.php");
            }
        ?>
<div class="container justify-content-center">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
                <div class="container_titulo">

<?php

// Recogemos en variables la posible decisión de borrar la cuenta del usuario:

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $borrar_cuenta=isset($_POST["opciones"]) ? $_POST["opciones"] : null;
    $usuario_introducido=isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : null;

    if($borrar_cuenta=="si"){

// Hacemos la conexión con la bbdd para poder hacer la modificación, mediante función creada

include_once '../modelo/funciones.php';


$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);

        // consulta preparada

        $sql="DELETE FROM usuarios WHERE usuario=:nombre_usuario";
        $sql_prep=$conexion->prepare($sql);
        $sql_prep->bindParam(":nombre_usuario",$usuario_introducido, PDO::PARAM_STR);
        
        try{
    
        $sql_prep->execute();
        echo "<div class='alert-alert-success'>Cuenta borrada con éxito</div>";
        header("Location: ../vista/despedida.php");
        session_destroy();
    
        }catch(PDOException $e){
            echo "<div class='alert-alert-danger'>Ha ocurrido un error: ".$e->getMessage()."</div>";
        }

    }else{

        header("Location: ../vista/acceso_usuario.php");
    }
}


?>
               
<form action="borrar_cuenta.php" method="POST">

        <label class="form-label">¿Seguro que quieres borrar tu cuenta, <?php echo "<div class='container_mensaje'>".$_SESSION["nombre"]."?</div>"?></label>
        <br><select name="opciones" required><br><br>
            <option value="si">Si</option>
            <option value="no">No</option>
        </select><br><br>
        <input type="submit" name="enviar" value="Enviar"><br><br><br>
        <a href="../vista/acceso_usuario.php">Volver a mi espacio</a>

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