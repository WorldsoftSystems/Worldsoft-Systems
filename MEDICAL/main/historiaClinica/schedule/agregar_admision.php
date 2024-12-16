<?php
require_once "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados por POST
    var_dump($_POST);

    // Validar los datos
    $id_paciente = isset($_POST['id_paciente']) ? $_POST['id_paciente'] : '';
    $id_responsable = isset($_POST['id_responsable']) ? $_POST['id_responsable'] : '';
    $id_diag = isset($_POST['id_diag']) ? $_POST['id_diag'] : '';
    $id_prof = isset($_POST['id_prof']) ? $_POST['id_prof'] : '';
    $id_medicamento = isset($_POST['id_medicamento']) ? $_POST['id_medicamento'] : '';
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
    $hc_cada_medi = isset($_POST['hc_cada_medi']) ? $_POST['hc_cada_medi'] : '';
    $hc_desc_medi = isset($_POST['hc_desc_medi']) ? $_POST['hc_desc_medi'] : '';
    $hc_fecha = isset($_POST['hc_fecha']) ? $_POST['hc_fecha'] : '';

    

    // Iniciar una transacción
    $conn->begin_transaction();

    $stmt1 = null;
    $stmt2 = null;
    $stmt3 = null;

    try {
        // Primera inserción en hc_admision_ambulatorio
        $sql1 = "INSERT INTO hc_admision_ambulatorio (
            id_paciente, 
            id_responsable,
            id_prof,
            asc_psiquico, 
            act_psiquica, 
            act, 
            orientacion, 
            conciencia, 
            memoria, 
            atencion, 
            pensamiento, 
            cont_pensamiento, 
            sensopercepcion, 
            afectividad, 
            inteligencia, 
            juicio, 
            esfinteres, 
            tratamiento, 
            evolucion,
            hc_cada_medi,
            hc_desc_medi,
            hc_fecha
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param(
            "iiisssssssssssssssssss",
            $id_paciente,
            $id_responsable,
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
            $hc_cada_medi,
            $hc_desc_medi,
            $hc_fecha
        );

        if (!$stmt1->execute()) {
            throw new Exception("Error en la primera inserción: " . $stmt1->error);
        }

        // Segunda inserción en paci_diag
        $codigo = $id_diag; // Generar el código a partir de id_diag
        $sql2 = "INSERT INTO paci_diag (id_paciente, fecha, codigo) VALUES (?, ?, ?)";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("isi", $id_paciente, $hc_fecha, $codigo);

        if (!$stmt2->execute()) {
            throw new Exception("Error en la segunda inserción: " . $stmt2->error);
        }

        // Obtén la hora actual en el formato "H:i:s"
        $hora_actual = date("H:i:s");

        // Define una variable para la dosis
        $dosis = 1;

        $sql3 = "INSERT INTO medicacion_paci(id_paciente, medicamento, fecha, hora, dosis) VALUES (?, ?, ?, ?, ?)";

        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("iissi", $id_paciente, $id_medicamento, $hc_fecha, $hora_actual, $dosis);

        if (!$stmt3->execute()) {
            throw new Exception("Error en la tercera inserción: " . $stmt3->error);
        }

        // Confirmar la transacción
        $conn->commit();
        echo "Datos guardados correctamente";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo $e->getMessage();
    } finally {
        if ($stmt1) $stmt1->close();
        if ($stmt2) $stmt2->close();
        if ($stmt3) $stmt3->close();
        $conn->close();
    }
}
?>
