<?php
// Incluir la conexión a la base de datos
require_once "../../../conexion.php";

// Verificar si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar los datos enviados desde el formulario
    $idPago = $_POST['id'];
    $idPaciente = $_POST['id_paciente'];
    $fechaPago = $_POST['pagoFechas'];
    $idProfesional = $_POST['pagoProfesional'];
    $actividad = $_POST['pagoActividad'];
    $montoPago = $_POST['pagoCantidad'];
    $montoTotal = $_POST['pagoTotal'];
    $metodoPago = isset($_POST['pagoMetodo']) ? $_POST['pagoMetodo'] : null;
    $obs = isset($_POST['pagoObs']) ? $_POST['pagoObs'] : null;

    // Validar datos obligatorios
    if (empty($idPago) || empty($idPaciente) || empty($fechaPago) || empty($idProfesional) || empty($actividad) || empty($montoPago) || empty($montoTotal)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Todos los campos obligatorios deben completarse.'
        ]);
        exit;
    }

    // Preparar la consulta SQL para actualizar el pago
    $sql = "UPDATE pagos 
            SET id_paciente = ?, fecha_pago = ?, id_profesional = ?, actividad = ?, monto_pago = ?, monto_total = ?, metodo_pago = ?, obs = ? 
            WHERE id_pago = ?";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular los parámetros
        $stmt->bind_param("isisddssi", $idPaciente, $fechaPago, $idProfesional, $actividad, $montoPago, $montoTotal, $metodoPago, $obs, $idPago);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pago actualizado exitosamente.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al actualizar el pago. Inténtelo de nuevo.'
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
