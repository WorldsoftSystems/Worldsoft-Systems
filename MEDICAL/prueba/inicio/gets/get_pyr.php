<?php
// Información de conexión
$hostname = "localhost";
$username = "root";
$password = "";
$database = "chat_zoe"; 

// Crear conexión
$conn = new mysqli($hostname, $username, $password, $database);

// Establecer la codificación de caracteres
$conn->set_charset("utf8mb4");

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Ejecutar la consulta
$sql = "SELECT * FROM bot_zoe";
$result = $conn->query($sql);

$faqData = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $faqData[] = [
            'text' => $row['pregunta'],
            'answer' => $row['respuesta']
        ];
    }
}

// Devolvemos el resultado en formato JSON
echo json_encode($faqData);

// Cerrar la conexión
$conn->close();
?>
