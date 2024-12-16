<?php
require_once "../../../conexion.php";

header('Content-Type: application/json'); // Indicar que la respuesta será en JSON

$response = array(); // Array para almacenar la respuesta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Decodificar el JSON de 'fechas' y verificar que se decodificó correctamente
    $fechas = json_decode($_POST['fechas'], true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($fechas) && !empty($fechas)) {
        $fecha = $fechas[0]; // Tomar la primera fecha del array decodificado
    } else {
        $response['status'] = 'error';
        $response['message'] = "No se recibió un formato de fechas válido o el array está vacío.";
        echo json_encode($response);
        exit;
    }

    $hora = $_POST['hora'];
    $profesional = $_POST['profesional'];
    $actividad = $_POST['actividad'];
    $cant = $_POST['cant'];

    $sql = "UPDATE practicas SET fecha = ?, hora = ?, profesional = ?, actividad = ?, cant = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisi", $fecha, $hora, $profesional, $actividad, $cant, $id);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = "Práctica actualizada correctamente";
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error al actualizar la práctica: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}
?>
