<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Establecer el encabezado para devolver JSON
header('Content-Type: application/json');

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$patologias = $_POST['patologias'];
$indicacion_dieta = $_POST['indicacion_dieta'];
$actitud_comida = $_POST['actitud_comida'];
$peso = $_POST['peso'];
$talla = $_POST['talla'];
$imc = $_POST['imc'];
$requiere = $_POST['requiere'];
$no_requiere = $_POST['no_requiere'];
$especificar = $_POST['especificar'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error));
    exit();
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO nutricion (id_paciente, patologias, indicacion_dieta, actitud_comida, peso, talla, imc, requiere, no_requiere, especificar)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(array("status" => "error", "message" => "Failed to prepare statement: " . $conn->error));
    exit();
}

$stmt->bind_param('isssssssss', $idPaciente, $patologias, $indicacion_dieta, $actitud_comida, $peso, $talla, $imc, $requiere, $no_requiere, $especificar);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Nutrición agregada correctamente."));
} else {
    echo json_encode(array("status" => "error", "message" => "Error al agregar nutrición: " . $stmt->error));
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
