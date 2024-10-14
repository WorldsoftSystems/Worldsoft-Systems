<?php
require_once "../../conexion.php";

// Obtener el id_paciente desde la solicitud GET
$idPaciente = $_GET['id_paciente'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para obtener los egresos del paciente específico
$sql = "SELECT j.*, juz.descripcion AS juz_full,s.descripcion as secre_full,c.descripcion as cura_full,t.descripcion as t_full
        FROM judiciales j 
        JOIN paciente p ON j.id_paciente = p.id 
        LEFT JOIN juzgado juz ON j.juzgado = juz.id
        LEFT JOIN secretaria s ON j.secre = s.id 
        LEFT JOIN curaduria c ON j.cura = c.id
        LEFT JOIN t_juicio t ON j.t_juicio = t.id
        WHERE j.id_paciente = $idPaciente";
$result = $conn->query($sql);


// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$judiciales = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $judiciales[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($judiciales);
?>
