<?php
// Configurar el tiempo de vida de la sesión antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 86400); // 24 horas en segundos
    ini_set('session.cookie_lifetime', 86400); // 24 horas para la cookie de sesión
    session_start();
}

// Renovar la sesión si el usuario está activo
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
    session_unset(); // Eliminar variables de sesión
    session_destroy(); // Destruir sesión
    header("Location: ./index.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // Actualizar el tiempo de la última actividad

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
    'prueba' => 'prueba',
    'UP3063207857500' => 'pq0241',
    //'UP3063207857500'=> 'worldsof_medical_pq0241',
    //'UP30684529249' => 'worldsof_medical_pq2002',
    //'UP3068452924900' => 'worldsof_medical_pq2001',
    'UP3069149922304' => 'pq0303',
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