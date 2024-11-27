<?php
include ('../../conect.php');

// Revisar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tabla = $_POST['tabla'];
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Insertar un nuevo usuario
    $sql = "INSERT INTO $tabla (user, password, cant_qr) VALUES ('$user', '$password', '0')";
    
    if ($conexion->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Usuario agregado exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conexion->error]);
    }

    $conexion->close();
}
?>
