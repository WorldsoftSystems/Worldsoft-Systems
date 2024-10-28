<?php
require_once "../../../conexion.php";

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores enviados en la solicitud POST
    $idPaciente = $_POST['id_paciente'];
    $fecha = $_POST['fecha'];
    $modalidad = $_POST['modalidad_paci'];

    // Verificar si la conexión se estableció correctamente
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
        exit;
    }

    // Consulta para verificar la última modalidad y el último egreso del paciente
    $sqlCheck = "SELECT 
            MAX(m.fecha) AS fecha_modalidad,
            MAX(e.fecha_egreso) AS fecha_egreso
        FROM paci_modalidad m
        LEFT JOIN egresos e ON m.id_paciente = e.id_paciente
        WHERE m.id_paciente = ?
    ";

    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param('i', $idPaciente);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $lastRecord = $resultCheck->fetch_assoc();

    // Validar que la fecha de la nueva modalidad sea mayor a la última fecha de modalidad registrada
    if ($lastRecord && $fecha <= $lastRecord['fecha_modalidad']) {
        echo json_encode(['success' => false, 'message' => 'La fecha de la nueva modalidad debe ser mayor que la fecha de la última modalidad registrada.']);
        exit;
    }

    // Validar que la fecha de la nueva modalidad sea mayor a la última fecha de egreso registrada
    if ($lastRecord && $fecha <= $lastRecord['fecha_egreso']) {
        echo json_encode(['success' => false, 'message' => 'La fecha de la nueva modalidad debe ser mayor que la fecha del último egreso registrado.']);
        exit;
    }

    // Preparar la consulta SQL para insertar la nueva modalidad
    $sql = "INSERT INTO paci_modalidad (id_paciente, fecha, modalidad) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param('isi', $idPaciente, $fecha, $modalidad);

    // Ejecutar la consulta y verificar si se ejecutó correctamente
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Modalidad agregada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar modalidad: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmtCheck->close();
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>