<?php
// Incluir el archivo de conexión a la base de datos
include('../../conexion.php');

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener la modalidad más reciente por paciente
$sql = "SELECT DISTINCT p.id,IFNULL(CONCAT(m.codigo, ' - ', m.descripcion), 'No tiene modalidad') AS modalidad_full
FROM egresos e
JOIN modalidad m ON e.modalidad = m.id
JOIN paciente p ON p.id = e.id_paciente
WHERE e.fecha_egreso = '0000-00-00'
";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Crear un array para almacenar los resultados
    $modalidades = array();

    // Iterar sobre los resultados y agregar cada fila al array
    while ($row = $result->fetch_assoc()) {
        $modalidades[] = $row;
    }

    // Convertir el array a formato JSON y mostrarlo
    echo json_encode($modalidades);
} else {
    // Si no hay resultados, mostrar un mensaje vacío
    echo json_encode(array());
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
