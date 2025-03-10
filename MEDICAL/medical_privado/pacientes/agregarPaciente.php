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
    $hijos = $_POST['hijos'];
    $ocupacion = $_POST['ocupacion'];
    $tipo_afiliado = $_POST['tipo_afiliado'];
    $boca_atencion = $_POST['boca_atencion'];
    $modalidad_act = $_POST['modalidad_act'];
    $hora_admision = $_POST['hora_admision'];
    $nro_hist_amb = $_POST['nro_hist_amb'];
    $nro_hist_int = $_POST['nro_hist_int'];
    $nro_de_tramite = $_POST['nro_de_tramite'];



    // Verificar si el paciente ya existe
    $sql_check = "SELECT id FROM paciente WHERE benef = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $benef);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'El paciente ya está registrado.';
    } else {
        // Insertar el nuevo paciente
        $sql = "INSERT INTO paciente (nombre, obra_social, fecha_nac, sexo, domicilio, localidad, partido, c_postal, telefono, tipo_doc, nro_doc, admision, id_prof, benef, hijos, ocupacion, tipo_afiliado, boca_atencion, nro_hist_amb, nro_hist_int, hora_admision, nro_de_tramite) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssssissisisssiissss",
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
            $hijos,
            $ocupacion,
            $tipo_afiliado,
            $boca_atencion,
            $nro_hist_amb, // Ambulatoria
            $nro_hist_int, // Internación
            $hora_admision,
            $nro_de_tramite
        );

        if ($stmt->execute()) {
            $id_paciente = $conn->insert_id;

            // Insertar la modalidad
            $sql_modalidad = "INSERT INTO paci_modalidad (id_paciente, modalidad, fecha) VALUES (?, ?, ?)";
            $stmt_modalidad = $conn->prepare($sql_modalidad);
            $stmt_modalidad->bind_param("iis", $id_paciente, $modalidad_act, $admision);

            if ($stmt_modalidad->execute()) {
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


    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

header('Content-Type: application/json');
echo json_encode($response);

?>