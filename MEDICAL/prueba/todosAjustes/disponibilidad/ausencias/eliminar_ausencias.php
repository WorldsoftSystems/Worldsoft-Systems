<?php
require_once "../../../conexion.php";

// Obtiene los datos JSON enviados
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo "Error: ID de la ausencia no proporcionado.";
    exit;
}

// Elimina la ausencia
$query = "DELETE FROM ausencias WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Ausencia eliminada correctamente.";
} else {
    echo "Error al eliminar la ausencia: " . $stmt->error;
}
?>
