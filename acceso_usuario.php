<!DOCTYPE html>
<html>

<!-- Creamos el espacio reservado para cada tipo de usuario (usuario normal o administrador) --> 

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/b2c128f13d.js" crossorigin="anonymous"></script>

        <title>Sesión iniciada</title>
        
        <style> 
        body {
            background-image: url('../IMG/libro_blanco.png');
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
            color: black;
            font-size: 10em;
            text-align: center;
            margin-top: 15vh;
           
        }

        a i{
            color: black;
        }

        a {
            cursor: pointer; /* Asegura que el cursor cambie a mano */
        }
        </style>
    </head>
    <body>
    <?php
            session_start();
            if(!isset($_SESSION["nombre"])){
                header("Location:../modelo/login.php");
            }
    

            if(isset($_POST["cerrar_sesion"])){

                session_destroy();
                header("Location:../modelo/login.php");
            }
        ?>

<div class="container justify-content-center">       
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-8">
            <div class="container_titulo">
                <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Bienvenid@ a tu espacio personal <?php echo $_SESSION["nombre"] ?></h2>        
            </div>
            <div class="mt-5">
                <div class="row">
                    <div style="margin-left: 10vw; margin-bottom: 10vw; margin-top: 1vw;">
                        <div class="col-12" style="margin-bottom: 1vh;">
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/modificar_usuario.php">
                            <i class="fa-solid fa-pen-to-square"></i>
                                Modificar nombre de usuario
                            </a>
                        </div>
                        <div class="col-12" style="margin-bottom: 1vh;">    
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/modificar_contraseña.php">
                            <i class="fa-solid fa-lock"></i>
                            Modificar contraseña
                            </a>
                        </div>
                        <div class="col-12" style="margin-bottom: 1vh;">    
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/borrar_cuenta.php">
                            <i class="fa-solid fa-user-xmark"></i>
                            Eliminar mi cuenta 
                            </a> 
                        </div>
                        <div class="col-12" style="margin-bottom: 1vh;"> 
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/ver_datos.php">
                            <i class="fa-solid fa-eye"></i>
                            Ver mis datos de usuario 
                            </a>
                        </div>    
                        <div class="col-12" style="margin-bottom: 1vh;">   
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/ver_libros.php">
                            <i class="fa-solid fa-book"></i>
                            Ver libros de la biblioteca 
                            </a>
                        </div>
                        <div class="col-12" style="margin-bottom: 1vh;"> 
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/mi_carrito.php">
                            <i class="fa-solid fa-bag-shopping"></i>
                            Mis libros seleccionados  
                            </a>
                        </div>
                        <div class="col-12" style="margin-bottom: 1vh;"> 
                            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/ver_libros_por_autor.php">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Buscar libros por autor
                            </a>
                        </div>

                        <div class="col-12" style="margin-bottom: 1vh;"> 
                            <a class="link-offset-2 link-underline link-underline-opacity-0" onclick=abrir_ventana() id="rae">
                            <i class="fa-solid fa-spell-check"></i>
                            Ir a la RAE
                            </a>
                        </div>
                        
                    </div>
                </div>    
            </div>
            <form action="acceso_usuario.php" method="POST">
                <div style="margin-left: 10vw; margin-top:-5vw;">
                    <input type="submit" class="btn btn-secondary" value="Cerrar Sesión" name="cerrar_sesion">
                </div>
            </form>
        </div>
    </div>
</div>


<script>

        //Creo la variable que guardará  la ventana flotante

        let ventana_flotante;

        //Creo la función que abre la ventana flotante de manera manual, con anchura del cuadro de 400 y altura de 400, usando window

        function abrir_ventana(){
        
            ventana_flotante=window.open("https://www.rae.es", "_blank", "width=800,height=800");
            
        }
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> 
    
    </body>
</html>