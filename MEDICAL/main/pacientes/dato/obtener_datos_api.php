<?php

require_once "../../conexion.php";

// Obtener el ID del paciente desde la solicitud GET
$idPaciente = $_GET['id'];

// Consulta SQL para obtener los datos del paciente
$sql = "SELECT benef, parentesco, obra_social FROM paciente WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idPaciente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos del paciente
    $row = $result->fetch_assoc();
    $benef = $row['benef'];
    $parentesco = $row['parentesco'];
    $obra_social = $row['obra_social'];

    // Devolver los datos en formato JSON
    echo json_encode([
        'benef' => $benef,
        'parentesco' => $parentesco,
        'obra_social' => $obra_social
    ]);
} else {
    // Si no se encuentra el paciente, devolver un error
    echo json_encode(['error' => 'Paciente no encontrado']);
}

$stmt->close();
$conn->close();
?>