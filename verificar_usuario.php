<?php
header('Content-Type: application/json'); // Indico que la respuesta debe ser en formato JSON
header('X-Requested-With: XMLHttpRequest'); // Indico que la solicitud se hace mediante AJAX

// Incluye el archivo de funciones para obtener la conexión a la base de datos
include_once 'funciones.php';
$conexion = conexion_bd($host, $dbname, $usuario, $contrasenia);

// Obtiene el usuario enviado por POST
$nuevo_nombre_usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : (isset($_POST["nombre_usuario_nuevo"]) ? $_POST["nombre_usuario_nuevo"] : null);

$sql_usuarios_registrados = "SELECT * FROM usuarios WHERE usuario = :nombre_usuario";
$sql_prep_usu = $conexion->prepare($sql_usuarios_registrados);
$sql_prep_usu->bindParam(":nombre_usuario", $nuevo_nombre_usuario, PDO::PARAM_STR);

try {
    $sql_prep_usu->execute();
    $resultado = $sql_prep_usu->fetch(PDO::FETCH_ASSOC);

    // Devuelve el resultado como JSON
    if ($resultado) {
        echo json_encode(array("exists" => true));
    } else {
        echo json_encode(array("exists" => false));
    }
} catch (PDOException $e) {
    echo json_encode(array("error" => "Ha ocurrido un error: " . $e->getMessage()));
}
?>

