<?php
require_once '../modelo/paciente.php';

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['agregar'])) {
    // Verificar si el campo nombreYapellido está vacío
    if (empty($_POST['nombreYapellido'])) {
        $_SESSION['alert_message'] = "El campo Nombre y Apellido no puede estar vacío";
        header("Location: ../pacientePanel/pacientePanel.php");
        exit(); // Asegura que el script se detenga después de la redirección
    }

    $nombreYapellido = $_POST['nombreYapellido'];
    $beneficio = $_POST['benef'];
    $parentesco = $_POST['parent'];
    $cod_prof = $_POST['cod_prof'];
    $cod_practica = $_POST['cod_practica'];
    $fecha = $_POST['fecha'];
    $cod_diag = $_POST['cod_diag'];// Obtener el código de diagnóstico del formulario 
    $token = $_POST['token'];
    // Concatenamos $beneficio y $parentesco en una sola cadena
    $beneficio_concatenado = $beneficio . $parentesco;

    // Verificar si ya existe un registro con el mismo cod_prof, fecha (sin hora) y beneficio
    $sql_check = "SELECT COUNT(*) AS count FROM paciente WHERE cod_prof = '$cod_prof' AND DATE(fecha) = DATE('$fecha') AND benef = '$beneficio_concatenado'";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Lanzar alerta si ya existe un registro con el mismo cod_prof, fecha y beneficio
        $_SESSION['alert_message'] = "El paciente ya se encuentra cargado con la fecha especificada y el beneficio.";
    } else {
        // Llama a la función agregarPaciente con los argumentos necesarios, incluido y $cod_diag
        if (agregarPaciente($nombreYapellido, $beneficio, $parentesco,$fecha, $cod_prof, $cod_practica, $cod_diag, $token)) {
            $_SESSION['alert_message'] = "Paciente agregado correctamente";
        } else {
            $_SESSION['alert_message'] = "Error al agregar el paciente";
        }
    }

    // Redirigir después de agregar para evitar reenvío de formulario
    header("Location: ../pacientePanel/pacientePanel.php");
    exit(); // Asegura que el script se detenga después de la redirección
}

// Función para actualizar paciente
function actualizarPaciente($id, $nombreYapellido, $benef, $codPractica, $token,$fecha, $codDiag)
{
    global $conn; // Usar la conexión a la base de datos

    // Preparar la consulta
    $sql = "UPDATE paciente SET nombreYapellido = ?, benef = ?, cod_practica = ?, fecha = ?, token = ?, cod_diag = ? WHERE cod_paci = ?";
    $stmt = $conn->prepare($sql); // Cambia $conexion por $conn
    if ($stmt === false) {
        return false; // Devolver falso si la preparación de la consulta falla
    }

    $stmt->bind_param('sssssss', $nombreYapellido, $benef, $codPractica,$fecha, $token, $codDiag, $id);

    // Ejecutar la consulta y devolver el resultado
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



// Manejar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se reciban todos los parámetros necesarios
    if (isset($_POST['id'], $_POST['nombreYapellido'], $_POST['benef'], $_POST['cod_practica'], $_POST['token'], $_POST['cod_diag'])) {
        $id = $_POST['id'];
        $nombreYapellido = $_POST['nombreYapellido'];
        $benef = $_POST['benef'];
        $codPractica = $_POST['cod_practica'];
        $token = $_POST['token'];
        $codDiag = $_POST['cod_diag'];
        $fecha_edit = $_POST['fecha_edit'];

        // Llamar a la función para actualizar el paciente
        if (actualizarPaciente($id, $nombreYapellido, $benef, $codPractica,$token,$fecha_edit, $codDiag)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el paciente']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
    }
    exit;
}


function obtenerPacientesConFiltro($fecha_desde, $fecha_hasta, $profesional)
{
    global $conn;

    // Preparar la consulta SQL base para obtener pacientes
    $sql = "SELECT * FROM paciente WHERE 1";

    // Aplicar filtro por fecha desde
    if (!empty($fecha_desde)) {
        $sql .= " AND DATE(fecha) >= '$fecha_desde'";
    }

    // Aplicar filtro por fecha hasta
    if (!empty($fecha_hasta)) {
        $sql .= " AND DATE(fecha) <= '$fecha_hasta'";
    }

    // Aplicar filtro por profesional
    if (!empty($profesional)) {
        $sql .= " AND cod_prof = $profesional";
    }

    // Ordenar por estado de carga y luego por cod_prof
    $sql .= " ORDER BY cargado ASC, cod_prof ASC";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron pacientes
    if ($result->num_rows > 0) {
        // Inicializar array para almacenar pacientes
        $pacientes = array();

        // Iterar sobre los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $pacientes[] = $row;
        }

        // Devolver array de pacientes
        return $pacientes;
    } else {
        // Devolver un array vacío si no se encontraron pacientes
        return array();
    }
}

