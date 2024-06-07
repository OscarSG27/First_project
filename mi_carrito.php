<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mi bolsa de libros</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Pacifico&display=swap" rel="stylesheet">
        <style> 
            body {
                background-image: url('../IMG/bolsa.jpg');
                background-size: cover;
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
                <div class="mt-5"> <!-- Margen superior para separar del borde superior -->
                    <div class="container_titulo">
                        <h2 class="fw-bold fs-1 fs-md-5 fs-lg-7" style="margin-bottom: 2em">Aquí tienes tu bolsa de libros <?php echo $_SESSION["nombre"]?></h2>
                    </div>
                <?php

                if(!isset($_SESSION["carrito"])){

                    echo "<div class='alert alert-info'>No hay libros en la bolsa</div>";
                }else{
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered'>";
                    echo "<tr><th>Títulos</th></tr>";
                    foreach($_SESSION["carrito"] as $libro){
                        echo "<tr><td>$libro</td></tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                    
                }
            
                ?>
                <br><br>
               
                <a href="../vista/acceso_usuario.php">Volver a mi perfil</a>

            </div>                        
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> 
    </body>
</html>