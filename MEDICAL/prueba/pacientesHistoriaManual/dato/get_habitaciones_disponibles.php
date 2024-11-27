<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ejecutar la consulta
$sql = "SELECT 
  h.id AS habitacion_id,
  h.num,
  h.piso,
  h.c_camas AS camas_totales,
  COALESCE(COUNT(ph.id), 0) AS camas_ocupadas,
  (h.c_camas - COALESCE(COUNT(ph.id), 0)) AS camas_disponibles
FROM 
  habitaciones h
  LEFT JOIN paci_habitacion ph ON h.id = ph.habitacion
  AND (ph.fecha_egreso = '0000-00-00' OR ph.fecha_egreso IS NULL) -- Considerar camas ocupadas si la fecha de egreso es '0000-00-00'
GROUP BY 
  h.id, h.num, h.piso, h.c_camas;





";
$result = $conn->query($sql);

// Manejo de errores para la consulta
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Procesar los resultados de la consulta
$habitaciones_dispo = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $habitaciones_dispo[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($habitaciones_dispo);
?>