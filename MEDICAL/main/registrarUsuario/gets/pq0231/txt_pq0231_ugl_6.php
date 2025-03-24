<?php

use PhpOffice\PhpSpreadsheet\Worksheet\Row;
// Conexión a la base de datos (modifica los datos de conexión según tu configuración)
include('../../../conexion.php');

// Verifica la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta SQL para obtener los datos de la tabla `parametro_sistema`
$sql = "SELECT cuit, inst, u_efect, c_pami, c_interno, mail, dir, puerta, clave_efect FROM parametro_sistema";
$result = $conn->query($sql);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    // Obtén los datos de la fila
    $row = $result->fetch_assoc();

    // Establece el huso horario
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    // Genera la fecha actual
    $fechaActual = date('d/m/Y');

    // Calcula el mes anterior en formato 08-24, por ejemplo
    $mesActual = date('m');
    $anoActual = date('y'); // Año en formato de 2 dígitos
    $mesAnterior = $mesActual - 1;

    // Si el mes anterior es 0 (es decir, estamos en enero), debemos ajustar a diciembre del año anterior
    if ($mesAnterior == 0) {
        $mesAnterior = 12;
        $anoActual = date('y', strtotime("-1 year")); // Restar un año si es diciembre
    }

    // Agregar 0 si el mes anterior es menor de 10
    if ($mesAnterior < 10) {
        $mesAnterior = '0' . $mesAnterior;
    }

    $periodo = $mesAnterior . '-' . $anoActual; // El resultado será, por ejemplo, 08-24


    // Definir el rango de fechas del mes anterior
    $fechaInicio = date('Y-m-d', strtotime("first day of -1 month"));
    $fechaFin = date('Y-m-d', strtotime("last day of -1 month"));


    // Crear la primera línea dinámica del archivo
    $cuit = $row['cuit'];
    $inst = $row['inst'];
    $u_efect = $row['u_efect'];
    $c_pami = $row['c_pami'];
    $clave_efect = $row['clave_efect'];
    $c_interno = $row['c_interno'];  // Nombre dinámico del archivo
    $mail = $row['mail'];
    $dir = $row['dir'];
    $puerta = $row['puerta'];

    // Primera línea dinámica
    $linea1 = "CABECERA\n";
    $linea2 = "30-60454669-5;;$fechaActual;$periodo;$inst;2;UP30604546695;69648\n";
    // Tercera línea con el texto 'PROFESIONAL'
    $linea3 = "PROFESIONAL\n";
    // Contenido inicial del archivo
    $contenido = $linea1 . $linea2 . $linea3;

    // Consulta SQL para obtener los profesionales con los nuevos campos
    $sqlProfesionales = "SELECT nombreYapellido, id_especialidad, matricula_n, matricula_p, tipo_doc, nro_doc FROM profesional";
    $resultProfesionales = $conn->query($sqlProfesionales);

    // Verifica si hay resultados en la tabla `profesional`
    if ($resultProfesionales->num_rows > 0) {
        // Itera sobre cada profesional y genera una nueva línea
        while ($profesional = $resultProfesionales->fetch_assoc()) {
            $nombreProfesional = $profesional['nombreYapellido'];
            $idEspecialidad = $profesional['id_especialidad'];
            $matriculaN = $profesional['matricula_n'];
            $matriculaP = $profesional['matricula_p'];
            $tipoDoc = $profesional['tipo_doc'];
            $nroDoc = $profesional['nro_doc'];

            // Verifica si el idEspecialidad es igual a 188
            if ($idEspecialidad == 188) {
                $idEspecialidad = 1000; // Cambia el valor a 1000 si es 188
            }

            // Guarda los datos en el array
            $profesionalesArray[] = array(
                'matriculaN' => $matriculaN
            );

            // Genera la línea para cada profesional
            $lineaProfesional = ";;;0;"
                . str_pad($nombreProfesional, 50) . ";"
                . $idEspecialidad . ";"
                . str_pad($matriculaN, 7) . ";"
                . str_pad($matriculaP, 7) . ";"
                . $tipoDoc . ";"
                . $nroDoc . ";;SIN SUMINISTRAR;0;;;;\n";

            // Agrega la línea al contenido
            $contenido .= $lineaProfesional;
        }
    } else {
        $contenido .= "No se encontraron profesionales.\n";
    }

    // Agregar la línea "PRESTADOR" después de todos los profesionales
    $contenido .= "PRESTADOR\n";
    $contenido .= ";$cuit;;;0;;;2;;0;$mail;01/01/2007;;;;0;0;0;$inst;$dir;$puerta;;;;;\n";

    $contenido .= "REL_PROFESIONALESXPRESTADOR\n";

    // Iterar sobre los profesionales para agregar las líneas correspondientes
    foreach ($profesionalesArray as $prof) {
        $matriculaN = $prof['matriculaN'];
        $contenido .= ";$cuit;$matriculaN;0;0;\n";
    }

    $contenido .= "BOCA_ATENCION\n";

    // Consulta SQL para obtener los profesionales con los nuevos campos
    $sqlBocas = "SELECT * FROM bocas_atencion";
    $resultBocas = $conn->query($sqlBocas);


    // Verifica si hay resultados en la tabla `bocas_atencion`
    if ($resultBocas->num_rows > 0) {
        // Itera sobre cada boca y genera una nueva línea
        while ($row = $resultBocas->fetch_assoc()) {
            $boca = $row['boca'];
            $puerta = $row['puerta'];
            $num_boca = $row['num_boca'];
            $ugl_boca = $row['ugl_boca'];

            // Genera la línea para cada boca de atención ugl_boca es INT
            $lineaBoca = ";$cuit;;0;$num_boca;$ugl_boca;$boca;$puerta;;;;\n";

            // Agrega la línea al contenido
            $contenido .= $lineaBoca;


        }
    } else {
        $contenido .= "No se encontraron bocas de atención.\n";
    }

    $contenido .= "REL_MODULOSXPRESTADOR\n";

    // Definir el valor base de los códigos
    $valores = [500, 503, 506, 508, 509, 522];

    // Recorre cada valor y genera la línea correspondiente
    foreach ($valores as $valor) {
        $contenido .= ";$cuit;;0;$valor;\n";
    }
    ;

    $contenido .= "BENEFICIO\n";

    // Consulta SQL para obtener los profesionales con los nuevos campos
    $sqlBenefs = "SELECT DISTINCT p.*
                 FROM paciente p
                 LEFT JOIN practicas practs ON p.id = practs.id_paciente
                 WHERE  (practs.fecha BETWEEN '$fechaInicio' AND '$fechaFin') AND p.obra_social = 4 AND p.ugl_paciente = 6
                 ";
    $resultBenef = $conn->query($sqlBenefs);

    // Verifica si hay resultados en la tabla `bocas_atencion`
    if ($resultBenef->num_rows > 0) {
        // Itera sobre cada boca y genera una nueva línea
        while ($row = $resultBenef->fetch_assoc()) {
            $benef = $row['benef'];
            $parentesco = $row['parentesco'];
            $admision = $row['admision'];

            // Si el valor de $benef es menor a 12, agrega un 0 al inicio
            if (strlen($benef) <= 11) {
                $benef = '0' . $benef;
            }


            // Formatea la fecha en 'dd/mm/yyyy'
            $fechaFormateada = date('d/m/Y', strtotime($admision));

            // Genera la línea para cada registro con el formato deseado
            $lineaBenef = ";;;$benef;;;1;$fechaFormateada\n";

            $contenido .= $lineaBenef;

        }
    } else {
        $contenido .= "No se encontraron benefs de atención.\n";
    }

    $contenido .= "AFILIADO\n";

    // Consulta SQL para obtener los profesionales con los nuevos campos
    $sqlPacis = "SELECT DISTINCT p.*
                 FROM paciente p
                 LEFT JOIN practicas practs ON p.id = practs.id_paciente
                 WHERE  practs.fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND p.obra_social = 4 AND p.ugl_paciente = 6
                 ";
    $resultPaci = $conn->query($sqlPacis);

    // Verifica si hay resultados en la tabla `bocas_atencion`
    if ($resultPaci->num_rows > 0) {
        // Itera sobre cada boca y genera una nueva línea
        while ($row = $resultPaci->fetch_assoc()) {
            $nombre = $row['nombre'];
            $tipo_doc = $row['tipo_doc'];
            $nro_doc = $row['nro_doc'];
            $fecha_nac = $row['fecha_nac'];
            $sexo = $row['sexo'];
            $benef = $row['benef'];
            $parentesco = $row['parentesco'];

            // Si el valor de $benef es menor a 12, agrega un 0 al inicio
            if (strlen($benef) <= 11) {
                $benef = '0' . $benef;
            }


            // Formatea la fecha en 'dd/mm/yyyy'
            $fechaFormateada = date('d/m/Y', strtotime($fecha_nac));


            // Genera la línea para cada registro con el formato deseado
            $lineaPaci = "$nombre;$tipo_doc;$nro_doc;;;; ; ;;;;;$fechaFormateada;$sexo;;;$benef;$parentesco;;;;;;;;\n";

            $contenido .= $lineaPaci;

        }
    } else {
        $contenido .= "No se encontraron pacientes .\n";
    }

    $contenido .= "PRESTACIONES\n";

    //AMBULATORIO
    $sqlAmbulatorioPsi = "WITH ValidRecords AS (
    SELECT
        p.id AS paciente_id,
        p.nombre,
        o.siglas,
        p.benef,
        p.parentesco,
        boca.num_boca AS boca_atencion,
        prof.matricula_n,
        p.tipo_afiliado,
        (SELECT pm.fecha
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
        ) AS ingreso_modalidad,
        p.sexo,
        COALESCE(
            (
                SELECT m.codigo
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad 
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
        ) AS modalidad_full,
        pract.fecha AS fecha_pract,
        pract.hora AS hora_pract,
        act_pract.codigo,
        (
            SELECT e.fecha_egreso
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad 
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            ) AND e.fecha_egreso BETWEEN '$fechaInicio' AND '$fechaFin' 
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS fecha_egreso,
        (
            SELECT e.motivo
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                WHERE pm.id_paciente = p.id
                AND pm.fecha <= pract.fecha 
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS motivo_egreso,
        d_id.codigo AS diag,
        pract.cant AS cantidad,
        CASE
            WHEN pract.fecha IS NOT NULL AND act_pract.codigo NOT IN ('520101', '521001') THEN pract.fecha
            ELSE NULL
        END AS valid_date
    FROM paciente p
    LEFT JOIN practicas pract ON pract.id_paciente = p.id
    LEFT JOIN actividades act_pract ON pract.actividad = act_pract.id
    LEFT JOIN obra_social o ON o.id = p.obra_social
    LEFT JOIN egresos e ON e.id_paciente = p.id
    LEFT JOIN modalidad m ON m.id = e.modalidad
    LEFT JOIN profesional prof ON prof.id_prof = p.id_prof 
    LEFT JOIN paci_diag d ON d.id_paciente = p.id
    LEFT JOIN diag d_id ON d_id.id = d.codigo
    LEFT JOIN bocas_atencion boca ON boca.id = p.boca_atencion
    WHERE pract.fecha BETWEEN '$fechaInicio' AND '$fechaFin'
      AND p.obra_social = 4 AND p.ugl_paciente = 6
)

SELECT 
    VR.nombre,
    VR.benef,
    VR.paciente_id,
    VR.tipo_afiliado,
    VR.matricula_n,
    VR.boca_atencion,
    VR.codigo,
    VR.fecha_pract,
    VR.fecha_egreso,
    VR.motivo_egreso,
    VR.hora_pract,
    VR.parentesco,
    VR.ingreso_modalidad,
    pop.op,  -- Múltiples filas por cada OP
    pop.modalidad_op,
    VR.sexo,
    VR.modalidad_full,
    VR.valid_date AS ult_atencion,
    VR.diag,
    VR.cantidad
FROM ValidRecords VR
LEFT JOIN paci_op pop 
    ON VR.paciente_id = pop.id_paciente 
    AND VR.modalidad_full = pop.modalidad_op  
    AND VR.fecha_pract BETWEEN pop.fecha AND pop.fecha_vencimiento
WHERE (VR.modalidad_full != '11' AND VR.modalidad_full != '12' AND VR.modalidad_full != '13')
ORDER BY VR.nombre ASC, pop.fecha DESC,VR.paciente_id ASC, VR.modalidad_full ASC, VR.fecha_pract;  -- Ordenamos para ver las OP más recientes arriba
";

    $resultPaciAmbulatorioPsi = $conn->query($sqlAmbulatorioPsi);

    // Verifica si hay resultados
    if ($resultPaciAmbulatorioPsi->num_rows > 0) {
        $current_paciente_id = null;
        $contenido_practicas = '';
        $current_modalidad = null;

        // Itera sobre cada paciente
        while ($row = $resultPaciAmbulatorioPsi->fetch_assoc()) {
            $id_paciente = $row['paciente_id'];
            $matricula_prof = $row['matricula_n'];
            $fecha_modalidad = $row['ingreso_modalidad'];
            $tipo_afiliado = $row['tipo_afiliado'];
            $modalidad = $row['modalidad_full'];
            $fecha_egreso = $row['fecha_egreso'];
            $tipo_egreso = $row['motivo_egreso'];
            $diagnostico_reciente = $row['diag'];
            $codigo = $row['codigo'];
            $fecha = $row['fecha_pract'];
            $hora = $row['hora_pract'];
            $cant = $row['cantidad'];
            $benef = $row['benef'];
            $parentesco = $row['parentesco'];
            $boca_atencion = $row['boca_atencion'];


            // Si el valor de $benef es menor a 12, agrega un 0 al inicio
            if (strlen($benef) <= 11) {
                $benef = '0' . $benef;
            }

            // Si fecha_egreso está vacía, asigna fechaFin y tipo_egreso como 8
            if (empty($fecha_egreso)) {
                $fecha_egreso = $fechaFin;
                $tipo_egreso = 8;
            }

            // Verifica si 'op' está vacío
            if (empty($row['op'])) {
                // Si no hay 'op', simplemente añade un ';'
                $op = '';
            } else {
                // Si hay 'op', usa su valor
                $op = $row['op'];
            }

            // Formatea la fecha en 'dd/mm/yyyy'
            $fechaFormateada_modalidad = date('d/m/Y', strtotime($fecha_modalidad));
            $fechaFormateada_egreso_amb = date('d/m/Y', strtotime($fecha_egreso));
            $fecha_practica = date('d/m/Y', strtotime($fecha));
            $hora_practica = date('H:i', strtotime($hora));

            // Si es un nuevo paciente, imprime los datos anteriores y comienza un nuevo bloque
            if ($current_paciente_id !== $id_paciente || $current_modalidad !== $row['modalidad_full']) {
                // Si ya tenemos datos de un paciente anterior, imprimimos el bloque "FIN AMBULATORIOPSI"
                if ($current_paciente_id !== null) {
                    // Añade el bloque de prácticas acumulado
                    $contenido .= "REL_PRACTICASREALIZADASXAMBULATORIOPSI\n";
                    $contenido .= $contenido_practicas;
                    $contenido .= "FIN AMBULATORIOPSI\n";
                }

                // Empieza un nuevo bloque de paciente
                $contenido .= "AMBULATORIOPSI \n";


                $modalidad_formateada = $modalidad; // Asigna el valor original por defecto
                // Construye la línea con la conversión de modalidad
                switch ($modalidad) {
                    case 10:
                        $modalidad_formateada = 4;
                        break;
                    case 8:
                        $modalidad_formateada = 4;
                        break;
                    case 9:
                        $modalidad_formateada = 4;
                        break;
                    default:
                        // Mantiene el valor original si no hay coincidencia
                        // Esto ya está manejado al asignar $modalidad_formateada al valor original
                        break;
                }


                $lineaAmbulatorioPsi = "$cuit;;$matricula_prof;0;0;0;$boca_atencion;0;$fechaFormateada_modalidad;;;$tipo_afiliado;$op;$modalidad_formateada;$benef;$parentesco;$fechaFormateada_egreso_amb;$tipo_egreso;\n";
                $contenido .= $lineaAmbulatorioPsi;

                $contenido .= "REL_DIAGNOSTICOSXAMBULATORIOPSI\n";
                $lineaAmbulatorioPsiDiag = ";;;0;1;$diagnostico_reciente;1\n";
                $contenido .= $lineaAmbulatorioPsiDiag;

                // Reinicia la variable para acumular las prácticas
                $contenido_practicas = '';
                $current_paciente_id = $id_paciente;
                $current_modalidad = $modalidad;
            }

            // Acumula las prácticas de este paciente
            $lineaAmbulatorioPsiPractica = ";;;0;1;$codigo;$fecha_practica $hora_practica;$cant;0;0\n";
            $contenido_practicas .= $lineaAmbulatorioPsiPractica;
        }

        // Añade el último bloque de prácticas para el último paciente
        if (!empty($contenido_practicas)) {
            $contenido .= "REL_PRACTICASREALIZADASXAMBULATORIOPSI\n";
            $contenido .= $contenido_practicas;
            $contenido .= "FIN AMBULATORIOPSI\n";
        }
    } else {
        $contenido .= "No se encontraron pacientes.\n";
    }


    //INTERNACION
    // Consulta SQL para obtener los datos necesarios
    $sqlInternacionPsi = "WITH ValidRecords AS (
    SELECT
        p.id AS paciente_id,
        p.nombre,
        o.siglas,
        p.benef,
        p.parentesco,
        boca.num_boca AS boca_atencion,
        prof.matricula_n,
        p.tipo_afiliado,
        p.hora_admision,
        p.nro_hist_int,
        (SELECT pm.fecha
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
        ) AS ingreso_modalidad,
        p.sexo,
        COALESCE(
            (
                SELECT m.codigo
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad 
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
        ) AS modalidad_full,
        pract.fecha AS fecha_pract,
        pract.hora AS hora_pract,
        act_pract.codigo,
         (
            SELECT e.hora_egreso
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                WHERE pm.id_paciente = p.id
                AND pm.fecha <= pract.fecha 
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS hora_egreso,
        (
            SELECT e.fecha_egreso
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad 
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            ) AND e.fecha_egreso BETWEEN '$fechaInicio' AND '$fechaFin' 
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS fecha_egreso,
        (
            SELECT e.motivo
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                WHERE pm.id_paciente = p.id
                AND pm.fecha <= pract.fecha 
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS motivo_egreso,
        d_id.codigo AS diag,
        pract.cant AS cantidad,
        CASE
            WHEN pract.fecha IS NOT NULL AND act_pract.codigo NOT IN ('520101', '521001') THEN pract.fecha
            ELSE NULL
        END AS valid_date
    FROM paciente p
    LEFT JOIN practicas pract ON pract.id_paciente = p.id
    LEFT JOIN actividades act_pract ON pract.actividad = act_pract.id
    LEFT JOIN obra_social o ON o.id = p.obra_social
    LEFT JOIN egresos e ON e.id_paciente = p.id
    LEFT JOIN modalidad m ON m.id = e.modalidad
    LEFT JOIN profesional prof ON prof.id_prof = p.id_prof 
    LEFT JOIN paci_diag d ON d.id_paciente = p.id
    LEFT JOIN diag d_id ON d_id.id = d.codigo
    LEFT JOIN bocas_atencion boca ON boca.id = p.boca_atencion
    WHERE pract.fecha BETWEEN '$fechaInicio' AND '$fechaFin'
      AND p.obra_social = 4  AND p.ugl_paciente = 6
)

SELECT 
    VR.nombre,
    VR.benef,
    VR.paciente_id,
    VR.tipo_afiliado,
    VR.matricula_n,
    VR.boca_atencion,
    VR.hora_egreso,
    VR.codigo,
    VR.fecha_pract,
    VR.fecha_egreso,
    VR.motivo_egreso,
    VR.hora_pract,
    VR.parentesco,
    VR.hora_admision,
    VR.nro_hist_int,
    VR.ingreso_modalidad,
    pop.op,  -- Múltiples filas por cada OP
    pop.modalidad_op,
    VR.sexo,
    VR.modalidad_full,
    VR.valid_date AS ult_atencion,
    VR.diag,
    VR.cantidad
FROM ValidRecords VR
LEFT JOIN paci_op pop 
    ON VR.paciente_id = pop.id_paciente 
    AND VR.modalidad_full = pop.modalidad_op  
    AND VR.fecha_pract BETWEEN pop.fecha AND pop.fecha_vencimiento
WHERE (VR.modalidad_full = '11' OR VR.modalidad_full = '12')
ORDER BY VR.nombre ASC, pop.fecha DESC,VR.paciente_id ASC, VR.modalidad_full ASC, VR.fecha_pract;  -- Ordenamos para ver las OP más recientes arriba


";

    $resultPaciInternacionPsi = $conn->query($sqlInternacionPsi);

    // Verifica si hay resultados
    if ($resultPaciInternacionPsi->num_rows > 0) {
        // Inicializamos la variable antes del bucle
        $current_paciente_id = null;
        $current_modalidad = null;  // Inicializar la modalidad como null
        $contenido_practicas = '';

        // Itera sobre cada paciente
        while ($row = $resultPaciInternacionPsi->fetch_assoc()) {
            $id_paciente = $row['paciente_id'];
            $matricula_prof = $row['matricula_n'];
            $matricula_practica = $row['matricula_n'];
            $fecha_modalidad = $row['ingreso_modalidad'];
            $tipo_afiliado = $row['tipo_afiliado'];
            $modalidad = $row['modalidad_full'];
            $fecha_egreso = $row['fecha_egreso'];
            $tipo_egreso = $row['motivo_egreso'];
            $diagnostico_reciente = $row['diag'];
            $codigo = $row['codigo'];
            $fecha = $row['fecha_pract'];
            $hora = $row['hora_pract'];
            $cant = $row['cantidad'];
            $benef = $row['benef'];
            $parentesco = $row['parentesco'];
            $boca_atencion = $row['boca_atencion'];
            $op = $row['op'];
            $admision = $row['ingreso_modalidad'];
            $hora_admision = $row['hora_admision'];
            $nro_hist_int = $row['nro_hist_int'];
            $hora_egreso = $row['hora_egreso'];

            $finalInternacionPsi = '';

            // Si fecha_egreso está vacía, asigna fechaFin y tipo_egreso como 8
            if (empty($fecha_egreso)) {
                $finalInternacionPsi = ";;";
                $cod_diag_egreso = "1";
            } else {
                $hora_egreso_formateada = date('H:i', strtotime($hora_egreso));
                $fechaFormateada_egreso_int = date('d/m/Y', strtotime($fecha_egreso));
                $finalInternacionPsi = "$fechaFormateada_egreso_int;$hora_egreso_formateada;$tipo_egreso";
                $cod_diag_egreso = "2";
            }

            // Verifica si 'op' está vacío
            if (empty($row['op'])) {
                // Si no hay 'op', simplemente añade un ';'
                $op = '';
            } else {
                // Si hay 'op', usa su valor
                $op = $row['op'];
            }



            // Formatea la fecha en 'dd/mm/yyyy'
            $fechaFormateada_modalidad = date('d/m/Y', strtotime($fecha_modalidad));

            $fecha_practica = date('d/m/Y', strtotime($fecha));
            $fecha_admision = date('d/m/Y', strtotime($admision));
            $hora_practica = date('H:i', strtotime($hora));
            $hora_admision = date('H:i', strtotime($hora_admision));
            // Si es un nuevo paciente, imprime los datos anteriores y comienza un nuevo bloque
            if ($current_paciente_id !== $id_paciente || $current_modalidad !== $row['modalidad_full']) {
                // Si ya tenemos datos de un paciente anterior, imprimimos el bloque "FIN INTERNACIONPSI"
                if ($current_paciente_id !== null) {
                    // Añade el bloque de prácticas acumulado
                    $contenido .= "REL_PRACTICASREALIZADASXINTERNACIONPSI\n";
                    $contenido .= $contenido_practicas;
                    $contenido .= "FIN INTERNACIONPSI\n";
                }

                // Empieza un nuevo bloque de paciente
                $contenido .= "INTERNACIONPSI \n";

                $modalidad_formateada = $modalidad; // Asigna el valor original por defecto
                // Construye la línea con la conversión de modalidad
                switch ($modalidad) {
                    case 11:
                        $modalidad_formateada = 6;
                        break;
                    case 12:
                        $modalidad_formateada = 5;
                        break;
                    default:
                        // Mantiene el valor original si no hay coincidencia
                        // Esto ya está manejado al asignar $modalidad_formateada al valor original
                        break;
                }


                // Si el valor de $benef es menor a 12, agrega un 0 al inicio
                if (strlen($benef) <= 11) {
                    $benef = '0' . $benef;
                }

                // Si es una nueva modalidad, usa la fecha de la modalidad
                $fecha_encabezado = $fechaFormateada_modalidad;

                $lineaInternacionPsi = "$cuit;;;0;0;0;$boca_atencion;;;$tipo_afiliado;$op;;$benef;$parentesco;$nro_hist_int;$modalidad_formateada;;;$fecha_encabezado;$hora_admision;$finalInternacionPsi\n";
                $contenido .= $lineaInternacionPsi;

                $contenido .= "REL_DIAGNOSTICOSXINTERNACIONPSI\n";
                $lineaInternacionPsiDiag = ";;;0;1;$diagnostico_reciente;$cod_diag_egreso;1\n";
                $contenido .= $lineaInternacionPsiDiag;

                // Reiniciar prácticas y modalidad
                $contenido_practicas = '';
                $current_paciente_id = $id_paciente;
                $current_modalidad = $modalidad;
            }

            // Acumula las prácticas de este paciente
            $lineaInternacionPsiPractica = ";$cuit;$matricula_practica;0;0;0;1;$codigo;$fecha_practica $hora_practica;$cant;1;$diagnostico_reciente\n";
            $contenido_practicas .= $lineaInternacionPsiPractica;
        }

        // Añade el último bloque de prácticas para el último paciente
        if (!empty($contenido_practicas)) {
            $contenido .= "REL_PRACTICASREALIZADASXINTERNACIONPSI\n";
            $contenido .= $contenido_practicas;
            $contenido .= "FIN INTERNACIONPSI\n";
        }
    } else {
        $contenido .= "No se encontraron pacientes.\n";
    }




    // Generar la respuesta JSON con el nombre del archivo y el contenido
    $response = array(
        'filename' => $c_interno . "_ugl_06_WSS.txt",  // Nombre dinámico del archivo
        'content' => $contenido
    );

    // Establecer el tipo de contenido como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // Si no hay datos en la tabla
    $response = array('error' => 'No se encontraron datos en la tabla de parámetros.');
    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn->close();
?>