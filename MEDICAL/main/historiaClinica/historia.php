<?php
require_once "../conexion.php";

session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
    header("Location: ../index.php");
}

// Verificar si se ha enviado el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    // Recibir el ID del paciente a eliminar
    $id = $_GET['eliminar'];

    // Preparar la consulta SQL para eliminar el paciente
    $sql = "DELETE FROM paciente WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("i", $id);

    try {
        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Redirigir a la página después de eliminar
            header("Location: ./paciente.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Verificar si el error es de restricción de clave externa
        if ($e->getCode() == 1451) {
            // Error de restricción de clave externa
            echo "<script>
                    alert('Error al eliminar, el paciente está relacionado con otra tabla');
                    window.location.href = './paciente.php';
                  </script>";
        } else {
            // Otro error
            echo "Error al eliminar el paciente: " . $e->getMessage();
        }
    }

    // Cerrar la sentencia
    $stmt->close();
}

// Obtener todos los pacientes
$sql = "SELECT * FROM paciente ORDER BY nombre ASC";
$result = $conn->query($sql);

$conn->close();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H.C.</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="../estilos/styleBotones.css">
    <link rel="stylesheet" href="../estilos/styleGeneral.css">
    <script src="./script/scriptHistoria.js" defer></script>
    <!--REPORTES -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <!-- QR -->
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

    <!-- BACK -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .modal-xl {
            max-width: 89%;

        }

        .custom-icon {
            color: var(--primary-color);
        }

        .icon-text {
            color: var(--primary-color) !important;
        }



        .scrollable-content {
            max-height: 400px;
            /* Ajusta según sea necesario */
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            position: relative;
        }

        .modal-header-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .modal-title {
            margin: 0;
            flex: 1;
            text-align: start;
        }

        .modal-logo {
            max-height: 4rem;
            margin-top: 1rem;
            /* Ajusta según sea necesario */
        }

        .btn-custom-add {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-modal-paciente {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <button class="button" style="vertical-align:middle; margin-left:7rem" onclick="confirmLogout(event)">
        <span>Cerrar sesión</span>
    </button>

    <div class="text-center my-4">
        <img src="../img/logo.png" alt="Logo MEDICAL" class="img-fluid" style="max-width: 15rem;">
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-auto text-center">
                <h2>Historia Clínica</h2>
                <input type="text" id="searchInput" style="width: 20rem;" class="form-control"
                    placeholder="Buscar por nombre o beneficio">
            </div>
        </div>
        <table class="table table-striped table-bordered " id="pacientesTable">
            <thead class="table-custom">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Beneficio</th>
                    <th>Parentesco</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td><?= $row["nombre"] ?></td>
                            <td><?= $row["benef"] ?></td>
                            <td><?= $row["parentesco"] ?></td>
                            <td>
                                <!-- Botón H.C AMB -->
                                <button type="button" class="btn btn-primary btn-sm btn-hc" data-id="<?= $row['id'] ?>"
                                    data-modal="amb" data-bs-toggle="tooltip" title="Historia Clínica Ambulatoria (H.C AMB)">
                                    H.C AMB
                                </button>
                                <!-- Botón H.C INT -->
                                <button type="button" class="btn btn-secondary btn-sm btn-hc" data-id="<?= $row['id'] ?>"
                                    data-modal="int" data-bs-toggle="tooltip" title="Historia Clínica Internación (H.C INT)">
                                    H.C INT
                                </button>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No se encontraron resultados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para H.C AMB -->
    <div class="modal fade" id="hcAmbModal" tabindex="-1" aria-labelledby="hcAmbModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hcAmbModalLabel">Historia Clínica Ambulatoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="hcAmbTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="admission-tab" data-bs-toggle="tab"
                                data-bs-target="#admission" type="button" role="tab">Admisión</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab">Evolución</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="hcAmbTabContent">
                        <!-- Pestaña 1 -->
                        <div class="tab-pane fade show active" id="admission" role="tabpanel">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Nombre: <span id="nombre-amb"></span></h6>
                                        <h6>Beneficio: <span id="benef-amb"></span></h6>
                                        <h6>Obra Social: <span id="obra-social-amb"></span></h6>
                                        <button id="ver-admisiones-btn" class="btn btn-primary mt-3">Ver
                                            admisiones</button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Edad: <span id="edad-amb"></span></h6>
                                        <h6>DNI: <span id="dni-amb"></span></h6>
                                    </div>
                                </div>
                            </div>



                            <h4>Marcar el dato que corresponda </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>PROFESIONAL:</h5>
                                    <div class="col-md-4 form-group">
                                        <label for="hc_prof">Profesional:*</label>
                                        <select class="form-control" id="hc_prof" name="hc_prof" required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                    </div>

                                    <h5>1-Aspecto Psíquico</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="normal" value="Normal"
                                            name="aspectoPsiquico">
                                        <label class="form-check-label" for="normal">Normal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="indiferente"
                                            value="Indiferente" name="aspectoPsiquico">
                                        <label class="form-check-label" for="indiferente">Indiferente</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="excitado" value="Excitado"
                                            name="aspectoPsiquico">
                                        <label class="form-check-label" for="excitado">Excitado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="obnubilado" value="Obnubilado"
                                            name="aspectoPsiquico">
                                        <label class="form-check-label" for="obnubilado">Obnubilado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="deprimido" value="Deprimido"
                                            name="aspectoPsiquico">
                                        <label class="form-check-label" for="deprimido">Deprimido</label>
                                    </div>


                                    <h5>2-Actitud Psíquica</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="activa" value="Activa"
                                            name="act_psiquica">
                                        <label class="form-check-label" for="activa">Activa</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pasiva" value="Pasiva"
                                            name="act_psiquica">
                                        <label class="form-check-label" for="pasiva">Pasiva</label>
                                    </div>

                                    <h5>3-Actividad</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="actividad-normal"
                                            value="Normal" name="act">
                                        <label class="form-check-label" for="actividad-normal">Normal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="abulia" value="Abulia"
                                            name="act">
                                        <label class="form-check-label" for="abulia">Abulia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="hiperbulia" value="Hiperbulia"
                                            name="act">
                                        <label class="form-check-label" for="hiperbulia">Hiperbulia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="hipobulia" value="Hipobulia"
                                            name="act">
                                        <label class="form-check-label" for="hipobulia">Hipobulia</label>
                                    </div>

                                    <h5>4-Orientación</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="global" value="Global"
                                            name="orientacion">
                                        <label class="form-check-label" for="global">Global</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="desorientacion-autosiquica"
                                            value="Desorientación Autosíquica" name="orientacion">
                                        <label class="form-check-label" for="desorientacion-autosiquica">Desorientación
                                            Autosíquica</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="desorientacion-tiempo"
                                            value="Desorientación en el Tiempo" name="orientacion">
                                        <label class="form-check-label" for="desorientacion-tiempo">Desorientación en el
                                            Tiempo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="desorientacion-lugar"
                                            value="Desorientación en el Lugar" name="orientacion">
                                        <label class="form-check-label" for="desorientacion-lugar">Desorientación en el
                                            Lugar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="desorientacion-global"
                                            value="Desorientación Global" name="orientacion">
                                        <label class="form-check-label" for="desorientacion-global">Desorientación
                                            Global</label>
                                    </div>

                                    <h5>5-Conciencia</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="lucida" value="Lúcida"
                                            name="conciencia">
                                        <label class="form-check-label" for="lucida">Lúcida</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="sin-conciencia-enfermedad"
                                            value="Sin Conciencia de Enfermedad ni de Situación" name="conciencia">
                                        <label class="form-check-label" for="sin-conciencia-enfermedad">Sin Conciencia
                                            de Enfermedad ni de Situación</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="sin-conciencia-enfermedad2"
                                            value="Sin Conciencia de Enfermedad" name="conciencia">
                                        <label class="form-check-label" for="sin-conciencia-enfermedad2">Sin Conciencia
                                            de Enfermedad</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="sin-conciencia-situacion"
                                            value="Sin Conciencia de Situación" name="conciencia">
                                        <label class="form-check-label" for="sin-conciencia-situacion">Sin Conciencia de
                                            Situación</label>
                                    </div>

                                    <h5>6-Memoria</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="memoria-normal" value="Normal"
                                            name="memoria">
                                        <label class="form-check-label" for="memoria-normal">Normal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="fallas-anterogradas"
                                            value="Fallas Anterógradas" name="memoria">
                                        <label class="form-check-label" for="fallas-anterogradas">Fallas
                                            Anterógradas</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="fallas-globales"
                                            value="Fallas Globales" name="memoria">
                                        <label class="form-check-label" for="fallas-globales">Fallas Globales</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="amnesia-lacunar"
                                            value="Fallas Globales" name="memoria">
                                        <label class="form-check-label" for="fallas-globales">Amnesia lacunar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="amnesia"
                                            value="Fallas Retrógradas" name="memoria">
                                        <label class="form-check-label" for="fallas-retrogradas">Amnesia</label>
                                    </div>

                                    <h5>7-Atención</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="atencion-normal" value="Normal"
                                            name="atencion">
                                        <label class="form-check-label" for="atencion-normal">Normal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="hiperprosexia"
                                            value="hiperprosexia" name="atencion">
                                        <label class="form-check-label" for="hiperprosexia">Hiperprosexia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="paraprosexia"
                                            value="paraprosexia" name="atencion">
                                        <label class="form-check-label" for="paraprosexia">Paraprosexia</label>
                                    </div>

                                    <h5>8-Curso del pensamiento</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pensamiento-normal"
                                            value="Normal" name="pensamiento" required>
                                        <label class="form-check-label" for="pensamiento-normal">Normal</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pensamiento-acelerado"
                                            value="acelerado" name="pensamiento">
                                        <label class="form-check-label" for="pensamiento-acelerado">Acelerado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pensamiento-interceptado"
                                            value="interceptado" name="pensamiento" required>
                                        <label class="form-check-label"
                                            for="pensamiento-interceptado">Interceptado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pensamiento-retardado"
                                            value="retardado" name="pensamiento" required>
                                        <label class="form-check-label" for="pensamiento-retardado">Retardado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pensamiento-disgregado"
                                            value="disgregado" name="pensamiento">
                                        <label class="form-check-label" for="pensamiento-disgregado">Disgregado</label>
                                    </div>

                                    <label for="hc_diag">Diagnostico:*</label>
                                    <select class="form-control" id="hc_diag" name="hc_diag" required>
                                        <option value="">Seleccionar...</option>
                                    </select>

                                    <div class="col-md-6">
                                        <label for="hc_medi">Que medicacion esta tomando el paciente:</label>
                                        <select class="form-control" id="hc_medi" name="hc_medi" required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="hc_desc_medi">Para que lo toma:</label>
                                        <input type="text" class="form-control" id="hc_desc_medi" name="hc_desc_medi"
                                            placeholder="Completar">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="hc_cada_medi">Cada cuanto lo toma:</label>
                                        <input type="text" class="form-control" id="hc_cada_medi" name="hc_cada_medi"
                                            placeholder="Completar">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="hc_familiar">Antecedentes Familiares:</label>
                                        <select class="form-control" id="hc_familiar" name="hc_familiar" required>
                                            <option value="">Seleccionar...</option>
                                            <!-- Las opciones se cargarán dinámicamente aquí -->
                                            <option value="nueva_responsable">Nueva Responsable</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="hc_fecha" class="form-label">Fecha:</label>
                                        <input type="date" class="form-control" id="hc_fecha" name="hc_fecha" required>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <h5>9-Contenido del pensamiento</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="contenido-coherente"
                                            value="coherente" name="cont_pensamiento">
                                        <label class="form-check-label" for="contenido-coherente">Coherente</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="contenido-incoherente"
                                            value="incoherente" name="cont_pensamiento">
                                        <label class="form-check-label" for="contenido-incoherente">Incoherente</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" id="contenid-delirante" name="cont_pensamiento"
                                            value="Delirante">
                                        <label for="contenid-delirante"> Delirante</label><br>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" id="contenido-autoeliminacion" name="cont_pensamiento"
                                            value="Ideas de autoeliminación">
                                        <label for="contenido-autoeliminacion"> Ideas de autoeliminación</label><br>
                                    </div>


                                    <h5>10-Sensopercepción</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="senso-alteraciones"
                                            name="sensopercepcion" value="Sin alteraciones">
                                        <label class="form-check-label" for="senso-alteraciones">Sin
                                            alteraciones</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="vehicle41"
                                            name="sensopercepcion" value="Ilusiones">
                                        <label class="form-check-label" for="senso-ilusiones">Ilusiones</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="senso-alucinaciones"
                                            value="Alucinaciones auditivas/visuales Cenestésicas"
                                            name="sensopercepcion">
                                        <label class="form-check-label" for="senso-alucinaciones">Alucinaciones
                                            auditivas/visuales
                                            Cenestésicas</label>
                                    </div>

                                    <h5>11-Afectividad</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="afectividad-sin-alteracion"
                                            name="afectividad" value="Sin alteración">
                                        <label class="form-check-label" for="afectividad-sin-alteracion">Sin
                                            alteración</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            id="afectividad-hipertimia-placentera" name="afectividad"
                                            value="Hipertimia placentera">
                                        <label class="form-check-label"
                                            for="afectividad-hipertimia-placentera">Hipertimia placentera</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            id="afectividad-hipertimia-displacentera" name="afectividad"
                                            value="Hipertimia displacentera">
                                        <label class="form-check-label"
                                            for="afectividad-hipertimia-displacentera">Hipertimia displacentera</label>
                                    </div>

                                    <h5>12-Inteligencia</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="inteligencia-normal"
                                            name="inteligencia" value="Normal">
                                        <label class="form-check-label" for="inteligencia-normal">Normal</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="inteligencia-superior"
                                            name="inteligencia" value="Superior">
                                        <label class="form-check-label" for="inteligencia-superior">Superior</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="inteligencia-inferior"
                                            name="inteligencia" value="Inferior">
                                        <label class="form-check-label" for="inteligencia-inferior">Inferior</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            id="inteligencia-marcada-deficiencia" name="inteligencia"
                                            value="Marcada deficiencia">
                                        <label class="form-check-label" for="inteligencia-marcada-deficiencia">Marcada
                                            deficiencia</label>
                                    </div>

                                    <h5>13-Juicio</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="juicio-normal" name="juicio"
                                            value="Normal">
                                        <label class="form-check-label" for="juicio-normal">Normal</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="juicio-insuficiencia"
                                            name="juicio" value="Insuficiencia">
                                        <label class="form-check-label" for="juicio-insuficiencia">Insuficiencia</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="juicio-debilitado"
                                            name="juicio" value="Debilitado">
                                        <label class="form-check-label" for="juicio-debilitado">Debilitado</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="juicio-suspendido"
                                            name="juicio" value="Suspendido">
                                        <label class="form-check-label" for="juicio-suspendido">Suspendido</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="juicio-desviado" name="juicio"
                                            value="Desviado">
                                        <label class="form-check-label" for="juicio-desviado">Desviado</label>
                                    </div>

                                    <h5>14-Control de esfínteres</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="control-esfinteres-normal"
                                            name="esfinteres" value="Normal">
                                        <label class="form-check-label" for="control-esfinteres-normal">Normal</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            id="control-esfinteres-incontinencia" name="esfinteres"
                                            value="Incontinencia Vesical/Rectal/Vésico-rectal">
                                        <label class="form-check-label"
                                            for="control-esfinteres-incontinencia">Incontinencia
                                            Vesical/Rectal/Vésico-rectal</label>
                                    </div>

                                    <h5>15-Tratamiento</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="tratamiento-clinico"
                                            name="tratamiento" value="Clínico">
                                        <label class="form-check-label" for="tratamiento-clinico">Clínico</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="tratamiento-psicofarmacologico"
                                            name="tratamiento" value="Psicofarmacológico reflejos">
                                        <label class="form-check-label"
                                            for="tratamiento-psicofarmacologico">Psicofarmacológico reflejos</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="tratamiento-biologico"
                                            name="tratamiento" value="Biológico">
                                        <label class="form-check-label" for="tratamiento-biologico">Biológico</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="tratamiento-ech-insulina"
                                            name="tratamiento" value="ECH/c.Insulina">
                                        <label class="form-check-label"
                                            for="tratamiento-ech-insulina">ECH/c.Insulina</label>
                                    </div>

                                    <h5>16-Evolución</h5>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="evolucion-buena"
                                            name="evolucion" value="Buena">
                                        <label class="form-check-label" for="evolucion-buena">Buena</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="evolucion-regular"
                                            name="evolucion" value="Regular">
                                        <label class="form-check-label" for="evolucion-regular">Regular</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="evolucion-mala"
                                            name="evolucion" value="Mala">
                                        <label class="form-check-label" for="evolucion-mala">Mala</label>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- Pestaña 2 -->
                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Nombre: <span id="nombre-amb-tab2"></span></h6>
                                        <h6>Beneficio: <span id="benef-amb-tab2"></span></h6>
                                        <h6>Obra Social: <span id="obra-social-amb-tab2"></span></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Edad: <span id="edad-amb-tab2"></span></h6>
                                        <h6>DNI: <span id="dni-amb-tab2"></span></h6>
                                    </div>
                                </div>
                                <div class="row justify-content-center mt-3">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-custom" id="hc_amb">Agregar Evolución</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-custom-add" id="guardarBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para H.C INT -->
    <div class="modal fade" id="hcIntModal" tabindex="-1" aria-labelledby="hcIntModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hcIntModalLabel">Historia Clínica Internación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="hcIntTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="admission-tab-int" data-bs-toggle="tab"
                                data-bs-target="#admission-int" type="button" role="tab">Admisión</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab-int" data-bs-toggle="tab" data-bs-target="#tab2-int"
                                type="button" role="tab">Evolución</button>
                        </li>
                       
                    </ul>
                    <div class="tab-content" id="hcIntTabContent">
                        <!-- Pestaña 1 -->
                        <div class="tab-pane fade show active" id="admission-int" role="tabpanel">
                            <div class="header-info">
                                <h6>Nombre: <span id="nombre-int"></span></h6>
                                <h6>Beneficio: <span id="benef-int"></span></h6>
                                <h6>Obra Social: <span id="obra-social-int"></span></h6>
                            </div>
                            <p>Datos de admisión...</p>
                        </div>
                        <!-- Pestaña 2 -->
                        <div class="tab-pane fade" id="tab2-int" role="tabpanel">
                            <div class="header-info">
                                <h6>Nombre: <span id="nombre-int-tab2"></span></h6>
                                <h6>Beneficio: <span id="benef-int-tab2"></span></h6>
                                <h6>Obra Social: <span id="obra-social-int-tab2"></span></h6>
                            </div>
                            <div class="row justify-content-center mt-3">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-custom" id="hc_int">Agregar Evolución</button>
                                    </div>
                                </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- agregar responsable-->
    <div class="modal fade" id="agregarResponModal" tabindex="-1" aria-labelledby="agregarResponModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarResponModalLabel">Agregar Responsable</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarRespon" class="row g-3">

                        <div class="col-md-4">
                            <label for="respon_nombre">Nombre:</label>
                            <input type="text" class="form-control" id="respon_nombre" name="respon_nombre">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_tel">tel:</label>
                            <input type="number" class="form-control" id="respon_tel" name="respon_tel">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="respon_parent">Parentesco:*</label>
                            <select class="form-control" id="respon_parent" name="respon_parent" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="respon_dni">DNI:</label>
                            <input type="number" class="form-control" id="respon_dni" name="respon_dni">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_dom">Domicilio:</label>
                            <input type="number" class="form-control" id="respon_dom" name="respon_dom">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_locali">Localidad:</label>
                            <input type="text" class="form-control" id="respon_locali" name="respon_locali">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarRespon">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- MOSTRAR ADMISIONES -->
    <div class="modal fade" id="admissionsModal" tabindex="-1" role="dialog" aria-labelledby="admissionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="admissionsModalLabel">Detalles de Admisión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí mostrarás los datos de la admisión -->
                    <p><strong>Nro:</strong> <span id="id-admision"></span></p>
                    <p><strong>Paciente</strong> <span id="id-paciente"></span></p>
                    <p><strong>ID Responsable:</strong> <span id="id-responsable"></span></p>
                    <p><strong>ID Profesional:</strong> <span id="id-prof"></span></p>
                    <p><strong>Asc. Psíquico:</strong> <span id="asc-psiquico"></span></p>
                    <p><strong>Act. Psíquica:</strong> <span id="act-psiquica"></span></p>
                    <p><strong>Act:</strong> <span id="act"></span></p>
                    <p><strong>Orientación:</strong> <span id="orientacion"></span></p>
                    <p><strong>Conciencia:</strong> <span id="conciencia"></span></p>
                    <p><strong>Memoria:</strong> <span id="memoria"></span></p>
                    <p><strong>Atención:</strong> <span id="atencion"></span></p>
                    <p><strong>Pensamiento:</strong> <span id="pensamiento"></span></p>
                    <p><strong>Contenido del Pensamiento:</strong> <span id="cont-pensamiento"></span></p>
                    <p><strong>Sensopercepción:</strong> <span id="sensopercepcion"></span></p>
                    <p><strong>Afectividad:</strong> <span id="afectividad"></span></p>
                    <p><strong>Inteligencia:</strong> <span id="inteligencia"></span></p>
                    <p><strong>Juicio:</strong> <span id="juicio"></span></p>
                    <p><strong>Esfínteres:</strong> <span id="esfinteres"></span></p>
                    <p><strong>Tratamiento:</strong> <span id="tratamiento"></span></p>
                    <p><strong>Evolución:</strong> <span id="evolucion"></span></p>
                    <p><strong>Cada Medicamento:</strong> <span id="hc-cada-medi"></span></p>
                    <p><strong>Descripción Medicamento:</strong> <span id="hc-desc-medi"></span></p>
                    <p><strong>Fecha:</strong> <span id="hc-fecha"></span></p>
                    <p><strong>Código QR:</strong></p>
                    <canvas id="qr-code"></canvas> <!-- Aquí se mostrará el código QR -->
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>




    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 footer-logo-text">
                    <img src="../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 50px;">
                    <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>