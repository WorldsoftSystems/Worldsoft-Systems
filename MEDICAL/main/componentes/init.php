<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/titulo_sistema.php';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    error_log("Error de conexión PDO: " . $e->getMessage());
    $pdo = null;
}

$title = obtenerTituloSistema($pdo);
?>
