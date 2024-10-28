<?php
// Conectar a la base de datos
include('../../conexion.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$id = $_POST['turno_id'];
$id_prof = $_POST['id_prof_edit'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$paciente = $_POST['paciente_id_edit'];
$motivo = $_POST['motivo'];
$llego = $_POST['llego'];
$atendido = $_POST['atendido'];
$observaciones = $_POST['observaciones'];

// Convertir la fecha al formato YYYY-MM-DD
$date = DateTime::createFromFormat('d/m/Y', $fecha);
$fechaFormateada = $date ? $date->format('Y-m-d') : null;

// Actualizar el turno en la base de datos
$stmt = $conn->prepare("UPDATE turnos SET fecha = ?, hora = ?, paciente = ?, id_prof = ?, motivo = ?, llego = ?, atendido = ?, observaciones = ? WHERE id = ?");
$stmt->bind_param("ssiiisssi", $fechaFormateada, $hora, $paciente, $id_prof, $motivo, $llego, $atendido, $observaciones, $id);

if ($stmt->execute()) {
    echo "Turno actualizado exitosamente";

    // Insertar en la tabla practicas si llego y atendido son ambos "SI"
    if ($llego === 'SI' && $atendido === 'SI') {
        $cant_t = 1;

        // Verificar si ya existe una práctica con la misma fecha, hora y motivo
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM practicas WHERE id_paciente = ? AND fecha = ? AND hora = ? AND actividad = ?");
        $check_stmt->bind_param("issi", $paciente, $fechaFormateada, $hora, $motivo);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        // Si no hay duplicado, insertar en practicas
        if ($count == 0) {
            $insert_stmt = $conn->prepare("INSERT INTO practicas (id_paciente, fecha, hora, profesional, actividad, cant) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("issiii", $paciente, $fechaFormateada, $hora, $id_prof, $motivo, $cant_t);

            if ($insert_stmt->execute()) {
                echo "Registro insertado en la tabla practicas.";
            } else {
                echo "Error al insertar en practicas: " . $insert_stmt->error;
            }

            // Cerrar la declaración de inserción
            $insert_stmt->close();
        } else {
            echo "La práctica ya existe en la tabla practicas.";
        }
    }
} else {
    echo "Error al actualizar el turno: " . $stmt->error;
}

// Cerrar las conexiones
$stmt->close();
$conn->close();
?>
