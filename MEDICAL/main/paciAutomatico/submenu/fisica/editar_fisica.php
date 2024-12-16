<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
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

    // Crear la consulta SQL con el SET dinámico
    $sql = "UPDATE fisica SET 
            medico_tratante = ?, 
            objetivos_generales = ?, 
            examen_postural = ?, 
            examen_muscular = ?, 
            examen_flexibilidad = ?, 
            fuerza_miembros_inferiores = ?, 
            fuerza_miembros_superiores = ?, 
            equilibrio_normal = ?, 
            equilibrio_ojos_cerrados = ?, 
            equilibrio_base_sustentacion = ?, 
            movimiento_ms = ?, 
            movimiento_ml = ?, 
            movimiento_tronco = ?, 
            caminando_giros = ?, 
            observaciones_generales = ? 
            WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros (i = integer, s = string)
    $stmt->bind_param(
        "issssssssssssssi", 
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
        $observaciones_generales, 
        $id
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Física actualizada correctamente."));
        exit();  // Asegúrate de detener la ejecución del script
    } else {
        echo "Error al actualizar física: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
