<?php
require_once "../../../conexion.php";

$id = $_POST['id'];
$boca = $_POST['boca'];
$puerta = $_POST['puerta'];
$num_boca = $_POST['num_boca'];
$ugl_boca = $_POST['ugl_boca'];

if ($id && $boca) {
    $stmt = $conn->prepare("UPDATE bocas_atencion SET boca = ?, puerta = ?, num_boca = ?, ugl_boca = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $boca, $puerta, $num_boca, $ugl_boca, $id); // Cambiado a "ssi"

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Boca actualizada exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar la boca."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "No se proporcionaron datos válidos."]);
}

$conn->close();
?>
