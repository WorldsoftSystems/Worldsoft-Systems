<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados por POST
    var_dump($_POST);

    // Validar los datos
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $antecedentes = isset($_POST['admi_familiar']) ? $_POST['admi_familiar'] : '';
    $id_prof = isset($_POST['id_prof']) ? $_POST['id_prof'] : '';
    $asc_psiquico = isset($_POST['asc_psiquico']) ? $_POST['asc_psiquico'] : '';
    $act_psiquica = isset($_POST['act_psiquica']) ? $_POST['act_psiquica'] : '';
    $act = isset($_POST['act']) ? $_POST['act'] : '';
    $orientacion = isset($_POST['orientacion']) ? $_POST['orientacion'] : '';
    $conciencia = isset($_POST['conciencia']) ? $_POST['conciencia'] : '';
    $memoria = isset($_POST['memoria']) ? $_POST['memoria'] : '';
    $atencion = isset($_POST['atencion']) ? $_POST['atencion'] : '';
    $pensamiento = isset($_POST['pensamiento']) ? $_POST['pensamiento'] : '';
    $cont_pensamiento = isset($_POST['cont_pensamiento']) ? $_POST['cont_pensamiento'] : '';
    $sensopercepcion = isset($_POST['sensopercepcion']) ? $_POST['sensopercepcion'] : '';
    $afectividad = isset($_POST['afectividad']) ? $_POST['afectividad'] : '';
    $inteligencia = isset($_POST['inteligencia']) ? $_POST['inteligencia'] : '';
    $juicio = isset($_POST['juicio']) ? $_POST['juicio'] : '';
    $esfinteres = isset($_POST['esfinteres']) ? $_POST['esfinteres'] : '';
    $tratamiento = isset($_POST['tratamiento']) ? $_POST['tratamiento'] : '';
    $evolucion = isset($_POST['evolucion']) ? $_POST['evolucion'] : '';
    $hc_fecha = isset($_POST['hc_fecha']) ? $_POST['hc_fecha'] : '';

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar en hc_admision_ambulatorio
        $sql = "UPDATE hc_admision_ambulatorio SET
            antecedentes = ?,
            id_prof = ?,
            asc_psiquico = ?,
            act_psiquica = ?,
            act = ?,
            orientacion = ?,
            conciencia = ?,
            memoria = ?,
            atencion = ?,
            pensamiento = ?,
            cont_pensamiento = ?,
            sensopercepcion = ?,
            afectividad = ?,
            inteligencia = ?,
            juicio = ?,
            esfinteres = ?,
            tratamiento = ?,
            evolucion = ?,
            hc_fecha = ?
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssssssssssssssssi",
            $antecedentes,
            $id_prof,
            $asc_psiquico,
            $act_psiquica,
            $act,
            $orientacion,
            $conciencia,
            $memoria,
            $atencion,
            $pensamiento,
            $cont_pensamiento,
            $sensopercepcion,
            $afectividad,
            $inteligencia,
            $juicio,
            $esfinteres,
            $tratamiento,
            $evolucion,
            $hc_fecha,
            $id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error en la actualización: " . $stmt->error);
        }

        // Confirmar la transacción
        $conn->commit();
        echo "Datos actualizados correctamente";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        $conn->rollback();
    } finally {
        if ($stmt) $stmt->close();
        $conn->close();
    }
}
?>
