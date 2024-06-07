<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inicio</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
    

        <style> 

            .container_mensaje{
                
                height: 15vh;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: -2.5em;
                margin-left: -10em;
                
            }
            
            body {
                background-image: url('../IMG/libros.jpg');
                background-size: cover;
                background-position: center;
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

//Vamos a conectarnos a la base de datos para hacer el CRUD. Lo haremos con PDO mediante función ya creada


include_once 'funciones.php';



$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);

/*Ahora recibiremos los datos de los distintos formularios para comparar con los usuarios guardados en la base de datos,
insertar nuevos o comparar si el usuario introducido es un administrador de la biblioteca */ 


// Login y validación de que no es perfil de administrador

?>


<!-- HTML -->

<div class="container justify-content-center">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
        <div class="container_titulo">
          <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">BIENVENID@ A BIBLIOTECA</h2>
          <p class="fs-3 fs-md-6 fs-lg-7">¿HABLAMOS DE FÚTBOL?</p>
        </div> 
        <?php
            if($_SERVER["REQUEST_METHOD"]=="POST"){

                $usuario_introducido=isset($_POST["usuario"]) ? $_POST["usuario"] : null;
                $contraseña_introducida=isset($_POST["contraseña"] ) ? $_POST["contraseña"] : null;
                
                if(!$usuario_introducido || !$contraseña_introducida){
                
                    echo "<div class='container_mensaje'>
                           <div class='alert alert-danger' role='alert'> 
                            Debes cumplimentar todos los campos
                            </div>
                          </div>";   // También validamos en el servidor
                }
                
                
                //Ahora vamos a hacer la comprobación de si el usuario existe en la base de datos. Si es así, tendrá acceso al perfil de usuarios
                
                       // consulta preparada
                
                       $sql="SELECT * FROM usuarios WHERE usuario=:nombre_usuario AND contrasenia=:contrasenia";
                       $sql_prep=$conexion->prepare($sql);
                       $sql_prep->bindParam(":nombre_usuario",$usuario_introducido, PDO::PARAM_STR);
                       $sql_prep->bindParam(":contrasenia",$contraseña_introducida, PDO::PARAM_STR);
                       
                       try{
                
                       $sql_prep->execute();
                       
                
                       }catch(PDOException $e){
                        echo "<div class='alert alert-danger' role='alert' align='center'>Ha ocurrido un error: </div>".$e->getMessage();
                       }
                
                       
                       if($sql_prep->rowCount()>0 and $usuario_introducido!="administrador"){
                       
                           //Inicio de la sesión
                
                           session_start();
                           $_SESSION["nombre"]=$usuario_introducido;
                         
                           header("Location:../vista/acceso_usuario.php");
                           
                       }else if($usuario_introducido=="administrador"){
                
                        echo "<div class='container_mensaje'>
                                <div class='alert alert-danger' role='alert'>
                                    Datos erróneos. Si eres administrador, dirígete al campo reservado.
                                </div>
                              </div>"; /* Validamos si un administrador está intentando entrar o el usuario es erróneo o no dado de alta
                                                                                                                                            mediante el acceso de usuarios*/
                    }else if($sql_prep->rowCount()==0){
                
                        echo "<div class='container_mensaje'>
                                <div class='alert alert-danger' role='alert'>
                                    Datos erróneos. Si eres administrador, dirígete al campo reservado.
                                </div>
                            </div>";
                }
                }
                ?>
        
          
            <form action="login.php" method="POST" style="margin-top:60px; text-align: center; width: fit-content;">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario"  required>
                </div>    
            
                <div class="mb-3">
                    <label  class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                </div>

                <div class="mb-3">
                    <label class="form-check-label" for="recordar">Recordar usuario</label>
                    <input type="checkbox" class="form-check-input" id="recordar" name="recordar">
                </div>

                <br><button type="submit" class="btn btn-primary" name="enviar">Enviar</button><br><br>
                <a href="nuevo_usuario.php">Soy nuevo, quiero registarme</a><br><br>
                <a href="login_administrador.php">Soy trabajador de la biblioteca. Login aquí</a>
            </form>
        </div> 
    </div>          
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const recordarCheckbox = document.getElementById('recordar');
            const usuarioInput = document.getElementById('usuario');

            const savedUsuario = getCookie('usuario');
            if (savedUsuario) {
                usuarioInput.value = savedUsuario;
                recordarCheckbox.checked = true;
            }

            
            document.querySelector('form').addEventListener('submit', function () {
                if (recordarCheckbox.checked) {
                    setCookie('usuario', usuarioInput.value, 30);
                } else {
                    deleteCookie('usuario');
                }
            });

            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    const date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            }

            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            function deleteCookie(name) {
                document.cookie = name + '=; Max-Age=-99999999;';
            }
        });
    </script>
          

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>