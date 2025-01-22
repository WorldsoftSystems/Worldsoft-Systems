<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$motivo = $_POST['motivo_evo'];
$antecedentes = $_POST['antecedentes'];
$estadoActual = $_POST['estado_actual'];
$familia = $_POST['familia'];
$diag = $_POST['evo_diag'];
$objetivo = $_POST['objetivo'];
$duracion = $_POST['duracion'];
$frecuencia = $_POST['frecuencia'];
$fecha = $_POST['evoFecha'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO evoluciones_amb (id_paciente, motivo, antecedentes, estado_actual, familia, diag, objetivo, duracion, frecuencia, fecha )
        VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('issssissss', $idPaciente, $motivo, $antecedentes, $estadoActual, $familia, $diag,$objetivo,$duracion,$frecuencia,$fecha);

if ($stmt->execute()) {
    echo "Evolucion agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
