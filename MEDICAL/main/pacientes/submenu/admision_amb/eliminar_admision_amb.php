<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // ID del registro a eliminar

    // Crear la consulta SQL para eliminar
    $sql = "DELETE FROM hc_admision_ambulatorio WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro (i = integer)
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Admision eliminada correctamente."));
        exit();
    } else {
        echo "Error al eliminar antecedentes familiares: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
