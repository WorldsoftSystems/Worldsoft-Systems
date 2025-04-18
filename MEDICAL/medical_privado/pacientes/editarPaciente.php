<?php
require_once "../conexion.php";

$response = array('success' => false, 'message' => '');

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asignar valores con isset() para evitar errores cuando no están definidos
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $obra_social = isset($_POST['obra_social']) ? $_POST['obra_social'] : null;
    $fecha_nac = isset($_POST['fecha_nac']) ? $_POST['fecha_nac'] : null;
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : null;
    $domicilio = isset($_POST['domicilio']) ? $_POST['domicilio'] : null;
    $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : null;
    $partido = isset($_POST['partido']) ? $_POST['partido'] : null;
    $c_postal = isset($_POST['c_postal']) ? $_POST['c_postal'] : null;
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
    $tipo_doc = isset($_POST['tipo_doc']) ? $_POST['tipo_doc'] : null;
    $nro_doc = isset($_POST['nro_doc']) ? $_POST['nro_doc'] : null;
    $admision = isset($_POST['admision']) ? $_POST['admision'] : null;
    $id_prof = isset($_POST['id_prof']) ? $_POST['id_prof'] : null;
    $benef = isset($_POST['benef']) ? $_POST['benef'] : null;
    $hijos = isset($_POST['hijos']) ? $_POST['hijos'] : null;
    $ocupacion = isset($_POST['ocupacion']) ? $_POST['ocupacion'] : null;
    $tipo_afiliado = isset($_POST['tipo_afiliado']) ? $_POST['tipo_afiliado'] : null;
    $boca_atencion = isset($_POST['boca_atencion']) ? $_POST['boca_atencion'] : null;
    $modalidad_act = isset($_POST['modalidad_act']) ? $_POST['modalidad_act'] : null;
    $nro_hist_amb = isset($_POST['nro_hist_amb']) ? $_POST['nro_hist_amb'] : null;
    $nro_hist_int = isset($_POST['nro_hist_int']) ? $_POST['nro_hist_int'] : null;
    $hora_admision = isset($_POST['hora_admision']) ? $_POST['hora_admision'] : null;
    $nro_de_tramite = isset($_POST['nro_de_tramite']) ? $_POST['nro_de_tramite'] : null;

    // Verificar si existe otro paciente con el mismo benef
    $sql_check = "SELECT id FROM paciente WHERE benef = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $benef, $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si existe otro paciente con el mismo benef, enviar respuesta de error
        $response['success'] = false;
        $response['message'] = 'Ya existe otro paciente con el mismo beneficiario.';
    } else {
        // Actualizar paciente
        $sql = "UPDATE paciente SET 
                    nombre = ?, 
                    obra_social = ?, 
                    fecha_nac = ?, 
                    sexo = ?, 
                    domicilio = ?, 
                    localidad = ?, 
                    partido = ?, 
                    c_postal = ?, 
                    telefono = ?, 
                    tipo_doc = ?, 
                    nro_doc = ?, 
                    admision = ?, 
                    id_prof = ?, 
                    benef = ?, 
                    hijos = ?, 
                    ocupacion = ?, 
                    tipo_afiliado = ?,
                    boca_atencion = ?,
                    nro_hist_amb = ?,
                    nro_hist_int = ?,
                    hora_admision = ?,
                    nro_de_tramite = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssssissisisssiissssi",
            $nombre,
            $obra_social,  // int
            $fecha_nac,    // date
            $sexo,
            $domicilio,
            $localidad,
            $partido,
            $c_postal,     // int
            $telefono,
            $tipo_doc,
            $nro_doc,      // int
            $admision,     // date
            $id_prof,      // int
            $benef,        // bigint
            $hijos,        // int
            $ocupacion,
            $tipo_afiliado, // int
            $boca_atencion, // int
            $nro_hist_amb,
            $nro_hist_int,
            $hora_admision,
            $nro_de_tramite,
            $id            // int
        );

        if ($stmt->execute()) {
            // Verificar si el paciente tiene una modalidad registrada
            $sql_check = "SELECT COUNT(*) FROM paci_modalidad WHERE id_paciente = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count > 0) {
                // Si existe, actualizar la modalidad más antigua
                $sql_modalidad = "UPDATE paci_modalidad 
                                    SET modalidad = ?, fecha = ? 
                                    WHERE id_paciente = ? 
                                    AND fecha = (
                                        SELECT MIN(fecha) 
                                        FROM paci_modalidad 
                                        WHERE id_paciente = ?)";

                $stmt_modalidad = $conn->prepare($sql_modalidad);
                $stmt_modalidad->bind_param("isii", $modalidad_act, $admision, $id, $id);
            } else {
                // Si no existe, insertar un nuevo registro
                $sql_modalidad = "INSERT INTO paci_modalidad (id_paciente, modalidad, fecha) VALUES (?, ?, ?)";
                $stmt_modalidad = $conn->prepare($sql_modalidad);
                $stmt_modalidad->bind_param("iis", $id, $modalidad_act, $admision);
            }

            // Ejecutar la consulta de actualización o inserción
            if ($stmt_modalidad->execute()) {
                $response['success'] = true;
                $response['message'] = 'Paciente actualizado correctamente.';
            } else {
                $response['message'] = 'Error al actualizar o insertar la modalidad del paciente: ' . $stmt_modalidad->error;
            }

            $stmt_modalidad->close();
        } else {
            $response['message'] = 'Error al actualizar el paciente: ' . $stmt->error;
        }


        $stmt->close();
    }
}

// Envía la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

?>