function obtenerPacientesConFiltroEstadisticas($fecha_desde, $fecha_hasta, $profesional, $numRegistros)
{
    global $conn;

    // Preparar la consulta SQL base para obtener pacientes
    $sql = "SELECT * FROM paciente WHERE 1";

    // Aplicar filtro por fecha desde
    if (!empty($fecha_desde)) {
        $sql .= " AND DATE(fecha) >= '$fecha_desde'";
    }

    // Aplicar filtro por fecha hasta
    if (!empty($fecha_hasta)) {
        $sql .= " AND DATE(fecha) <= '$fecha_hasta'";
    }

    // Aplicar filtro por profesional
    if (!empty($profesional)) {
        $sql .= " AND cod_prof = $profesional";
    }

    // Ordenar por estado de carga y luego por cod_prof
    $sql .= " ORDER BY cargado ASC, cod_prof ASC";

    // Aplicar límite según el número de registros deseado
    if ($numRegistros !== 'todos') {
        $sql .= " LIMIT $numRegistros";
    }

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron pacientes
    if ($result->num_rows > 0) {
        // Inicializar array para almacenar pacientes
        $pacientes = array();

        // Iterar sobre los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $pacientes[] = $row;
        }

        // Devolver array de pacientes
        return $pacientes;
    } else {
        // Devolver un array vacío si no se encontraron pacientes
        return array();
    }
}

function obtenerPacientesConFiltroParaPDF($fecha_desde, $fecha_hasta, $profesional)
{
    global $conn;

    // Preparar la consulta SQL base para obtener pacientes
    $sql = "SELECT * FROM paciente WHERE 1";

    // Aplicar filtro por fecha desde
    if (!empty($fecha_desde)) {
        $sql .= " AND DATE(fecha) >= '$fecha_desde'";
    }

    // Aplicar filtro por fecha hasta
    if (!empty($fecha_hasta)) {
        $sql .= " AND DATE(fecha) <= '$fecha_hasta'";
    }

    // Aplicar filtro por profesional
    if (!empty($profesional)) {
        $sql .= " AND cod_prof = $profesional";
    }

    // Añadir orden por fecha
    $sql .= " ORDER BY fecha ASC";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron pacientes
    if ($result->num_rows > 0) {
        // Inicializar array para almacenar pacientes
        $pacientes = array();

        // Iterar sobre los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $pacientes[] = $row;
        }

        // Devolver array de pacientes
        return $pacientes;
    } else {
        // Devolver un array vacío si no se encontraron pacientes
        return array();
    }
}


function obtenerTotalPacientesParaProfesional($profesional, $fecha_desde, $fecha_hasta)
{
    global $conn;

    // Preparar la consulta SQL para obtener el total de pacientes para un profesional específico dentro del rango de fechas
    $sql = "SELECT COUNT(*) AS total FROM paciente WHERE cod_prof = ?";

    // Agregar condiciones para el rango de fechas si están proporcionadas
    if (!empty($fecha_desde)) {
        $sql .= " AND DATE(fecha) >= ?";
    }
    if (!empty($fecha_hasta)) {
        $sql .= " AND DATE(fecha) <= ?";
    }

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    if (!empty($fecha_desde) && !empty($fecha_hasta)) {
        $stmt->bind_param("iss", $profesional, $fecha_desde, $fecha_hasta);
    } elseif (!empty($fecha_desde)) {
        $stmt->bind_param("is", $profesional, $fecha_desde);
    } elseif (!empty($fecha_hasta)) {
        $stmt->bind_param("is", $profesional, $fecha_hasta);
    } else {
        $stmt->bind_param("i", $profesional);
    }

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el total de pacientes
    if ($result->num_rows > 0) {
        // Obtener el total de pacientes
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        // Devolver 0 si no se encontró ningún paciente para el profesional especificado
        return 0;
    }
}

