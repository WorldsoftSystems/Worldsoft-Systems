<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Lógica para cerrar sesión (se puede usar desde cualquier vista con `?cerrar_sesion`)
if (isset($_GET['cerrar_sesion'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
