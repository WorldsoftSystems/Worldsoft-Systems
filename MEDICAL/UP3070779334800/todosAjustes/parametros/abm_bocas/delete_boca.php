<?php
require_once "../../../conexion.php";

$id = $_POST['id'];

if ($id) {
    $stmt = $conn->prepare("DELETE FROM bocas_atencion WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Boca eliminada exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar la boca."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "No se proporcionó un ID válido."]);
}

$conn->close();
?>
