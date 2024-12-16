<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Leer el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id'])) {
        $id_disponibilidad = intval($data['id']);

        // Ejecutar la consulta de eliminación
        $sql = "DELETE FROM disponibilidad WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_disponibilidad);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Disponibilidad eliminada correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al eliminar la disponibilidad: ' . $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'No se recibió un ID válido.']);
    }

    $conn->close();
    exit();
}

echo json_encode(['error' => 'Solicitud inválida.']);
exit();
?>
