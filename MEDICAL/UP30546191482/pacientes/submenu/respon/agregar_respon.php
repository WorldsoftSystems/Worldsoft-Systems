<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$nombre = $_POST['respon_nombre'];
$tel = $_POST['respon_tel'];
$tipo_familiar = $_POST['respon_parent'];
$dni = $_POST['respon_dni'];
$dom = $_POST['respon_dom'];
$localidad = $_POST['respon_locali'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO responsable (id_paciente, nombreYapellido, tel, tipo_familiar, dni, dom, localidad)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isiiiss", $idPaciente, $nombre,$tel,$tipo_familiar,$dni,$dom,$localidad);

if ($stmt->execute()) {
    echo "Práctica agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>