<?php
include('../../conect.php');
session_start();

// Definir la tabla por defecto o la seleccionada
$tabla_seleccionada = isset($_GET['tabla']) ? $_GET['tabla'] : 'usuarios_rosario';

// Consulta dinámica dependiendo de la tabla seleccionada
$sql = "SELECT * FROM $tabla_seleccionada";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['user'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "<td>" . $row['cant_qr'] . "</td>";
        echo "<td>";
        // Usar solo un botón para eliminar, sin form
        echo "<button type='button' class='btn btn-danger btn-sm' onclick='eliminarUsuario(" . $row['id'] . ")'>Eliminar</button>";
        echo "<button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editUserModal' onclick='loadUserData(" . $row['id'] . ", \"" . $row['user'] . "\", \"" . $row['password'] . "\", \"" . $row['cant_qr'] . "\")'>Editar</button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>No hay registros</td></tr>";
}
?>