function obtenerTotalPacientes()
{
    global $conn;

    // Preparar la consulta SQL para obtener el total de pacientes
    $sql = "SELECT COUNT(*) AS total FROM paciente";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Obtener el resultado
    $row = $result->fetch_assoc();

    // Devolver el total de pacientes
    return $row['total'];
}

function obtenerNombreYApellidoPorDNI($dni)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT nombreYapellido, benef FROM padron WHERE dni = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $dni);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el nombre y apellido
    if ($result->num_rows > 0) {
        // Devolver el nombre, apellido y beneficio
        $row = $result->fetch_assoc();
        return array(
            'nombreYapellido' => $row['nombreYapellido'],
            'benef' => $row['benef']
        );
    } else {
        // Devolver false si no se encontró el nombre y apellido
        return false;
    }
}

if (isset($_GET['verificarDni']) && isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    // Obtener nombre, apellido y beneficio por DNI
    $datos = obtenerNombreYApellidoPorDNI($dni);

    if ($datos) {
        echo json_encode(array('success' => true, 'nombreYapellido' => $datos['nombreYapellido'], 'benef' => $datos['benef']));
    } else {
        // Si el DNI no existe en el padron, devolver un mensaje indicando que no se encontró
        echo json_encode(array('success' => false, 'message' => 'Completar nombre y apellido'));
    }
}




function obtenerNombreYApellidoPorBeneficio($beneficio)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT nombreYapellido,dni FROM padron WHERE benef = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $beneficio);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el nombre y apellido
    if ($result->num_rows > 0) {
        // Devolver el nombre y apellido
        $row = $result->fetch_assoc();
        return array(
            'nombreYapellido' => $row['nombreYapellido'],
            'dni' => $row['dni']
        );
    } else {
        // Devolver false si no se encontró el nombre y apellido
        return false;
    }
}

// Procesar la solicitud de verificación del número de beneficio
if (isset($_GET['verificarBeneficio']) && isset($_GET['benef'])) {
    $beneficio = $_GET['benef'];
    $datos = obtenerNombreYApellidoPorBeneficio($beneficio);
    $completar = 'Completar con nombre y apellido';
    if ($datos) {
        echo json_encode(array('success' => true, 'nombreYapellido' => $datos['nombreYapellido'], 'dni' => $datos['dni']));
    } else {
        // Si no se reciben los parámetros esperados, devolver un mensaje de error
        echo json_encode(array('success' => false, 'message' => 'Completar nombre y apellido'));

    }
    exit();
}




function obtenerNombreProfesional($cod_prof)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT nombre, apellido FROM prof WHERE cod_prof = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $cod_prof);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el profesional
    if ($result->num_rows > 0) {
        // Obtener el nombre y apellido del profesional
        $row = $result->fetch_assoc();
        return $row['apellido'] . ' ' . $row['nombre'];
    } else {
        // Devolver false si no se encontró el profesional
        return false;
    }
}


function actualizarEstadoCargado($cod_paci, $nuevo_estado)
{
    global $conn;

    // Preparar la consulta SQL para actualizar el estado 'cargado' del paciente
    $sql = "UPDATE paciente SET cargado = ? WHERE cod_paci = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la sentencia fue exitosa
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("si", $nuevo_estado, $cod_paci);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        return true; // La actualización se realizó con éxito
    } else {
        // Si hay un error en la ejecución, mostrar el mensaje de error
        die("Error al ejecutar la consulta: " . $stmt->error);
    }
}



