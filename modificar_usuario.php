<!DOCTYPE html>
<html>

<!-- Creamos el espacio reservado para modificar el nombre de usuario, haciendo update en la bbdd --> 

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Modificar usuario</title>
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
        background-image: url('../IMG/cuadernos.jpg');
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



<!-- HTML -->

<div class="container justify-content-center">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
           
            <?php

            // Hacemos la conexión con la bbdd para poder hacer la modificación mediante función ya creada

            include_once '../modelo/funciones.php';



            $conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);

            // Recogemos en una variable el nuevo nombre de usuario:

            if($_SERVER["REQUEST_METHOD"]=="POST"){

            $usuario_introducido=isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : null;
            $nuevo_nombre_usuario=isset($_POST["nombre_usuario_nuevo"]) ? $_POST["nombre_usuario_nuevo"] : null;

            // consulta preparada

                $sql="UPDATE usuarios SET usuario=:nuevo_nombre WHERE usuario=:nombre_usuario";
                $sql_prep=$conexion->prepare($sql);
                $sql_prep->bindParam(":nuevo_nombre",$nuevo_nombre_usuario, PDO::PARAM_STR);
                $sql_prep->bindParam(":nombre_usuario",$usuario_introducido, PDO::PARAM_STR);
                
                try{

                $sql_prep->execute();
      

                }catch(PDOException $e){
                    echo "<div class='alert alert-danger'>Ha ocurrido un error: ".$e->getMessage()."/div>";
                }

                $_SESSION["nombre"]=$nuevo_nombre_usuario;
            }

            ?>              
            <div class="container_titulo">
                <?php echo "<div class='alert alert-info' style='width: 405px;'>Tu nombre de usuario actual es: ".$_SESSION["nombre"]."</div>"; ?>
            </div>    
            <div id="usuario-feedback" class="alert" role="alert" style="display: none; width: 385px;"></div>
                <form id="formulario" action="modificar_usuario.php" method="POST">

                    <label class="form-label"><b>Nuevo nombre de usuario</b></label>
                    <input class="form-control" type="text" id="nombre_usuario_nuevo" name="nombre_usuario_nuevo" required><br><br><br>
                    <input type="submit" onclick=validar() name="enviar" value="Enviar"><br><br><br>
                    <a href="../vista/acceso_usuario.php">Volver a mi espacio</a>

                </form>
            
            </div> 
        </div>
    </div>
</div>         

<script>

//VALIDACIÓN JS

function validar(){

let formulario=document.getElementById("formulario");

//Evaluamos si los datos introducidos pasan la prueba de validación, mediante condicionales

if(formulario.checkValidity()){

    //Si los datos cumplen con las restricciones, salta esta alerta

     alert("Datos correctos. ¡Nombre de usuario modificado!")
}else{

    //Si los datos no cumplen con las restricciones, salta esta alerta

    alert("Datos erróneos. Por favor, cumplimente todos los campos correctamente");
}



}





//AJAX

   document.addEventListener('DOMContentLoaded', function() {
    const usuarioInput = document.getElementById('nombre_usuario_nuevo');
    const feedback = document.getElementById('usuario-feedback');

    usuarioInput.addEventListener('input', function() {
        const usuario = this.value.trim(); // trim() para eliminar espacios en blanco al principio y al final

        if (usuario.length > 0) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {

                            if (usuarioInput.value.trim() === '') {
                                // Si el campo se vació mientras se esperaba la respuesta, no mostrar ningún mensaje
                                feedback.innerHTML = '';
                                feedback.style.display = 'none';
                                feedback.classList.remove('alert-success', 'alert-danger');
                                return;
                            }
                            const data = JSON.parse(xhr.responseText);

                            if (data.exists) {
                                feedback.innerHTML = 'Este usuario ya se encuentra registrado, prueba con otro';
                                feedback.classList.remove('alert-success');
                                feedback.classList.add('alert-danger');
                                feedback.style.display = 'block';
                            } else {
                                feedback.innerHTML = 'Eres el primero en tener este usuario. ¡Qué bien!';
                                feedback.classList.remove('alert-danger');
                                feedback.classList.add('alert-success');
                                feedback.style.display = 'block';
                            }
                        } catch (e) {
                            console.error('Error al parsear JSON:', e);
                        }
                    } else {
                        console.error('Error en la petición:', xhr.status);
                    }
                }
            };
            xhr.open('POST', '../modelo/verificar_usuario.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('usuario=' + encodeURIComponent(usuario));
        } else {
            feedback.innerHTML = '';
            feedback.classList.remove('alert-success', 'alert-danger');
            feedback.style.display = 'none';
        }
    });
});
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> 
</body>
</html>