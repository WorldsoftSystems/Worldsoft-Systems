<?php
// Incluimos el archivo de conexión a la base de datos
include ('../../conexion.php');

// Verificamos que los parámetros GET existan
if (isset($_GET['fecha_desde']) && isset($_GET['fecha_hasta'])) {
    $fechaDesde = $_GET['fecha_desde'];
    $fechaHasta = $_GET['fecha_hasta'];
    
    // Consulta SQL para obtener las operaciones con fecha_vencimiento entre fecha_desde y fecha_hasta
    $query = "SELECT p.nombre, o.fecha, o.op, o.cant, m.descripcion, o.fecha_vencimiento
              FROM paci_op o
              LEFT JOIN paciente p ON p.id = o.id_paciente
              LEFT JOIN modalidad m ON m.id = o.modalidad_op
              WHERE fecha_vencimiento BETWEEN ? AND ?";
    
    // Preparamos la consulta
    if ($stmt = $conn->prepare($query)) {
        // Bind de parámetros a la consulta
        $stmt->bind_param("ss", $fechaDesde, $fechaHasta);
        
        // Ejecutamos la consulta
        $stmt->execute();
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Creamos un array para almacenar los datos
        $opData = array();
        
        // Agrupar por modalidad
        while ($row = $result->fetch_assoc()) {
            $modalidad = $row['descripcion'];
            if (!isset($opData[$modalidad])) {
                $opData[$modalidad] = []; // Inicializa el array para la modalidad
            }
            $opData[$modalidad][] = $row; // Agrega el registro al array de la modalidad
        }
        
        // Devolvemos los datos en formato JSON
        echo json_encode($opData);
        
        // Cerramos la consulta
        $stmt->close();
    } else {
        // En caso de error al preparar la consulta
        echo json_encode(["error" => "Error al preparar la consulta."]);
    }
} else {
    // Si no se reciben los parámetros
    echo json_encode(["error" => "Faltan parámetros fecha_desde o fecha_hasta."]);
}

// Cerramos la conexión a la base de datos
$conn->close();
?>
