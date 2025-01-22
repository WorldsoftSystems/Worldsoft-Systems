<?php
// Incluir la conexión a la base de datos
require_once "../../../conexion.php";

// Verificar si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar los datos enviados desde el formulario
    $idPaciente = $_POST['id_paciente'];
    $fechaPago = $_POST['pagoFechas'];
    $idProfesional = $_POST['pagoProfesional'];
    $actividad = $_POST['pagoActividad'];
    $montoPago = $_POST['pagoCantidad'];
    $montoTotal = $_POST['pagoTotal'];
    $metodoPago = isset($_POST['pagoMetodo']) ? $_POST['pagoMetodo'] : null;
    $obs = isset($_POST['pagoObs']) ? $_POST['pagoObs'] : null;

    // Validar datos obligatorios
    if (empty($idPaciente) || empty($fechaPago) || empty($idProfesional) || empty($actividad) || empty($montoPago) || empty($montoTotal)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Todos los campos obligatorios deben completarse.'
        ]);
        exit;
    }

    // Preparar la consulta SQL para insertar un nuevo pago
    $sql = "INSERT INTO pagos (id_paciente, fecha_pago, id_profesional, actividad, monto_pago, monto_total, metodo_pago, obs) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular los parámetros
        $stmt->bind_param("isisddss", $idPaciente, $fechaPago, $idProfesional, $actividad, $montoPago, $montoTotal, $metodoPago, $obs);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pago registrado exitosamente.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al registrar el pago. Inténtelo de nuevo.'
            ]);
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al preparar la consulta SQL.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de solicitud no válido.'
    ]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