// Función para obtener los pacientes de un profesional específico
function obtenerPacientesPorProfesional($cod_prof, $limite, $offset)
{
    global $conn;

    // Preparar la consulta SQL para obtener los pacientes del profesional con LIMIT y OFFSET
    $sql = "SELECT p.*, pr.apellido AS nom_prof 
        FROM paciente p 
        LEFT JOIN prof pr ON pr.cod_prof = p.cod_prof  
        WHERE p.cod_prof = ? 
        ORDER BY p.fecha DESC 
        LIMIT ? OFFSET ?";


    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros: cod_prof (int), limite (int), offset (int)
    $stmt->bind_param("iii", $cod_prof, $limite, $offset);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontraron pacientes
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los pacientes
        $pacientes = array();

        // Iterar sobre los resultados
        while ($row = $result->fetch_assoc()) {
            $pacientes[] = $row;
        }

        // Devolver el array de pacientes
        return $pacientes;
    } else {
        // Si no se encontraron pacientes, devolver un array vacío
        return array();
    }
}

function contarPacientesPorProfesional($cod_prof)
{
    global $conn;

    // Preparar la consulta SQL para contar los pacientes del profesional
    $sql = "SELECT COUNT(*) as total FROM paciente WHERE cod_prof = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("i", $cod_prof);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró un resultado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (int) $row['total']; // Devolver el total de pacientes
    } else {
        return 0; // Si no hay pacientes, devolver 0
    }
}



if (isset($_GET['obtenerPacientesPorProfesional']) && isset($_GET['cod_prof'])) {
    $cod_prof = $_GET['cod_prof'];

    // Obtener el límite y el offset, proporcionando valores predeterminados
    $limite = isset($_GET['limite']) ? (int) $_GET['limite'] : 50; // Por defecto, 50 pacientes por página
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0; // Por defecto, inicio desde la primera página

    // Obtener los pacientes para el profesional con paginación
    $pacientes = obtenerPacientesPorProfesional($cod_prof, $limite, $offset);

    // Contar el total de pacientes para la paginación
    $totalPacientes = contarPacientesPorProfesional($cod_prof);

    // Devolver la lista de pacientes y el total en un solo objeto JSON
    echo json_encode([
        'pacientes' => $pacientes,
        'total' => $totalPacientes
    ]);
    exit();
}

// Procesar la solicitud de obtener el nombre del profesional por su código
if (isset($_GET['obtenerNombreProfesional']) && isset($_GET['cod_prof'])) {
    $cod_prof = $_GET['cod_prof'];
    $nombreProfesional = obtenerNombreProfesional($cod_prof);
    if ($nombreProfesional) {
        echo json_encode(array('success' => true, 'nombreProfesional' => $nombreProfesional));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Profesional no encontrado'));
    }
    exit();
}




function obtenerEspecialidadProfesional($cod_prof)
{
    global $conn;
    $sql = "SELECT especialidad FROM prof WHERE cod_prof = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);
    // Vincular parámetro
    $stmt->bind_param("i", $cod_prof);
    // Ejecutar consulta
    $stmt->execute();
    // Obtener resultado
    $result = $stmt->get_result();

    // Verificar si se encontró la especialidad
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['especialidad'];
    } else {
        return "Especialidad no especificada"; // O un mensaje apropiado si no se encuentra la especialidad
    }
}

function obtenerDescripcionPractica($cod_practica)
{
    global $conn;
    $sql = "SELECT descript FROM tipo_prac WHERE cod_practica  = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);
    // Vincular parámetro
    $stmt->bind_param("i", $cod_practica);
    // Ejecutar consulta
    $stmt->execute();
    // Obtener resultado
    $result = $stmt->get_result();

    // Verificar si se encontró la especialidad
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['descript'];
    } else {
        return "Sin descripcion"; // O un mensaje apropiado si no se encuentra la especialidad
    }
}

