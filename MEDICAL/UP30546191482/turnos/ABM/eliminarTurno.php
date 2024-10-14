<?php
require_once "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'

    $sql = "DELETE FROM turnos WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success"; // Cambia la respuesta a "success"
    } else {
        echo "Error al eliminar el turno: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
