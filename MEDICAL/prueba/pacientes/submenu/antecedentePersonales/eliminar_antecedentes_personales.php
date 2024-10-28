<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // ID del registro a eliminar (entero)

    // Crear la consulta SQL para eliminar
    $sql = "DELETE FROM antecedentes_personales WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros (i = integer)
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Antecedentes personales eliminados correctamente."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al eliminar antecedentes personales: " . $stmt->error));
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
