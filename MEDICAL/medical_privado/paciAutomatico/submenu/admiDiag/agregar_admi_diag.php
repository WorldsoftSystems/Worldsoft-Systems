<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente']; // ID del paciente (entero)
    $impresion_naturaleza = $_POST['impresion_naturaleza'];
    $impresion_situacion = $_POST['impresion_situacion'];
    $impresion_conciencia = $_POST['impresion_conciencia'];
    $impresion_expectativas = $_POST['impresion_expectativas'];
    $diagnostico_clinico = $_POST['diagnostico_clinico'];
    $diagnostico_gravedad = $_POST['diagnostico_gravedad'];
    $factores_desencadenantes = $_POST['factores_desencadenantes'];
    $personalidad_premorbida = $_POST['personalidad_premorbida'];
    $incapacidad_social = $_POST['incapacidad_social'];
    $indicaciones = $_POST['indicaciones'];
    $pronostico = $_POST['pronostico'];

    // Crear la consulta SQL para insertar
    $sql = "INSERT INTO admi_diag (
                id_paciente, 
                impresion_naturaleza, 
                impresion_situacion, 
                impresion_conciencia, 
                impresion_expectativas, 
                diagnostico_clinico, 
                diagnostico_gravedad, 
                factores_desencadenantes, 
                personalidad_premorbida, 
                incapacidad_social, 
                indicaciones, 
                pronostico
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros (i = integer, s = string)
    $stmt->bind_param(
        "isssssssssss", 
        $id_paciente, 
        $impresion_naturaleza, 
        $impresion_situacion, 
        $impresion_conciencia, 
        $impresion_expectativas, 
        $diagnostico_clinico, 
        $diagnostico_gravedad, 
        $factores_desencadenantes, 
        $personalidad_premorbida, 
        $incapacidad_social, 
        $indicaciones, 
        $pronostico
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Impresión Diagnóstica agregada correctamente."));
        exit();  // Asegúrate de detener la ejecución del script
    } else {
        echo "Error al agregar Impresión Diagnóstica: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
