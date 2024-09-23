<?php
require_once '../controlador/control_paciente.php';

// Verificar si se envió el formulario de actualización
if (isset($_POST['actualizar'])) {
    // Obtener los datos del formulario
    $cod_paci = $_POST['id'];
    $nombreYapellido = $_POST['nombreYapellido'];
    $benef = $_POST['benef'];
    $cod_prof = $_POST['cod_prof'];
    $cod_practica = $_POST['cod_practica'];
    $cod_diag = $_POST['cod_diag'];
    $fecha = $_POST['fecha'];

    // Llamar a la función editarPaciente con los datos obtenidos del formulario
    if (editarPaciente($cod_paci, $nombreYapellido, $benef, $cod_prof, $cod_practica, $cod_diag, $fecha)) {
        echo "<script>alert('Paciente actualizado correctamente'); window.location.href = './pacientePanel.php';</script>";
        exit; // Asegúrate de que el script se detenga aquí para evitar ejecuciones adicionales
    } else {
        echo "<script>alert('Error al actualizar el paciente');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <style>
        /* Personalizar estilos adicionales aquí si es necesario */
    </style>
</head>

<body class="bg-gray-100">
    <!-- Botón para volver al panel -->
    <a href="../pacientePanel/pacientePanel.php"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver</a>

    <div class="container mx-auto px-4 py-8">
        <?php
        require_once '../controlador/control_paciente.php';
        if (isset($_GET['editar'])) {
            $id = $_GET['editar'];
            $paciente = obtenerPacientePorID($id);
            if ($paciente) {
                ?>
                <h1 class="text-3xl font-bold mb-4">Editar Paciente</h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $paciente['cod_paci']; ?>">

                    <div class="mb-4" style="display: none;">
                        <!-- Este div se ocultará visualmente, pero mantendrá el campo de entrada para el código del profesional -->
                        <label for="cod_prof" class="block text-sm font-medium text-gray-700">Código de Profesional:</label>
                        <input type="text" id="cod_prof" name="cod_prof" required
                            value="<?php echo isset($paciente['cod_prof']) ? $paciente['cod_prof'] : ''; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre del Profesional:</label>
                        <span id="nombreProfesional"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            <?php
                            if (isset($paciente['cod_prof'])) {
                                $nombreProfesional = obtenerNombreProfesional($paciente['cod_prof']);
                                echo $nombreProfesional ? $nombreProfesional : 'Nombre no encontrado';
                            } else {
                                echo 'Código de profesional no proporcionado';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <label for="nombreYapellido" class="block text-sm font-medium text-gray-700">Nombre y Apellido:</label>
                        <input type="text" id="nombreYapellido" name="nombreYapellido" required
                            value="<?php echo $paciente['nombreYapellido']; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="benef" class="block text-sm font-medium text-gray-700">Beneficio:</label>
                        <input type="text" id="benef" name="benef" required value="<?php echo $paciente['benef']; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                        <!-- Botón de verificación -->
                        <button type="button" id="verificarBeneficio"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Verificar</button>

                    </div>

                    <div class="mb-4">
                        <label for="cod_practica" class="block text-sm font-medium text-gray-700">Código de Práctica:</label>
                        <select id="cod_practica" name="cod_practica" required
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            <option value="">Seleccionar Código de Práctica</option>
                            <?php
                            $codigos_practica = obtenerCodigosPractica();
                            foreach ($codigos_practica as $cod_practica) {
                                // Verificar si el código de práctica actual es el seleccionado para el paciente
                                $selected = ($cod_practica == $paciente['cod_practica']) ? 'selected' : '';
                                echo "<option value='" . $cod_practica . "' $selected>" . $cod_practica . "</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="mb-4">
                        <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha y Hora:</label>
                        <input type="datetime-local" id="fecha" name="fecha" required value="<?php echo $paciente['fecha']; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="cod_diag" class="block text-sm font-medium text-gray-700">Diagnóstico:</label>
                        <select id="cod_diag" name="cod_diag" required
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            <option value="">Seleccionar Diagnóstico</option>
                            <?php
                            $diagnosticos = obtenerDiagnosticoConDescripcion(); // Obtener la lista de diagnósticos con descripciones
                            foreach ($diagnosticos as $diagnostico) {
                                // Verificar si el diagnóstico actual es el seleccionado para el paciente
                                $selected = ($diagnostico['cod_diag'] == $paciente['cod_diag']) ? 'selected' : '';
                                echo "<option value='" . $diagnostico['cod_diag'] . "' $selected>" . $diagnostico['cod_diag'] . " - " . $diagnostico['descript'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <button type="submit" name="actualizar"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                </form>
                <?php
            } else {
                echo "<p>Paciente no encontrado</p>";
            }
        }
        ?>
    </div>

    <script>

        // Obtener el elemento del campo de entrada del código del profesional
        var codProfInput = document.getElementById('cod_prof');

        // Agregar un listener para el evento "change" en el campo de entrada del código del profesional
        codProfInput.addEventListener('change', function () {
            var codProfValue = this.value; // Obtener el valor del código del profesional
            var nombreProfesionalSpan = document.getElementById('nombreProfesional'); // Obtener el elemento del span del nombre del profesional

            // Realizar una solicitud AJAX para obtener el nombre del profesional
            fetch('../controlador/control_paciente.php?obtenerNombreProfesional&cod_prof=' + codProfValue)
                .then(response => response.json())
                .then(data => {
                    // Mostrar el nombre del profesional en el span correspondiente
                    nombreProfesionalSpan.textContent = data.nombreProfesional || 'Nombre no encontrado';
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Mostrar un mensaje de error si hay un problema al obtener el nombre del profesional
                    nombreProfesionalSpan.textContent = 'Error al obtener el nombre del profesional';
                });
        });

        //funcion para maximo de beneficio:
        document.getElementById("benef").addEventListener("input", function () {
            var input = this.value.trim();
            // Eliminar caracteres no numéricos
            input = input.replace(/\D/g, '');
            // Limitar a 14 caracteres
            input = input.slice(0, 14);
            this.value = input;
        });

        document.getElementById('verificarBeneficio').addEventListener('click', function () {
            var beneficio = document.getElementById('benef').value;
            if (beneficio.trim() !== '') {
                // Comprobar la longitud del beneficio ingresado
                if (beneficio.trim().length < 14) {
                    alert('El número de beneficio debe tener al menos 14 caracteres.');
                    return; // Salir de la función si no se cumplen los requisitos de longitud
                }

                // Realizar la verificación del beneficio si tiene al menos 14 caracteres
                fetch('../controlador/control_paciente.php?verificarBeneficio&benef=' + beneficio)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('nombreYapellido').value = data.nombreYapellido;
                            document.getElementById('btnAgregar').disabled = false;
                        } else {
                            if (data.completar) {
                                alert('Completar número de beneficio.');
                            } else {
                                alert(data.message);
                            }
                            document.getElementById('nombreYapellido').value = ''; // Limpiar el campo si el beneficio no existe
                            document.getElementById('btnAgregar').disabled = false; // Deshabilitar el botón si el beneficio no existe
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                alert('Por favor ingrese un número de beneficio.');
            }
        });
    </script>
</body>

</html>