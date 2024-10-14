<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente']; // ID del paciente (entero)
    $medico_tratante = $_POST['medico_tratante'];
    $objetivos_generales = $_POST['objetivos_generales'];
    $examen_postural = $_POST['examen_postural'];
    $examen_muscular = $_POST['examen_muscular'];
    $examen_flexibilidad = $_POST['examen_flexibilidad'];
    $fuerza_miembros_inferiores = $_POST['fuerza_miembros_inferiores'];
    $fuerza_miembros_superiores = $_POST['fuerza_miembros_superiores'];
    $equilibrio_normal = $_POST['equilibrio_normal'];
    $equilibrio_ojos_cerrados = $_POST['equilibrio_ojos_cerrados'];
    $equilibrio_base_sustentacion = $_POST['equilibrio_base_sustentacion'];
    $movimiento_ms = $_POST['movimiento_ms'];
    $movimiento_ml = $_POST['movimiento_ml'];
    $movimiento_tronco = $_POST['movimiento_tronco'];
    $caminando_giros = $_POST['caminando_giros'];
    $observaciones_generales = $_POST['observaciones_generales'];

    // Crear la consulta SQL para insertar
    $sql = "INSERT INTO fisica (
                id_paciente, 
                medico_tratante, 
                objetivos_generales, 
                examen_postural, 
                examen_muscular, 
                examen_flexibilidad, 
                fuerza_miembros_inferiores, 
                fuerza_miembros_superiores, 
                equilibrio_normal, 
                equilibrio_ojos_cerrados, 
                equilibrio_base_sustentacion, 
                movimiento_ms, 
                movimiento_ml, 
                movimiento_tronco, 
                caminando_giros, 
                observaciones_generales
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros (i = integer, s = string)
    $stmt->bind_param(
        "iissssssssssssss", 
        $id_paciente, 
        $medico_tratante, 
        $objetivos_generales, 
        $examen_postural, 
        $examen_muscular, 
        $examen_flexibilidad, 
        $fuerza_miembros_inferiores, 
        $fuerza_miembros_superiores, 
        $equilibrio_normal, 
        $equilibrio_ojos_cerrados, 
        $equilibrio_base_sustentacion, 
        $movimiento_ms, 
        $movimiento_ml, 
        $movimiento_tronco, 
        $caminando_giros, 
        $observaciones_generales
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Física agregada correctamente."));
        exit();  // Asegúrate de detener la ejecución del script
    } else {
        echo "Error al agregar física: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
