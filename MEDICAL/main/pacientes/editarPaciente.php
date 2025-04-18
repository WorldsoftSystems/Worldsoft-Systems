<?php
require_once "../conexion.php";

$response = array('success' => false, 'message' => '');

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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
    $nro_hist_amb = $_POST['nro_hist_amb'];
    $nro_hist_int = $_POST['nro_hist_int'];
    $hora_admision = $_POST['hora_admision'];
    $ugl_id = $_POST['ugl_paciente']; // Este es el ID o la descripción seleccionada
    $nro_de_tramite = $_POST['nro_de_tramite'];

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
    }

    // Verifica si se obtuvo el ID correctamente
    if (!$ugl_id) {
        $response['success'] = false;
        $response['message'] = 'No se encontró un UGL correspondiente.';
        echo json_encode($response);
        exit();
    }


    // Verificar si existe otro paciente con el mismo benef y parentesco
    $sql_check = "SELECT id FROM paciente WHERE benef = ? AND parentesco = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("isi", $benef, $parentesco, $id);  // Cambié 'iii' a 'isi' porque 'parentesco' es varchar
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si existe otro paciente con el mismo benef y parentesco, enviar respuesta de error
        $response['success'] = false;
        $response['message'] = 'Ya existe otro paciente con el mismo beneficiario y parentesco.';
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
                    ugl_paciente = ?,
                    parentesco = ?, 
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
            "sisssssissisiiisisiiiissi",
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
            $ugl_id,
            $parentesco,   // varchar
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
            // Actualizar en la tabla paci_modalidad solo la modalidad más antigua (primera ingresada)
            $sql_modalidad = "UPDATE paci_modalidad 
                                SET modalidad = ?, fecha = ? 
                                WHERE id_paciente = ? 
                                AND fecha = (
                                    SELECT MIN(fecha) 
                                    FROM paci_modalidad 
                                    WHERE id_paciente = ?) ";

            // Preparamos y ejecutamos la consulta
            $stmt_modalidad = $conn->prepare($sql_modalidad);
            $stmt_modalidad->bind_param("isii", $modalidad_act, $admision, $id, $id);

            if ($stmt_modalidad->execute()) {
                $response['success'] = true;
                $response['message'] = 'Paciente actualizado correctamente.';
            } else {
                $response['message'] = 'Error al actualizar la modalidad del paciente: ' . $stmt_modalidad->error;
            }

            $stmt_modalidad->close();

        } else {
            $response['message'] = 'Error al actualizar el paciente: ' . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();
}

// Envía la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

?>