<?php
require_once "../../../conexion.php";

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores enviados en la solicitud POST
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $modalidad = $_POST['modalidad_paci'];

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        // Enviar respuesta en formato JSON si la conexión falla
        echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
        exit;
    }

    // Preparar la consulta SQL para actualizar la modalidad
    $sql = "UPDATE paci_modalidad SET fecha = ?, modalidad = ? WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param("sii", $fecha, $modalidad, $id);

    // Ejecutar la consulta y verificar si se ejecutó correctamente
    if ($stmt->execute()) {
        // Respuesta exitosa en formato JSON
        echo json_encode(['success' => true, 'message' => 'Modalidad actualizada correctamente.']);
    } else {
        // Respuesta en caso de error al ejecutar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la modalidad: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Respuesta en caso de que no se reciba una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
