<?php
require_once "../../../conexion.php";

$boca = $_POST['boca'];
$puerta = $_POST['puerta'];
$num_boca = $_POST['num_boca'];
$ugl_boca = $_POST['ugl_boca'];

if ($boca) {
    $stmt = $conn->prepare("INSERT INTO bocas_atencion (boca, puerta, num_boca, ugl_boca) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $boca, $puerta, $num_boca, $ugl_boca); // Verifica los tipos de datos

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Boca agregada exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al agregar la boca."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "No se proporcionó un nombre válido."]);
}

$conn->close();
?>
