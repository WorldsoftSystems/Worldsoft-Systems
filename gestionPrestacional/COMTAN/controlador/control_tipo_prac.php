<?php
// Incluir el archivo de conexión a la base de datos
require_once '../conexion/conexion.php';

// Función para agregar un nuevo tipo de práctica
function agregarTipoPractica($descripcion, $cod_practica)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "INSERT INTO tipo_prac (descript, cod_practica) VALUES (?, ?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("si", $descripcion, $cod_practica);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Función para obtener todos los tipos de práctica
function obtenerTiposPractica()
{
    global $conn;

    // Array para almacenar los resultados
    $tiposPractica = array();

    // Preparar la consulta SQL
    $sql = "SELECT * FROM tipo_prac";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Obtener cada fila de resultados y agregarla al array
        while ($row = $result->fetch_assoc()) {
            $tiposPractica[] = $row;
        }
    }

    return $tiposPractica;
}

// Función para eliminar un tipo de práctica por su ID
function eliminarTipoPractica($id)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "DELETE FROM tipo_prac WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Función para actualizar un tipo de práctica
function actualizarTipoPractica($id, $descripcion, $cod_practica)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "UPDATE tipo_prac SET descript = ?, cod_practica = ? WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("sii", $descripcion, $cod_practica, $id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Función para obtener los detalles de un tipo de práctica por su ID
function obtenerTipoPracticaPorID($id)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT * FROM tipo_prac WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el tipo de práctica
    if ($result->num_rows > 0) {
        // Devolver los detalles del tipo de práctica
        return $result->fetch_assoc();
    } else {
        // Devolver false si no se encontró el tipo de práctica
        return false;
    }
}

?>
