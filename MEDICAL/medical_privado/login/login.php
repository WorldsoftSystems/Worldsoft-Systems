<?php
// Conexión a la base de datos
require_once "../conexion.php";

// Verificar la conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos.");
}

// Recuperar datos del formulario y sanitizar la entrada
$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

// Validar que los datos no estén vacíos
if (empty($usuario) || empty($clave)) {
    header("Location: ../index.php?error=datos_incompletos");
    exit();
}

// Preparar la consulta SQL
$sql = "SELECT clave FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt === false) {
    die("Error en la preparación de la consulta.");
}

// Vincular el parámetro
$stmt->bind_param("s", $usuario);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows > 0) {
    // Obtener el hash de la contraseña almacenada en la base de datos
    $row = $result->fetch_assoc();
    $hashed_clave = $row['clave'];

    // Verificar si la contraseña ingresada coincide con el hash almacenado
    if (password_verify($clave, $hashed_clave)) {
        // Inicio de sesión exitoso
        session_start();
        $_SESSION['usuario'] = $usuario;

        // Establecer opciones seguras para las cookies de sesión
        session_regenerate_id(true); // Regenerar el ID de sesión para evitar fijación de sesión
        ini_set('session.cookie_httponly', 1); // Marcar la cookie de sesión como HttpOnly
        ini_set('session.cookie_secure', 1); // Marcar la cookie de sesión como Secure (requiere HTTPS)

        header("Location: ../inicio/home.php"); // Redireccionar a la página de inicio después de iniciar sesión
        exit();
    } else {
        // Credenciales incorrectas
        header("Location: ../index.php?error=credenciales_incorrectas");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: ../index.php?error=usuario_no_encontrado");
    exit();
}


?>
