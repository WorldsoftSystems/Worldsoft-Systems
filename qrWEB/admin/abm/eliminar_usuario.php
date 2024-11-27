<?php
include ('../../conect.php');

// Revisar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['delete_id'];
    $tabla = $_POST['tabla'];

    // Eliminar el usuario
    $sql = "DELETE FROM $tabla WHERE id=$id";

    header('Content-Type: application/json');

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Usuario eliminado exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conexion->error]);
    }

    $conexion->close();
    exit; // Asegúrate de agregar esto
}
?>
