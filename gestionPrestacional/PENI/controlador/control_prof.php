<?php
require_once '../modelo/profe.php';

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $especialidad = $_POST['especialidad']; // Agregar la especialidad obtenida del formulario
    $prof_g = !empty($_POST['profesional']) ? $_POST['profesional'] : NULL;

    if (agregarProfesor($nombre, $apellido, $especialidad, $prof_g)) {
        $_SESSION['alert_message'] = "Profesional registrado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al agregar el registro.";
    }

    // Redirigir después de agregar para evitar reenvío de formulario
    header("Location: ../profesionalPanel/profesionalPanel.php");
    exit(); // Asegura que el script se detenga después de la redirección

}


if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    if (eliminarProfesor($id)) {
        $_SESSION['alert_message'] = "Profesional eliminado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al eliminar el registro: " . $conn->error;
    }

    // Redirigir después de eliminar para evitar reenvío de formulario
    header("Location: ../profesionalPanel/profesionalPanel.php");
    exit(); // Asegura que el script se detenga después de la redirección
}

if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $especialidad = $_POST['especialidad']; // Capturar el valor de la especialidad desde el formulario
    $prof_g = $_POST['profesional'];

    // Llamar a la función para actualizar el profesional
    if (actualizarProfesor($id, $nombre, $apellido, $especialidad, $prof_g)) {
        $_SESSION['alert_message'] = "Profesional actualizado correctamente";
    } else {
        $_SESSION['alert_message'] = "Error al actualizar el registro: " . $conn->error;
    }

    // Redirigir después de actualizar para evitar el reenvío del formulario
    header("Location: ../profesionalPanel/profesionalPanel.php");
    exit(); // Asegurar que el script se detenga después de la redirección
}



// Función para obtener los detalles de un tipo de práctica por su ID
// Función para obtener los detalles de un profesional por su ID
function obtenerProfesionalPorID($id)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT * FROM prof WHERE cod_prof = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el profesional
    if ($result->num_rows > 0) {
        // Devolver los detalles del profesional
        return $result->fetch_assoc();
    } else {
        // Devolver false si no se encontró el profesional
        return false;
    }
}
?>