<?php
// Incluir el archivo de conexión
require_once '../conexion/conexion.php';

// Iniciar la sesión
session_start();

// Obtener datos del formulario
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Consulta SQL para verificar las credenciales
$sql = "SELECT * FROM credenciales WHERE usuario='$usuario' AND clave='$clave'";
$resultado = $conn->query($sql);

// Verificar si se encontraron resultados
if ($resultado->num_rows > 0) {
    // Inicio de sesión exitoso
    $_SESSION['usuario'] = $usuario; // Establecer la sesión del usuario
    header("Location: ../panelMain/panelMain.php"); // Redirigir al panel principal
    exit(); // Terminar el script después de redirigir
} else {
    // Inicio de sesión fallido
    header("Location: ../index.php?error=1"); // Redirigir al usuario a la página de inicio con un mensaje de error
    exit(); // Terminar el script después de redirigir
}

// Cerrar la conexión (esto no es necesario ya que se cerrará automáticamente al finalizar el script)
?>
