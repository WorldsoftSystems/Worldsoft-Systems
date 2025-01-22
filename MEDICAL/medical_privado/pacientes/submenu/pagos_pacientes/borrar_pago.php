<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'

    $sql = "DELETE FROM pagos WHERE id_pago = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Pago eliminada correctamente";
    } else {
        echo "Error al eliminar el pago: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
