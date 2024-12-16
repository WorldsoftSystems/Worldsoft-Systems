<?php
require_once "../../../conexion.php";

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores enviados en la solicitud POST
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $diag = $_POST['egreso_diag'];
    $modalidad = $_POST['egreso_modalidad'];
    $hora_egreso = $_POST['hora_egreso'];
    
    // Si el campo 'motivo' está vacío, se guarda como NULL
    $motivo = !empty($_POST['egreso_motivo']) ? $_POST['egreso_motivo'] : NULL;

    // Preparar la consulta SQL
    $sql = "UPDATE egresos SET fecha_egreso = ?, diag = ?, modalidad = ?, motivo = ?, hora_egreso = ? WHERE id = ?";

    // Preparar la consulta con `prepare`
    $stmt = $conn->prepare($sql);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param("siiisi", $fecha, $diag, $modalidad, $motivo, $hora_egreso, $id);

    // Ejecutar la consulta y verificar si se ejecutó correctamente
    if ($stmt->execute()) {
        // Respuesta exitosa en formato JSON
        echo json_encode(['success' => true, 'message' => 'Egreso actualizado correctamente']);
    } else {
        // Respuesta en caso de error al ejecutar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al actualizar egreso: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Respuesta en caso de que no se reciba una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
