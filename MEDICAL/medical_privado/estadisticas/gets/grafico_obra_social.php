<?php
// Incluir el archivo de conexión
include ('../../conexion.php');

// Array de respuesta
$response = array();

$sql = "SELECT 
            os.siglas AS obra_social, 
            COUNT(p.id) AS total_pacientes 
        FROM 
            paciente p
        LEFT JOIN 
            obra_social os 
        ON 
            p.obra_social = os.id
        GROUP BY 
            os.siglas
        ORDER BY 
            total_pacientes DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Enviar datos en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