function obtenerProfesionales()
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT cod_prof, nombre, apellido FROM prof";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron profesionales
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los profesionales
        $profesionales = array();

        // Iterar sobre los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $profesionales[] = $row;
        }

        // Devolver el array de profesionales
        return $profesionales;
    } else {
        // Devolver un array vacío si no se encontraron profesionales
        return array();
    }
}



function obtenerCodigosPractica()
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT cod_practica FROM tipo_prac";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron códigos de práctica
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los códigos de práctica
        $codigos_practica = array();

        // Iterar sobre los resultados y almacenar los códigos de práctica en el array
        while ($row = $result->fetch_assoc()) {
            $codigos_practica[] = $row['cod_practica'];
        }

        // Devolver el array de códigos de práctica
        return $codigos_practica;
    } else {
        // Devolver un array vacío si no se encontraron códigos de práctica
        return array();
    }
}

function obtenerDiagnosticoConDescripcion()
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT cod_diag, descript FROM diagnostico";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron diagnósticos
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los diagnósticos con descripciones
        $diagnosticos = array();

        // Iterar sobre los resultados y almacenar cada diagnóstico con su descripción en el array
        while ($row = $result->fetch_assoc()) {
            $diagnosticos[] = $row;
        }

        // Devolver el array de diagnósticos con descripciones
        return $diagnosticos;
    } else {
        // Devolver un array vacío si no se encontraron diagnósticos
        return array();
    }
}

// Procesar la solicitud de buscar por nombre y apellido
if (isset($_GET['buscarPorNombreApellido']) && isset($_GET['nombreYapellido'])) {
    $nombreYapellido = $_GET['nombreYapellido'];
    $datos = obtenerBeneficioPorNombreYApellido($nombreYapellido);
    if ($datos) {
        echo json_encode(array('success' => true, 'benef' => $datos['benef'], 'dni' => $datos['dni']));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Beneficio no encontrado'));
    }
    exit();
}

function obtenerBeneficioPorNombreYApellido($nombreYapellido)
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT benef,dni FROM padron WHERE nombreYapellido = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("s", $nombreYapellido);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró el beneficio
    if ($result->num_rows > 0) {
        // Obtener el beneficio
        $row = $result->fetch_assoc();
        return array(
            'benef' => $row['benef'],
            'dni' => $row['dni']
        );
    } else {
        // Devolver false si no se encontró el beneficio
        return false;
    }
}

// Función para obtener los códigos de práctica
function obtenerCodigosPracticaEditar()
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT cod_practica FROM tipo_prac";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron códigos de práctica
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los códigos de práctica
        $codigos_practica = array();

        // Iterar sobre los resultados y almacenar los códigos de práctica en el array
        while ($row = $result->fetch_assoc()) {
            $codigos_practica[] = $row['cod_practica'];
        }

        // Devolver el array de códigos de práctica
        echo json_encode($codigos_practica);
        exit;
    } else {
        // Devolver un array vacío si no se encontraron códigos de práctica
        echo json_encode(array());
        exit;
    }
}

// Función para obtener diagnósticos con descripción
function obtenerDiagnosticosConDescripcionEditar()
{
    global $conn;

    // Preparar la consulta SQL
    $sql = "SELECT cod_diag, descript FROM diagnostico";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron diagnósticos
    if ($result->num_rows > 0) {
        // Inicializar un array para almacenar los diagnósticos con descripciones
        $diagnosticos = array();

        // Iterar sobre los resultados y almacenar cada diagnóstico con su descripción en el array
        while ($row = $result->fetch_assoc()) {
            $diagnosticos[] = $row;
        }

        // Devolver el array de diagnósticos con descripciones
        echo json_encode($diagnosticos);
        exit;
    } else {
        // Devolver un array vacío si no se encontraron diagnósticos
        echo json_encode(array());
        exit;
    }
}

// Manejar la solicitud AJAX para obtener diagnósticos y códigos de práctica
if (isset($_GET['obtenerDiagnosticos'])) {
    obtenerDiagnosticosConDescripcionEditar();
}

if (isset($_GET['obtenerCodigosPractica'])) {
    obtenerCodigosPracticaEditar();
}

?>