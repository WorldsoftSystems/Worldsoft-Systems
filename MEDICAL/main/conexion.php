<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener el 'UP' desde la sesión o la URL
$cliente = isset($_GET['up']) ? $_GET['up'] : (isset($_SESSION['up']) ? $_SESSION['up'] : null);

// Configuraciones comunes
$config_comun = [
    //'host' => 'localhost',
    //'user' => 'worldsof_OME',
    //'password' => 'Wss1593.',
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
];

// Bases de datos específicas por cliente
$config_bases_datos = [
    'UP3069149922304' => 'medical_testpq0303',
    'prueba' => 'medical_pq2001test',
    'UP3054610431800' => 'medical_pq0222',
    'UP3063207857500' => 'medical_pq0241_test'
    //'UP3063207857500'=> 'worldsof_medical_pq0241',
    //'UP30684529249' => 'worldsof_medical_pq2002',
    //'UP3068452924900' => 'worldsof_medical_pq2001',
    //'UP3069149922304' => 'worldsof_medical_pq0303',
    //'UP3070014059400' => 'worldsof_medica_pq1605',
    //'UP3070779334800' => 'worldsof_medical_pq0328'
];

// Validar el cliente y obtener su base de datos
if (!$cliente || !isset($config_bases_datos[$cliente])) {
    header("Location: https://worldsoftsystems.com.ar/MEDICAL/medical_redireccion.html");
    exit; // Asegúrate de detener la ejecución después de la redirección
}


// Combinar configuraciones comunes con la base de datos específica
$config = array_merge($config_comun, ['database' => $config_bases_datos[$cliente]]);

// Crear conexión
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['database']);
$conn->set_charset("utf8mb4");

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>