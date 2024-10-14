<?php

// Incluir el archivo de conexión a la base de datos
require_once "../conexion.php";

// Obtener los datos del nuevo paciente desde la solicitud POST
$nombreNuevo = $_POST['nombreNuevo'];
$obraSocialNuevo = $_POST['obraSocialNuevo'];

// Preparar la consulta SQL para insertar el nuevo paciente
$sql = "INSERT INTO paciente (nombre, obra_social) VALUES ('$nombreNuevo', '$obraSocialNuevo')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    // Si la inserción fue exitosa, devolver un mensaje de éxito
    echo json_encode(array('mensaje' => 'Nuevo paciente agregado correctamente'));
} else {
    // Si ocurrió un error durante la inserción, devolver un mensaje de error
    echo json_encode(array('error' => 'Error al agregar nuevo paciente: ' . $conn->error));
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
