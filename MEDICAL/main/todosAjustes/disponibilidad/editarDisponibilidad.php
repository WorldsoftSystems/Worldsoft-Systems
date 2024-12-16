<?php
require_once "../../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $id_prof = $_POST['id_prof'];
    
    // Iterar sobre los días de la semana
    $dias = array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado");
    foreach ($dias as $dia) {
        // Verificar si se proporcionó un horario para este día
        if (isset($_POST['horario_inicio_' . $dia]) && isset($_POST['horario_fin_' . $dia]) && isset($_POST['intervalo_' . $dia])) {
            $hora_inicio = $_POST['horario_inicio_' . $dia];
            $hora_fin = $_POST['horario_fin_' . $dia];
            $intervalo = $_POST['intervalo_' . $dia];

            // Consulta SQL para actualizar el registro correspondiente a este día
            $sql = "UPDATE disponibilidad SET hora_inicio = ?, hora_fin = ?, intervarlo= ? WHERE id = ? AND dia_semana = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssis", $hora_inicio, $hora_fin,$intervalo, $id, $dia);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirigir después de editar la disponibilidad
    header("Location: ./disponibilidad.php?success=true");
    exit();
}
?>
