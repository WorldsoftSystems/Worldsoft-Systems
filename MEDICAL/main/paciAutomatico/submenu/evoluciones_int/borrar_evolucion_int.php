<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'

    $sql = "DELETE FROM evoluciones_int WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Evolucion eliminada correctamente";
    } else {
        echo "Error al eliminar la evolucion: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
