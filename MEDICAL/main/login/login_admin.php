<?php
require_once "../conexion.php";

if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

$sql = "SELECT clave FROM usuario_admin WHERE usuario = '$usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_clave = $row['clave'];

    if (password_verify($clave, $hashed_clave)) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        header("Location: ../administacionTXT/panelAdmin.php");
        exit;
    } else {
        // Contraseña incorrecta
        header("Location: admin.php?error=credenciales");
        exit;
    }
} else {
    // Usuario no encontrado
    header("Location: admin.php?error=usuario");
    exit;
}

?>