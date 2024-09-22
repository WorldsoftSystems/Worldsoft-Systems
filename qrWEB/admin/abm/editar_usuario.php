<?php
include('../../conect.php');

// Revisar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $tabla = $_POST['tabla'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $cant_qr = $_POST['cant_qr'];

    // Actualizar el usuario
    $sql = "UPDATE $tabla SET user='$user', password='$password', cant_qr='$cant_qr' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Usuario actualizado exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conexion->error]);
    }

    $conexion->close();
}
?>