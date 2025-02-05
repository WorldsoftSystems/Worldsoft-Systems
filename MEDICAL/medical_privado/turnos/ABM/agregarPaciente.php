<?php

include('../../conexion.php');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del POST
    $nombre = !empty($_POST['nombre']) ? $_POST['nombre'] : null;
    $obra_social = !empty($_POST['obra_social']) ? $_POST['obra_social'] : null;
    $fecha_nac = !empty($_POST['fecha_nac']) ? $_POST['fecha_nac'] : null;
    $sexo = !empty($_POST['sexo']) ? $_POST['sexo'] : null;
    $tipo_doc = !empty($_POST['tipo_doc']) ? $_POST['tipo_doc'] : null;
    $nro_doc = !empty($_POST['nro_doc']) ? $_POST['nro_doc'] : null;
    $admision = !empty($_POST['admision']) ? $_POST['admision'] : null;
    $id_prof = !empty($_POST['id_prof']) ? $_POST['id_prof'] : null;
    $benef = !empty($_POST['benef']) ? $_POST['benef'] : 0;
    $tipo_afiliado = !empty($_POST['tipo_afiliado']) ? $_POST['tipo_afiliado'] : null;
    $boca_atencion = !empty($_POST['boca_atencion']) ? $_POST['boca_atencion'] : null;
    $modalidad_act = !empty($_POST['modalidad_act']) ? $_POST['modalidad_act'] : null;
    $hora_admision = !empty($_POST['hora_admision']) ? $_POST['hora_admision'] : null;


    // Verificar si el paciente ya existe
    $sql_check = "SELECT id FROM paciente WHERE benef = ? AND nombre = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $benef,$nombre);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'El paciente ya está registrado.';
    } else {
        // Insertar el nuevo paciente
        $sql = "INSERT INTO paciente (nombre, obra_social, fecha_nac, sexo, tipo_doc, nro_doc, admision, id_prof, benef, tipo_afiliado, boca_atencion,hora_admision) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssisisiis",
            $nombre,
            $obra_social,
            $fecha_nac,
            $sexo,
            $tipo_doc,
            $nro_doc,
            $admision,
            $id_prof,
            $benef,
            $tipo_afiliado,
            $boca_atencion,
            $hora_admision
        );

        if ($stmt->execute()) {
            $id_paciente = $conn->insert_id;

            $nombre_concatenado = sprintf(
                "%s - %s", // Cambiado para que haya solo dos marcadores de posición
                strtoupper($nombre), // Asegurarte de que el nombre esté en mayúsculas
                $benef
            );



            if (!is_null($modalidad_act)) { // Verifica que modalidad_act tenga un valor válido
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

                // Mover el cierre de $stmt_modalidad aquí, dentro del if
                $stmt_modalidad->close();
            } else {
                // Si modalidad_act es null, igual se debe considerar la inserción exitosa
                $response['success'] = true;
                $response['message'] = 'Paciente agregado correctamente.';
                $response['id'] = $id_paciente;
                $response['nombre'] = $nombre_concatenado;
            }

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