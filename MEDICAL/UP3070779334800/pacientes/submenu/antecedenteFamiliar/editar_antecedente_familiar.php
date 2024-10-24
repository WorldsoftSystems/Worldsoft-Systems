<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // ID del registro a actualizar
    $antecedentesFamiliar1 = $_POST['antecedentesFamiliar1'];
    $antecedentesFamiliar2 = $_POST['antecedentesFamiliar2'];
    $antecedentesFamiliar3 = $_POST['antecedentesFamiliar3'];
    $antecedentesFamiliar4 = $_POST['antecedentesFamiliar4'];
    $antecedentesFamiliar5 = $_POST['antecedentesFamiliar5'];
    $antecedentesFamiliar6 = $_POST['antecedentesFamiliar6'];

    // Crear la consulta SQL para actualizar
    $sql = "UPDATE antecedentes_familiares SET 
                antecedentes_familiar_1 = ?, 
                antecedentes_familiar_2 = ?, 
                antecedentes_familiar_3 = ?, 
                antecedentes_familiar_4 = ?, 
                antecedentes_familiar_5 = ?, 
                antecedentes_familiar_6 = ?
            WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param(
        "ssssssi", 
        $antecedentesFamiliar1, 
        $antecedentesFamiliar2, 
        $antecedentesFamiliar3, 
        $antecedentesFamiliar4, 
        $antecedentesFamiliar5, 
        $antecedentesFamiliar6, 
        $id
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Antecedentes familiares actualizados correctamente."));
        exit();
    } else {
        echo "Error al actualizar antecedentes familiares: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
