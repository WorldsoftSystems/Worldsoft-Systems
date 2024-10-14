<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Si no hay id, asumir que es 0 (nuevo registro)
    $id_prof = $_POST['id_prof'];

    // Obtener los días seleccionados como un array
    $dias_seleccionados = array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado");

    // Iterar sobre los días seleccionados
    foreach ($dias_seleccionados as $dia) {
        // Verificar si se han proporcionado valores de hora de inicio y fin para este día
        // Verificar si los campos están establecidos y no están vacíos
        if (
            isset($_POST['horario_inicio_' . $dia]) && $_POST['horario_inicio_' . $dia] !== "" &&
            isset($_POST['horario_fin_' . $dia]) && $_POST['horario_fin_' . $dia] !== ""
        ) {

            // Obtener los valores de hora de inicio y fin para este día
            $hora_inicio = $_POST['horario_inicio_' . $dia];
            $hora_fin = $_POST['horario_fin_' . $dia];

            // Verificar si el intervalo está establecido y no está vacío, de lo contrario asignar 20
            $intervalo = isset($_POST['intervalo_' . $dia]) && $_POST['intervalo_' . $dia] !== ""
                ? $_POST['intervalo_' . $dia]
                : 20;

            if ($id > 0) {
                // Si hay un id, actualizar el registro existente
                $sql = "UPDATE disponibilidad SET hora_inicio = ?, hora_fin = ?, intervalo = ? WHERE id = ? AND dia_semana = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssis", $hora_inicio, $hora_fin, $intervalo, $id, $dia);
            } else {
                // Si no hay id, insertar un nuevo registro
                $sql = "INSERT INTO disponibilidad (id_prof, dia_semana, hora_inicio, hora_fin, intervalo) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $id_prof, $dia, $hora_inicio, $hora_fin, $intervalo);
            }

            if ($stmt->execute()) {
                // Éxito al agregar o actualizar disponibilidad para este día
            } else {
                echo "Error al procesar la disponibilidad para el día $dia: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    // Redireccionar después de procesar todos los horarios
    header("Location: ./disponibilidad.php?success=true");
    exit();
}

$conn->close();
?>