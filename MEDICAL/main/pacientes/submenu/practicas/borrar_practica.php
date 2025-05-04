<?php
require_once "../../../conexion.php";

header('Content-Type: application/json'); // 🔥 Muy importante: decirle al navegador que es JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM practicas WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Práctica eliminada correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al eliminar la práctica: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
