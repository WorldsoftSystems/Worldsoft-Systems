<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'

    $sql = "DELETE FROM traslados WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "traslado eliminada correctamente";
    } else {
        echo "Error al eliminar la traslado: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
