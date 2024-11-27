<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $forma_presentarse = $_POST['forma_presentarse'];
    $vestimenta = $_POST['vestimenta'];
    $peso = $_POST['peso_psiquiatrico'];
    $grado_actividad = $_POST['grado_actividad'];
    $cualidad_formal = $_POST['cualidad_formal'];
    $pertinente = $_POST['pertinente'];
    $signos_ansiedad = $_POST['signos_ansiedad'];
    $bradilalia = $_POST['bradilalia'];
    $cooperativo = $_POST['cooperativo'];
    $comunicativo = $_POST['comunicativo'];
    $escala_actitudes = $_POST['escala_actitudes'];

    $sql = "UPDATE ex_psiquiatrico SET forma_presentarse = ?, vestimenta = ?, peso = ?, grado_actividad = ?, cualidad_formal = ?, pertinente = ?, signos_ansiedad = ?, bradilalia = ?, cooperativo = ?, comunicativo = ?, escala_actitudes = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssi", $forma_presentarse, $vestimenta, $peso, $grado_actividad, $cualidad_formal, $pertinente, $signos_ansiedad, $bradilalia, $cooperativo, $comunicativo, $escala_actitudes, $id);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Examen psiquiátrico actualizado correctamente."));
        exit();  // Asegúrate de detener la ejecución del script
    } else {
        echo "Error al actualizar el examen psiquiátrico: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
