<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Si no hay id, asumir que es 0 (nuevo registro)
    $id_prof = $_POST['id_prof'];

    // Obtener los días seleccionados como un array
    $dias_seleccionados = array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado");

    $respuesta = []; // Inicializar un array para la respuesta

    // Iterar sobre los días seleccionados
    foreach ($dias_seleccionados as $dia) {
        // Verificar si se han proporcionado valores de hora de inicio y fin para este día
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
                // Verificar si ya existe un registro para el profesional y el día
                $check_sql = "SELECT id FROM disponibilidad WHERE id_prof = ? AND dia_semana = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("is", $id_prof, $dia);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    // Si ya existe, retornar un error
                    $respuesta[] = ['error' => "Ya existe disponibilidad para $dia. Por favor, edítala."];
                    continue; // Saltar al siguiente día
                }

                // Si no existe, insertar un nuevo registro
                $sql = "INSERT INTO disponibilidad (id_prof, dia_semana, hora_inicio, hora_fin, intervalo) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $id_prof, $dia, $hora_inicio, $hora_fin, $intervalo);
            }

            if ($stmt->execute()) {
                // Éxito al agregar o actualizar disponibilidad para este día
                $respuesta[] = ['success' => "Disponibilidad para $dia guardada correctamente."];
            } else {
                $respuesta[] = ['error' => "Error al procesar la disponibilidad para $dia: " . $stmt->error];
            }

            $stmt->close();
        }
    }

    // Retornar la respuesta como JSON
    echo json_encode($respuesta);
    exit();
}

$conn->close();
?>
