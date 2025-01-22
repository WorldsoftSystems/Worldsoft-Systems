<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $patologias = $_POST['patologias'];
    $indicacion_dieta = $_POST['indicacion_dieta'];
    $actitud_comida = $_POST['actitud_comida'];
    $peso = $_POST['peso'];
    $talla = $_POST['talla'];
    $imc = $_POST['imc'];
    $requiere = $_POST['requiere'];
    $no_requiere = $_POST['no_requiere'];
    $especificar = $_POST['especificar'];

    $sql = "UPDATE nutricion SET patologias = ?, indicacion_dieta = ?, actitud_comida = ?, peso = ?, talla = ?, imc = ?, requiere = ?, no_requiere = ?, especificar = ?  WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $patologias,$indicacion_dieta,$actitud_comida,$peso,$talla,$imc,$requiere,$no_requiere,$especificar, $id);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Nutrición actualizada correctamente."));
        exit();  // Asegúrate de detener la ejecución del script
        
    } else {
        echo "Error al actualizar nutrcion: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>