<?php
// Incluimos el archivo de conexión a la base de datos
require_once "../../conexion.php";

// Creamos un array para almacenar los resultados
$resultados = array();

// Creamos la consulta SQL
$sql = "SELECT num_hist_amb, num_hist_int FROM parametro_sistema";

try {
    // Ejecutamos la consulta
    $resultado = $conn->query($sql);

    // Verificamos si hay resultados
    if ($resultado->num_rows > 0) {
        // Recorremos cada fila y la añadimos al array de resultados
        while($fila = $resultado->fetch_assoc()) {
            $resultados[] = $fila;
        }
    } else {
        // Si no hay resultados, puedes devolver un mensaje vacío o personalizado
        $resultados['mensaje'] = "No se encontraron datos.";
    }

    // Devolvemos el resultado como JSON
    echo json_encode($resultados);

} catch (Exception $e) {
    // En caso de error, devolvemos un mensaje de error
    echo json_encode(array("error" => "Error al consultar los datos: " . $e->getMessage()));
}

// Cerramos la conexión a la base de datos
$conn->close();
?>
