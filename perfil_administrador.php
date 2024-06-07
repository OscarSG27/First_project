<!DOCTYPE html>
<html>

<!-- Creamos el espacio reservado para cada tipo de usuario (usuario normal o administrador) --> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sesión iniciada</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/b2c128f13d.js" crossorigin="anonymous"></script>

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
            height: 100%;
        }

        .container_titulo{
            font-family: "Caveat", cursive;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
            color: black;
            font-size: 10em;
            margin-top: 8vh;
           
        }

        a i{
            color: black;
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
                header("Location:../modelo/login_administrador.php");
            }



        ?>
<div>        
    <div class="container justify-content-center">       
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-8">
                <div class="container_titulo">
                    <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7">Estás viendo el espacio de administradores</h2>        
                </div>
                <div class="mt-5">
                    <div class="row">
                        <div style="margin-bottom: 10vw; margin-top: 1vw;">
                            <div class="col-12" style="margin-bottom: 1vh;">
                                <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/modificar_libros.php">
                                    <i class="fa-solid fa-square-pen"></i>
                                    Modificar libros
                                </a>
                            </div>
                            <div class="col-12" style="margin-bottom: 1vh;">    
                                <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/alta_nuevo_libro.php">
                                    <i class="fa-solid fa-plus"></i>
                                    Alta de nuevo libro
                                </a>
                            </div>    
                            <div class="col-12" style="margin-bottom: 1vh;">
                                <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/borrar_libros.php">
                                    <i class="fa-solid fa-trash-can"></i>
                                    Borrar libros
                                </a>
                            </div>
                            <div class="col-12" style="margin-bottom: 1vh;">
                                <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/ver_libros_admin.php">
                                    <i class="fa-regular fa-eye"></i>
                                    Ver libros
                                </a>
                            </div> 
                            <div class="col-12" style="margin-bottom: 1vh;">   
                                <a class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/borrar_usuario.php">
                                    <i class="fa-solid fa-user-slash"></i>
                                    Eliminar usuario bliblioteca
                                </a>
                            </div>
                            <div class="col-12" style="margin-bottom: 1vh;">
                                <a  class="link-offset-2 link-underline link-underline-opacity-0" href="../controlador/ver_usuarios.php">
                                    <i class="fa-solid fa-user"></i>
                                    Ver los usuarios de la biblioteca
                                </a>
                            </div>
                        </div>
                    </div>   
                </div>
                <form action="perfil_administrador.php" method="POST">
                    <div style="margin-top:-5vw;">
                        <input type="submit" class="btn btn-secondary" value="Cerrar Sesión" name="cerrar_sesion">
                    </div>    
                </form> 
            </div>
        </div>   
    </div>                  
</div>



    
    </body>
</html>