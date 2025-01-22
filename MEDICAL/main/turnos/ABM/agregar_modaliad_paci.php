<?php
// Incluir la conexión a la base de datos
include('../../conexion.php');

// Verificar si los parámetros necesarios están presentes
if (isset($_POST['paciente_id']) && isset($_POST['modalidad'])) {
    // Obtener los valores enviados desde el formulario
    $id_paciente = intval($_POST['paciente_id']);
    $modalidad = intval($_POST['modalidad']);
    $fecha = date('Y-m-d'); // Fecha actual

    // Preparar la consulta SQL para insertar la nueva modalidad
    $sql = "INSERT INTO paci_modalidad (id_paciente, modalidad, fecha) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("iis", $id_paciente, $modalidad, $fecha);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Responder con éxito
            echo json_encode(['success' => true, 'message' => 'Modalidad agregada exitosamente.']);
        } else {
            // Responder en caso de error al ejecutar
            echo json_encode(['success' => false, 'message' => 'Error al agregar la modalidad.']);
        }

        // Cerrar la consulta preparada
        $stmt->close();
    } else {
        // Responder en caso de error al preparar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }
} else {
    // Responder si faltan parámetros
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros necesarios.']);
}

// Cerrar la conexión
$conn->close();
?>
