<?php
// Conexión a la base de datos
require_once "../conexion.php";

if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Recuperar datos del formulario
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Consulta SQL para obtener el hash de la contraseña del usuario
$sql = "SELECT clave FROM usuario_admin WHERE usuario = '$usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Obtener el hash de la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc();
    $hashed_clave = $row['clave'];

    // Verificar si la contraseña ingresada coincide con el hash almacenado
    if (password_verify($clave, $hashed_clave)) {
        // Inicio de sesión exitoso
        session_start();
        $_SESSION['usuario'] = $usuario;
        header("Location: ../registrarUsuario/registro.php"); // Redireccionar a la página de inicio después de iniciar sesión
    } else {
        // Credenciales incorrectas
        echo "Usuario o contraseña incorrectos.";
    }
} else {
    // Usuario no encontrado
    echo "Usuario no encontrado.";
}

$conn->close();
?>
