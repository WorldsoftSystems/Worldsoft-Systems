<?php
session_start();

// Capturar y guardar 'up' de manera segura
$up = filter_input(INPUT_GET, 'up', FILTER_SANITIZE_SPECIAL_CHARS);
if (!empty($up)) {
    $_SESSION['up'] = $up;
}

// Solo errores
$errorMsg = '';
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);
if ($error) {
    switch ($error) {
        case 'credenciales_incorrectas':
        case 'usuario_no_encontrado':
            $errorMsg = 'Usuario o contraseña incorrectos.';
            break;
        case 'sesion_expirada':
            $errorMsg = 'Tu sesión ha expirado. Iniciá sesión de nuevo.';
            break;
    }
}
?>
