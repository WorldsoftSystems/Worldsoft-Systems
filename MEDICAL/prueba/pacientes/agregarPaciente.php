<?php
require_once "../conexion.php";

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del POST
    $nombre = $_POST['nombre'];
    $obra_social = $_POST['obra_social'];
    $fecha_nac = $_POST['fecha_nac'];
    $sexo = $_POST['sexo'];
    $domicilio = $_POST['domicilio'];
    $localidad = $_POST['localidad'];
    $partido = $_POST['partido'];
    $c_postal = $_POST['c_postal'];
    $telefono = $_POST['telefono'];
    $tipo_doc = $_POST['tipo_doc'];
    $nro_doc = $_POST['nro_doc'];
    $admision = $_POST['admision'];
    $id_prof = $_POST['id_prof'];
    $benef = $_POST['benef'];
    $parentesco = $_POST['parentesco'];
    $hijos = $_POST['hijos'];
    $ocupacion = $_POST['ocupacion'];
    $tipo_afiliado = $_POST['tipo_afiliado'];
    $boca_atencion = $_POST['boca_atencion'];
    $modalidad_act = $_POST['modalidad_act'];
    $hora_admision = $_POST['hora_admision'];
    $tipo_historia = $_POST['tipo_historia'];
    $ugl_descripcion = $_POST['ugl_paciente'];  // Este es el texto (descripción) enviado por el formulario

    // 1. Aquí va la consulta SQL para convertir la descripción a ID:
    $sql_ugl = "SELECT id FROM codigo_ugl WHERE descripcion = ?";
    $stmt_ugl = $conn->prepare($sql_ugl);
    $stmt_ugl->bind_param("s", $ugl_descripcion);  // Vinculamos la descripción como parámetro
    $stmt_ugl->execute();
    $stmt_ugl->bind_result($ugl_id);  // El resultado será el ID del UGL
    $stmt_ugl->fetch();
    $stmt_ugl->close();

    // Si no se encuentra el UGL, devolvemos un error
    if (!$ugl_id) {
        $response['success'] = false;
        $response['message'] = 'No se encontró un UGL correspondiente.';
        echo json_encode($response);
        exit();
    }


    // Obtener los valores actuales de num_hist_amb y num_hist_int desde la tabla parametro_sistema
    $sql_param = "SELECT num_hist_amb, num_hist_int FROM parametro_sistema"; // Asegúrate que `id = 1` es correcto para tu caso
    $result_param = $conn->query($sql_param);
    if ($result_param->num_rows > 0) {
        $row_param = $result_param->fetch_assoc();
        $nro_hist_amb = $row_param['num_hist_amb'];
        $nro_hist_int = $row_param['num_hist_int'];

        // Asignar el número de historia clínica según la elección del usuario
        if ($tipo_historia == 'amb') {
            $nro_hist_clinica_amb = $nro_hist_amb;
            $nro_hist_clinica_int = 0; // Internación en 0
        } else {
            $nro_hist_clinica_amb = 0; // Ambulatoria en 0
            $nro_hist_clinica_int = $nro_hist_int;
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
            $sql = "INSERT INTO paciente (nombre, obra_social, fecha_nac, sexo, domicilio, localidad, partido, c_postal, telefono, tipo_doc, nro_doc, admision, id_prof, benef, parentesco, hijos, ocupacion, tipo_afiliado, boca_atencion, nro_hist_amb, nro_hist_int, hora_admision, ugl_paciente) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssssssssssiisisiiiisi",
                $nombre,
                $obra_social,
                $fecha_nac,
                $sexo,
                $domicilio,
                $localidad,
                $partido,
                $c_postal,
                $telefono,
                $tipo_doc,
                $nro_doc,
                $admision,
                $id_prof,
                $benef,
                $parentesco,
                $hijos,
                $ocupacion,
                $tipo_afiliado,
                $boca_atencion,
                $nro_hist_clinica_amb, // Ambulatoria
                $nro_hist_clinica_int, // Internación
                $hora_admision,
                $ugl_id
            );

            if ($stmt->execute()) {
                $id_paciente = $conn->insert_id;

                // Insertar la modalidad
                $sql_modalidad = "INSERT INTO paci_modalidad (id_paciente, modalidad, fecha) VALUES (?, ?, ?)";
                $stmt_modalidad = $conn->prepare($sql_modalidad);
                $stmt_modalidad->bind_param("iis", $id_paciente, $modalidad_act, $admision);

                if ($stmt_modalidad->execute()) {
                    // Incrementar solo el campo seleccionado por el usuario
                    if ($tipo_historia == 'amb') {
                        $sql_update_param = "UPDATE parametro_sistema SET num_hist_amb = num_hist_amb + 1";
                    } else {
                        $sql_update_param = "UPDATE parametro_sistema SET num_hist_int = num_hist_int + 1";
                    }
                    $conn->query($sql_update_param);

                    $response['success'] = true;
                    $response['message'] = 'Paciente agregado correctamente.';
                    $response['id'] = $id_paciente;
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
    } else {
        $response['success'] = false;
        $response['message'] = 'No se encontraron parámetros del sistema.';
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

header('Content-Type: application/json');
echo json_encode($response);

?>