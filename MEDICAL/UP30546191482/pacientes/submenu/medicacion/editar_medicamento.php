<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $idPaciente = $_POST['id_paciente']; // Asegúrate de tener también el 'id_paciente'
    $desc = $_POST['mediDesc'];
    $fecha = $_POST['medi_fecha'];
    $hora = $_POST['medi_hora'];
    $dosis = $_POST['dosis'];

    // Obtener la fecha de admisión del paciente
    $sql_admision = "SELECT admision FROM paciente WHERE id = ?";
    $stmt_admision = $conn->prepare($sql_admision);
    $stmt_admision->bind_param('i', $idPaciente);
    $stmt_admision->execute();
    $stmt_admision->bind_result($fecha_admision);
    $stmt_admision->fetch();
    $stmt_admision->close();

    // Verificar si la fecha de la medicación es menor que la fecha de admisión
    if (strtotime($fecha) < strtotime($fecha_admision)) {
        echo "Error: La fecha de la medicación no puede ser menor a la fecha de admisión del paciente.";
    } else {
        // Preparar y ejecutar la consulta de actualización
        $sql = "UPDATE medicacion_paci SET medicamento = ?, fecha = ?, hora = ?, dosis = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issii", $desc, $fecha, $hora, $dosis, $id);

        if ($stmt->execute()) {
            echo "Medicamento actualizado correctamente.";
        } else {
            echo "Error al actualizar medicamento: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
