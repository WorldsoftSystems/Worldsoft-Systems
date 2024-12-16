<?php

// Conexión a la base de datos
require_once "../conexion.php";

if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Recuperar datos del formulario
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Hashear la contraseña antes de almacenarla en la base de datos
$hashed_clave = password_hash($clave, PASSWORD_DEFAULT);

// Consulta SQL para insertar un nuevo usuario
$sql = "INSERT INTO usuarios (usuario, clave) VALUES ('$usuario', '$hashed_clave')";

if ($conn->query($sql) === TRUE) {
    // Si la consulta fue exitosa, muestra una alerta y redirige a la misma página
    echo "<script>
            alert('Usuario registrado correctamente.');
            window.location.href = 'registro.php';
          </script>";
} else {
    // Si hubo un error, muestra una alerta y redirige a la misma página
    echo "<script>
            alert('Error al registrar el usuario: " . $conn->error . "');
            window.location.href = 'registro.php';
          </script>";
}
$conn->close();
?>
