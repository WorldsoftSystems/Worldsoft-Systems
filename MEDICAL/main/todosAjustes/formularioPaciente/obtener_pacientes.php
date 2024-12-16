<?php

// Incluir el archivo de conexión a la base de datos
require_once "../conexion.php";

// Query para obtener los pacientes
$sql = "SELECT * FROM paciente";

// Ejecutar la consulta
$resultado = $conn->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Inicializar un array para almacenar los pacientes
    $pacientes = array();

    // Recorrer los resultados y almacenarlos en el array
    while ($fila = $resultado->fetch_assoc()) {
        $paciente = array(
            'id' => $fila['id'],
            'nombre' => $fila['nombre'],
            'obra_social' => $fila['obra_social']
        );
        // Agregar el paciente al array de pacientes
        array_push($pacientes, $paciente);
    }

    // Convertir el array de pacientes a formato JSON
    echo json_encode($pacientes);
} else {
    // Si no hay resultados, devolver un mensaje indicando que no se encontraron pacientes
    echo json_encode(array('mensaje' => 'No se encontraron pacientes'));
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
