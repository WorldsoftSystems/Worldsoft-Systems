<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el término de búsqueda de la consulta GET
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$searchQuery = $conn->real_escape_string($searchQuery);

// Consulta para obtener id y nombre del paciente filtrado por término de búsqueda
$sql = "SELECT paciente.id, 
               CONCAT(TRIM(paciente.nombre), ' - ', TRIM(paciente.benef), ' / ', TRIM(paciente.parentesco)) AS nombre,
               m.id AS modalidad
        FROM paciente
        LEFT JOIN (
            SELECT id_paciente, modalidad, MAX(fecha) AS max_fecha
            FROM paci_modalidad
            GROUP BY id_paciente
        ) pM ON paciente.id = pM.id_paciente
        LEFT JOIN modalidad m ON pM.modalidad = m.id
        WHERE paciente.nombre LIKE '%$searchQuery%' 
           OR paciente.benef LIKE '%$searchQuery%' 
           OR paciente.parentesco LIKE '%$searchQuery%'
        ORDER BY paciente.nombre ASC";



$result = $conn->query($sql);

// Procesar los resultados de la consulta
$pacientes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($pacientes);

?>
