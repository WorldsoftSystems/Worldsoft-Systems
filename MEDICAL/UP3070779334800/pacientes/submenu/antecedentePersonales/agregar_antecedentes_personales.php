<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente']; // ID del paciente (entero)
    $complicaciones_nacimiento = $_POST['complicaciones_nacimiento'];
    $desarrollo_ninez = $_POST['desarrollo_ninez'];
    $enfermedades_principales = $_POST['enfermedades_principales'];
    $sistema_nervioso = $_POST['sistema_nervioso'];
    $estudios = $_POST['estudios'];
    $actividad_sexual = $_POST['actividad_sexual'];
    $historial_marital = $_POST['historial_marital'];
    $embarazos_hijos = $_POST['embarazos_hijos'];
    $interrelacion_familiar = $_POST['interrelacion_familiar'];
    $actividades_laborales = $_POST['actividades_laborales'];
    $habitos = $_POST['habitos'];
    $intereses = $_POST['intereses'];
    $actividad_social = $_POST['actividad_social'];
    $creencias_religiosas = $_POST['creencias_religiosas'];
    $toxicomanias = $_POST['toxicomanias'];
    $rasgos_personalidad = $_POST['rasgos_personalidad'];

    // Crear la consulta SQL para insertar
    $sql = "INSERT INTO antecedentes_personales (
                id_paciente, 
                complicaciones_nacimiento, 
                desarrollo_ninez, 
                enfermedades_principales, 
                sistema_nervioso, 
                estudios, 
                actividad_sexual, 
                historial_marital, 
                embarazos_hijos, 
                interrelacion_familiar, 
                actividades_laborales, 
                habitos, 
                intereses, 
                actividad_social, 
                creencias_religiosas, 
                toxicomanias, 
                rasgos_personalidad
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros (i = integer, s = string)
    $stmt->bind_param(
        "issssssssssssssss", 
        $id_paciente, 
        $complicaciones_nacimiento, 
        $desarrollo_ninez, 
        $enfermedades_principales, 
        $sistema_nervioso, 
        $estudios, 
        $actividad_sexual, 
        $historial_marital, 
        $embarazos_hijos, 
        $interrelacion_familiar, 
        $actividades_laborales, 
        $habitos, 
        $intereses, 
        $actividad_social, 
        $creencias_religiosas, 
        $toxicomanias, 
        $rasgos_personalidad
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Antecedentes personales agregados correctamente."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al agregar antecedentes personales: " . $stmt->error));
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
