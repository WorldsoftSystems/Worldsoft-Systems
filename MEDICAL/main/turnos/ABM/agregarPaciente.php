<?php
include('../../conexion.php');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del POST
    $nombre = $_POST['nombre'];
    $obra_social = $_POST['obra_social'];
    $fecha_nac = $_POST['fecha_nac'];
    $sexo = $_POST['sexo'];
    $tipo_doc = $_POST['tipo_doc'];
    $nro_doc = $_POST['nro_doc'];
    $admision = $_POST['admision'];
    $id_prof = $_POST['id_prof'];
    $benef = $_POST['benef'];
    $parentesco = $_POST['parentesco'];
    $tipo_afiliado = $_POST['tipo_afiliado'];
    $boca_atencion = $_POST['boca_atencion'];
    $modalidad_act = $_POST['modalidad_act'];
    $hora_admision = $_POST['hora_admision'];
    $ugl_id = $_POST['ugl_paciente']; // Este es el ID o la descripción seleccionada
    $telefono = $_POST['telefono'];
    // Agrega un registro en el log para verificar el valor recibido
    error_log("Valor recibido en 'ugl_paciente': $ugl_id");

    // Verifica si el valor recibido es un número (ID) o una descripción (texto)
    if (is_numeric($ugl_id)) {
        // Si es numérico, asumimos que es el ID
        $ugl_id = (int) $ugl_id;
        error_log("Interpretado como ID: $ugl_id");
    } else {
        // Si no es numérico, asumimos que es una descripción y hacemos la consulta para obtener el ID
        $sql_ugl = "SELECT id FROM codigo_ugl WHERE descripcion LIKE ?";
        $stmt_ugl = $conn->prepare($sql_ugl);
        $stmt_ugl->bind_param("s", $ugl_id); // Aquí usamos directamente $ugl_id como descripción
        $stmt_ugl->execute();
        $stmt_ugl->bind_result($ugl_id);
        $stmt_ugl->fetch();
        $stmt_ugl->close();

        error_log("ID obtenido para la descripción '$ugl_id': " . ($ugl_id ? $ugl_id : 'No encontrado'));
    }

    // Verifica si se obtuvo el ID correctamente
    if (!$ugl_id) {
        $response['success'] = false;
        $response['message'] = 'No se encontró un UGL correspondiente.';
        echo json_encode($response);
        exit();
    }




    // Verificar si el paciente ya existe
    $sql_check = "SELECT id FROM paciente WHERE benef = ? AND parentesco = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $benef, $parentesco);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'El paciente ya está registrado.';
    } else {
        // Insertar el nuevo paciente
        $sql = "INSERT INTO paciente (nombre, obra_social, fecha_nac, sexo, tipo_doc, nro_doc, admision, id_prof, benef, parentesco, tipo_afiliado, boca_atencion,hora_admision, ugl_paciente, telefono) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssssissiisis",
            $nombre,
            $obra_social,
            $fecha_nac,
            $sexo,
            $tipo_doc,
            $nro_doc,
            $admision,
            $id_prof,
            $benef,
            $parentesco,
            $tipo_afiliado,
            $boca_atencion,
            $hora_admision,
            $ugl_id,
            $telefono
        );

        if ($stmt->execute()) {
            $id_paciente = $conn->insert_id;

            // Construir el nombre concatenado
            $nombre_concatenado = sprintf(
                "%s - %s / %s",
                strtoupper($nombre), // Asegurarte de que el nombre esté en mayúsculas
                $benef,
                $parentesco
            );


            // Insertar la modalidad
            $sql_modalidad = "INSERT INTO paci_modalidad (id_paciente, modalidad, fecha) VALUES (?, ?, ?)";
            $stmt_modalidad = $conn->prepare($sql_modalidad);
            $stmt_modalidad->bind_param("iis", $id_paciente, $modalidad_act, $admision);

            if ($stmt_modalidad->execute()) {
                $response['success'] = true;
                $response['message'] = 'Paciente agregado correctamente.';
                $response['id'] = $id_paciente; // ID generado
                $response['nombre'] = $nombre_concatenado;  // Nombre del paciente
            } else {
                $response['success'] = false;
                $response['message'] = 'Error al agregar la modalidad del paciente: ' . $stmt_modalidad->error;
            }

            $stmt_modalidad->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Error al agregar el paciente: ' . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();


    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

header('Content-Type: application/json');
echo json_encode($response);


?>