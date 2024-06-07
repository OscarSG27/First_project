<!DOCTYPE html>
<html>

<!-- Creamos login de nuevo usuario para despues añadir estos nuevos datos a la base de datos. Hay un formulario por cada color de fondo --> 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
    <style> 
        body {
            background-image: url('../IMG/libros.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
        }

        .container_form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.7em;
        }

        .container_mensaje{
            height: 15vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: -2.5em;
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
//Vamos a conectarnos a la base de datos para hacer el CRUD. Lo haremos con PDO, mediante función ya creada
include_once 'funciones.php';
$conexion=conexion_bd($host,$dbname,$usuario,$contrasenia);
?>

<!-- HTML -->
<div class="container justify-content-center">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
                <div class="container_titulo">
                    <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">NUEVO USUARIO DE BIBLIOTECA</h2>
                    <p class="fs-3 fs-md-6 fs-lg-7">¿HABLAMOS DE FÚTBOL?</p>
                </div> 
                <div id="usuario-feedback" class="alert" role="alert" style="display: none; width: 385px;"></div>

                <?php        
                if($_SERVER["REQUEST_METHOD"]=="POST"){
                    $nombre=isset($_POST["nombre"]) ? $_POST["nombre"] : null;
                    $apellidos=isset($_POST["apellidos"]) ? $_POST["apellidos"] : null;
                    $usuario_introducido=isset($_POST["usuario"]) ? $_POST["usuario"] : null;
                    $contraseña_introducida=isset($_POST["contraseña"] ) ? $_POST["contraseña"] : null;
                    $contraseña_confirmada=isset($_POST["contraseña_confirmada"] ) ? $_POST["contraseña_confirmada"] : null;
                    
                    //Comprobamos si las contraseñas coinciden
                    if($contraseña_introducida!= $contraseña_confirmada){
   
                    }else{
                        if(!$usuario_introducido || !$contraseña_introducida || !$nombre || !$apellidos){
                            echo "<div class='container_mensaje'>
                                    <div class='alert alert-danger' role='alert' align='center'>
                                        Debes cumplimentar todos los campos
                                    </div>    
                                  </div>"; // <-También validamos en el servidor                                
                        }else{
                            //Consulta sql para crear un nuevo registro
                            $registro_sql="INSERT INTO usuarios (Nombre, Apellidos, usuario, contrasenia) VALUES (:nombre, :apellidos, :nombre_usuario, :contrasenia)";
                            //Usamos consultas preparadas para evitar inyecciones de SQL
                            $sql_prep=$conexion->prepare($registro_sql);
                            $sql_prep->bindParam(':nombre', $nombre,PDO::PARAM_STR);
                            $sql_prep->bindParam(':apellidos', $apellidos,PDO::PARAM_STR);
                            $sql_prep->bindParam(':nombre_usuario',$usuario_introducido,PDO::PARAM_STR);
                            $sql_prep->bindParam(':contrasenia', $contraseña_confirmada,PDO::PARAM_STR);

                            try {
                                $sql_prep->execute();

                            } catch(PDOException $e) {
                                echo "<div class='alert alert-danger' role='alert'>
                                        Ha ocurrido un error al crear el usuario: " . $e->getMessage() . "
                                      </div>";
                            }
                        }
                    }
                }        
                ?>
                <div class="d-flex justify-content-center">

                    </div>
                        <form  id="formulario" action="nuevo_usuario.php" method="POST" style="margin-top:35px; text-align: center; width: fit-content;">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" pattern="[A-Za-z\s]+" id="nombre" name="nombre" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" pattern="[A-Za-z\s]+" id="apellidos" name="apellidos" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>    
                        
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirma Contraseña</label>
                                <input type="password" class="form-control" id="contraseña_confirmada" name="contraseña_confirmada" required>
                            </div>

                            <br><button type="submit" onclick=validar() class="btn btn-primary" name="enviar">Enviar</button><br>
                        </form>
                </div> 
                
                <a href="login.php">Volver a Inicio</a>


<script>

//Función validar con checkValidity en JS

//Vamos a crear una función para validar todos los campos del formulario, mediante el método checkValidity()

function validar(){

let formulario=document.getElementById("formulario");
let contraseña = document.getElementById("contraseña").value;
let contraseña_confirmada = document.getElementById("contraseña_confirmada").value;

if (!formulario.checkValidity()) {
        alert("Datos erróneos. Por favor, cumplimente todos los campos correctamente");
        return false;
    }

    if (contraseña !== contraseña_confirmada) {
        alert("Las contraseñas no coinciden");
        return false;
    }

    alert("Datos correctos. Usuario registrado con éxito. ¡Bienvenid@!");
    return true;
}
 

//Petición AJAX
document.addEventListener('DOMContentLoaded', function() {
    const usuarioInput = document.getElementById('usuario');
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
                                feedback.classList.remove('alert-info');
                                feedback.classList.add('alert-danger');
                                feedback.style.display = 'block';
                            } else {
                                feedback.innerHTML = 'Eres el primero en tener este usuario. ¡Qué bien!';
                                feedback.classList.remove('alert-danger');
                                feedback.classList.add('alert-info');
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
            xhr.open('POST', 'verificar_usuario.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('usuario=' + encodeURIComponent(usuario));
        } else {
            feedback.innerHTML = '';
            feedback.classList.remove('alert-info', 'alert-danger');
            feedback.style.display = 'none';
        }
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>   
            </div>
        </div>
    </div>
</div>
</body>
</html>
