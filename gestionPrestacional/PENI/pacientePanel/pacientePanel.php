<?php
require_once '../controlador/control_paciente.php';

require_once '../conexion/conexion.php';

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener el nombre de la tabla 'parametros'
$sql = "SELECT nombre FROM parametros LIMIT 1";  // Ajusta la consulta si es necesario
$result = $conn->query($sql);

$nombre = "";  // Valor por defecto si no se encuentra en la base de datos

if ($result->num_rows > 0) {
    // Extrae el nombre de la tabla si se encuentra el resultado
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
}


// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit; // Asegura que el script se detenga después de redirigir
}


?>

<!DOCTYPE html>
<html lang="es" charset="UTF-8">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Prestaciones <?php echo htmlspecialchars($nombre); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <script src="assets/plugins/qrCode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .image-top-right {
            position: absolute;
            top: 10px;
            /* Ajustar según la distancia desde la parte superior */
            right: 10px;
            /* Ajustar según la distancia desde el lado derecho */
            max-width: 100%;
            /* Ancho máximo de la imagen */
            height: auto;
            /* Altura ajustada automáticamente según el ancho */
            border-radius: 12px;
            /* Radio de borde para hacerlo más redondeado */
        }

        /* Estilos para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Estilos para las celdas de la tabla */
        .table td,
        .table th {
            padding: 8px;
            border: 1px solid #dddddd;
            text-align: left;
        }

        /* Estilos para el encabezado de la tabla */
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Botón para volver al panel -->
    <a href="../panelMain/panelMain.php"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver</a>

    <?php

    if (isset($_SESSION['alert_message'])) {
        // Muestra el mensaje de alerta
        echo "<script>alert('" . $_SESSION['alert_message'] . "');</script>";
        // Elimina el mensaje de alerta de la sesión para que no se muestre de nuevo
        unset($_SESSION['alert_message']);
    }
    ?>
    <div class="container mx-auto px-4 py-8 relative"> <!-- Añadir relative para el posicionamiento absoluto -->
        <img src="../img/prestaciones.jpeg" alt="Imagen" class="image-top-right hidden sm:block">
        <h1 class="text-3xl font-bold mb-4">Panel de Prestaciones <?php echo htmlspecialchars($nombre); ?></h1>

        <p class="text-lg text-gray-600">
            <br>
            <strong>Actualización</strong>: Los números de beneficios de los pacientes son verificados contra
            el padrón online, en el caso que estén incorrectos no se podrá registrar la prestación. <br>
            <strong>Recuerde</strong> tener en cuenta el 'Código de Práctica' de cada especialidad.
        </p>

        <!-- Formulario para agregar nuevo paciente -->
        <h2 class="text-2xl font-bold mb-2">Agregar Nueva Prestacion</h2>
        <form method="post" class="mb-4" id="formAgregar">
            <!-- Formulario para seleccionar el profesional -->
            <div class="mb-4">
                <label for="cod_prof" class="block text-sm font-medium text-gray-700">Profesional:</label>
                <select id="cod_prof" name="cod_prof" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar Profesional</option>
                    <?php
                    require_once '../controlador/control_paciente.php';
                    // Obtener la lista de profesionales
                    $profesionales = obtenerProfesionales();

                    // Ordenar los profesionales alfabéticamente por apellido
                    usort($profesionales, function ($a, $b) {
                        return strcmp($a['apellido'], $b['apellido']);
                    });

                    // Mostrar los profesionales ordenados
                    foreach ($profesionales as $profesional) {
                        echo "<option value='" . $profesional['cod_prof'] . "'>" . $profesional['apellido'] . " " . $profesional['nombre'] . "</option>";
                    }
                    ?>
                    ?>
                </select>
            </div>



            <div id="buscarContainer">
                <div class="mb-4 flex items-center">
                    <label for="benef" class="block text-sm font-medium text-gray-700 mr-2">Beneficio(12
                        digitos):</label>
                    <input type="number" id="benef" name="benef" required maxlength="12"
                        class="mt-1 p-2 block w-1/2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mr-2">
                </div>

                <div class="mb-4 flex items-center">
                    <label for="parentesco" class="block text-sm font-medium text-gray-700 mr-2">Parentesco(2
                        digitos):</label>
                    <input type="number" id="parentesco" name="parent" required maxlength="2"
                        class="mt-1 p-2 block w-1/2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mr-2">
                </div>

                <button type="button" name="buscar" id="btnBuscar"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Buscar
                </button>
            </div>

            <div class="mb-4 flex items-center">
                <label for="nombreYapellido" class="block text-sm font-medium text-gray-700 mr-2">Nombre y
                    Apellido:</label>
                <input type="text" id="nombreYapellido" name="nombreYapellido" readonly
                    class="mt-1 p-2 block w-1/2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mr-2">
            </div>
            <div class="mb-4 flex items-center">
                <label for="token" class="block text-sm font-medium text-gray-700 mr-2">Token:</label>
                <input type="text" id="token" name="token"
                    class="mt-1 p-2 block w-1/2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-2 flex items-center">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
            </div>


            <div class="mb-4">
                <label for="cod_practica" class="block text-sm font-medium text-gray-700">Código de Práctica:</label>
                <select id="cod_practica" name="cod_practica" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar Código de Práctica</option>
                    <?php
                    // Obtener códigos de práctica y descripciones
                    $codigos_practica = obtenerCodigosPractica();
                    foreach ($codigos_practica as $cod_practica) {
                        // Obtener la descripción para el código de práctica actual
                        $descripcion = obtenerDescripcionPractica($cod_practica);
                        // Imprimir opción con código de práctica y descripción
                        echo "<option value='" . $cod_practica . "'>" . $cod_practica . " - " . $descripcion . "</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="mb-4">
                <label for="cod_diag" class="block text-sm font-medium text-gray-700">Diagnóstico:</label>
                <select id="cod_diag" name="cod_diag" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar Diagnóstico</option>
                    <?php
                    $diagnosticos = obtenerDiagnosticoConDescripcion(); // Obtener la lista de diagnósticos con descripciones
                    foreach ($diagnosticos as $diagnostico) {
                        echo "<option value='" . $diagnostico['cod_diag'] . "'>" . $diagnostico['cod_diag'] . " - " . $diagnostico['descript'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="agregar" id="btnAgregar"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Agregar
            </button>

        </form>

        <br>
        <div class="flex items-center mb-4">
            <h2 class="text-3xl font-bold mr-4">Reporte de prestaciones</h2>
        </div>

        <div class="mb-4">
            <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Desde:</label>
            <input type="date" id="fecha_desde" name="fecha_desde"
                class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Hasta:</label>
            <input type="date" id="fecha_hasta" name="fecha_hasta"
                class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
        </div>

        <button id="btnGenerarPDF" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Generar PDF
        </button>

        <div id="contenedorPacientes"></div>
        <div id="paginacion"></div>

        <!-- EDITAR MODAL-->
        <!-- Modal -->
        <div class="modal fade" id="modalEditarPaciente" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarPacienteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarPacienteLabel">Editar Paciente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarPaciente">
                            <input type="hidden" name="id" id="idPaciente">

                            <div class="mb-4">
                                <label for="nombreYapellidoModal" class="block text-sm font-medium text-gray-700">Nombre
                                    y Apellido:</label>
                                <input type="text" id="nombreYapellidoModal" name="nombreYapellido" required
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="benefModal"
                                    class="block text-sm font-medium text-gray-700">Beneficio:</label>
                                <input type="text" id="benefModal" name="benef" required
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="tokenModal" class="block text-sm font-medium text-gray-700">Token:</label>
                                <input type="text" id="tokenModal" name="token" required
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="mb-2">
                                <label for="fecha_edit" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="fecha_edit" name="fecha_edit">
                            </div>

                            <div class="mb-4">
                                <label for="cod_practicaModal" class="block text-sm font-medium text-gray-700">Código de
                                    Práctica:</label>
                                <select id="cod_practicaModal" name="cod_practica" required
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                    <option value="">Seleccionar Código de Práctica</option>
                                    <!-- Aquí se llenarán las opciones con JavaScript -->
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="cod_diagModal"
                                    class="block text-sm font-medium text-gray-700">Diagnóstico:</label>
                                <select id="cod_diagModal" name="cod_diag" required
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                    <option value="">Seleccionar Diagnóstico</option>
                                    <!-- Aquí se llenarán las opciones con JavaScript -->
                                </select>
                            </div>

                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aquí va el script de JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/cheerio"></script>
        <script>
            document.getElementById('btnBuscar').addEventListener('click', function () {
                // Obtener los valores de los campos de "Beneficio" y "Parentesco"
                var beneficio = $('#benef').val();
                var parentesco = $('#parentesco').val();

                // Realizar la solicitud al backend
                fetch(`https://worldsoftsystems.com.ar/buscarBenef?beneficio=${beneficio}&parentesco=${parentesco}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Verificar si se encontró el nombre y apellido
                        if (data.resultado) {
                            // Actualizar el campo de nombre y apellido con el resultado
                            $('#nombreYapellido').val(data.resultado);
                        } else {
                            // Mostrar una alerta si no se encuentra el resultado
                            alert("No se encontró ningún beneficiario con los datos proporcionados.");
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        // Muestra un mensaje de error si ocurre un error durante la solicitud
                        alert("Error al buscar el nombre y apellido.");
                    });
            });

            document.getElementById('btnGenerarPDF').addEventListener('click', function () {
                // Llamar a la función para generar el PDF
                generarPDF();

            });

            function generarPDF() {
                // Obtener el ID del profesional seleccionado
                var cod_prof = document.getElementById('cod_prof').value;
                // Obtener las fechas de inicio y fin
                var fechaInicio = document.getElementById('fecha_desde').value;
                var fechaFin = document.getElementById('fecha_hasta').value;

                // Crear la URL para generar el PDF con los parámetros de fecha y el ID del profesional
                var url = './generar_pdf.php?profesional=' + cod_prof + '&fecha_desde=' + fechaInicio + '&fecha_hasta=' + fechaFin;

                // Redireccionar a la URL para generar el PDF
                window.location.href = url;
            }

            function cargarPacientesPorProfesional(cod_prof, pagina = 1) {
                const limite = 50; // Número de pacientes por página
                const offset = (pagina - 1) * limite; // Calcular el desplazamiento

                fetch(`../controlador/control_paciente.php?obtenerPacientesPorProfesional=true&cod_prof=${cod_prof}&limite=${limite}&offset=${offset}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {

                        if (data.pacientes && data.pacientes.length > 0) {
                            mostrarPacientes(data, pagina, cod_prof); // Llamar a la función para mostrar los pacientes y pasar cod_prof
                        } else {
                            document.getElementById('contenedorPacientes').innerHTML = "No se encontraron pacientes.";
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar pacientes:', error);
                    });
            }

            // Función para formatear la fecha en formato argentino (DD/MM/YYYY)
            function formatearFecha(fecha) {
                if (!fecha) return 'N/A'; // Si no hay fecha, retornar 'N/A'

                // Extraer solo la parte de la fecha
                const fechaSolo = fecha.split(' ')[0]; // '2024-08-13'
                const [year, month, day] = fechaSolo.split('-'); // Separar en componentes

                return `${day}/${month}/${year}`; // Retornar en formato DD/MM/YYYY
            }

            function mostrarPacientes(data, pagina, cod_prof) { // Acepta cod_prof como tercer parámetro
                var contenedorPacientes = document.getElementById('contenedorPacientes');
                contenedorPacientes.innerHTML = ''; // Limpiar cualquier contenido anterior de pacientes

                // Crear la tabla y sus encabezados
                var table = document.createElement('table');
                table.classList.add('table'); // Agregar la clase 'table'
                var thead = document.createElement('thead');
                var headerRow = document.createElement('tr');
                headerRow.innerHTML = '<th>Nombre y Apellido</th><th>Beneficio</th><th>Profesional</th><th>Práctica</th><th>Diagnóstico</th><th>Fecha</th>';
                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Crear el cuerpo de la tabla
                var tbody = document.createElement('tbody');
                data.pacientes.forEach(function (paciente) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${paciente.nombreYapellido}</td>
                        <td>${paciente.benef}</td>
                        <td>${paciente.nom_prof}</td>
                        <td>${paciente.cod_practica}</td>
                        <td>${paciente.cod_diag || 'N/A'}</td>
                        <td>${formatearFecha(paciente.fecha) || 'N/A'}</td>
                        `;
                    tbody.appendChild(row);
                });
                table.appendChild(tbody);
                contenedorPacientes.appendChild(table);

                // Crear los botones de paginación
                var paginacion = document.getElementById('paginacion');
                paginacion.innerHTML = ''; // Limpiar la paginación anterior
                for (let i = 1; i <= Math.ceil(data.total / 50); i++) { // Calcular el total de páginas
                    var button = document.createElement('button');
                    button.textContent = i;
                    button.onclick = function () {

                        cargarPacientesPorProfesional(cod_prof, i); // Llamar a cargar con la página seleccionada
                    };
                    if (i === pagina) { // Desactivar el botón de la página actual
                        button.disabled = true;
                    }
                    paginacion.appendChild(button);
                }
            }

            // Definir la variable cod_prof en un ámbito más amplio
            let cod_prof;

            // Agregar un event listener para detectar cambios en el elemento select con id cod_prof
            document.getElementById('cod_prof').addEventListener('change', function () {
                cod_prof = this.value; // Obtener el valor seleccionado del profesional
                cargarPacientesPorProfesional(cod_prof); // Llamar a la función para cargar pacientes por profesional
            });

            // Ejemplo de cómo manejar el clic en las páginas
            document.querySelectorAll('.pagina').forEach(pagina => {
                pagina.addEventListener('click', function () {
                    const numeroPagina = parseInt(this.textContent);
                    if (cod_prof) { // Verificar si cod_prof está definido
                        cargarPacientesPorProfesional(cod_prof, numeroPagina); // Llama a la función con el número de página
                    } else {
                        console.warn("Código del profesional no definido."); // Mensaje de advertencia si cod_prof es undefined
                    }
                });
            });

            //MODAL EDITAR
            function abrirModalEditar(codPaci, nombreYapellido, benef, token,fecha, codPractica, codDiag) {
                // Asignar los valores a los campos del modal
                document.getElementById('idPaciente').value = codPaci;
                document.getElementById('nombreYapellidoModal').value = nombreYapellido;
                document.getElementById('benefModal').value = benef;
                document.getElementById('cod_practicaModal').value = codPractica;
                document.getElementById('tokenModal').value = token;
                document.getElementById('fecha_edit').value = fecha;

                // Abrir el modal
                $('#modalEditarPaciente').modal('show');

                // Aquí puedes agregar lógica para llenar las opciones de diagnóstico y práctica
                llenarOpcionesDiagnostico(codDiag); // Llenar opciones de diagnóstico
                llenarOpcionesPractica(codPractica); // Llenar opciones de práctica
            }

            function llenarOpcionesDiagnostico(codDiagSeleccionado) {
                fetch('../controlador/control_paciente.php?obtenerDiagnosticos')
                    .then(response => response.json())
                    .then(diagnosticos => {
                        var selectDiagnostico = document.getElementById('cod_diagModal');
                        selectDiagnostico.innerHTML = '<option value="">Seleccionar Diagnóstico</option>'; // Limpiar opciones

                        diagnosticos.forEach(diagnostico => {
                            var selected = (diagnostico.cod_diag === codDiagSeleccionado) ? 'selected' : '';
                            selectDiagnostico.innerHTML += `<option value="${diagnostico.cod_diag}" ${selected}>${diagnostico.cod_diag} - ${diagnostico.descript}</option>`;
                        });
                    })
                    .catch(error => console.error('Error al obtener diagnósticos:', error));
            }

            function llenarOpcionesPractica(codPracticaSeleccionada) {
                fetch('../controlador/control_paciente.php?obtenerCodigosPractica')
                    .then(response => response.json())
                    .then(codigosPractica => {
                        var selectPractica = document.getElementById('cod_practicaModal');
                        selectPractica.innerHTML = '<option value="">Seleccionar Código de Práctica</option>'; // Limpiar opciones

                        codigosPractica.forEach(codigo => {
                            var selected = (codigo === codPracticaSeleccionada) ? 'selected' : '';
                            selectPractica.innerHTML += `<option value="${codigo}" ${selected}>${codigo}</option>`;
                        });
                    })
                    .catch(error => console.error('Error al obtener códigos de práctica:', error));
            }

            document.getElementById('formEditarPaciente').addEventListener('submit', function (e) {
                e.preventDefault(); // Evitar la recarga de página

                var formData = new FormData(this); // Obtener datos del formulario


                fetch('../controlador/control_paciente.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => {
                        return response.text(); // Cambiar a text() para ver el contenido real
                    })
                    .then(text => {
                        console.log(text); // Mostrar el contenido de la respuesta en la consola
                        try {
                            const data = JSON.parse(text); // Intentar convertir a JSON
                            if (data.success) {
                                alert('Paciente actualizado correctamente');
                                $('#modalEditarPaciente').modal('hide'); // Cerrar el modal
                                cargarPacientesPorProfesional(cod_prof); // Volver a cargar los pacientes
                            } else {
                                alert('Error al actualizar el paciente: ' + data.message);
                            }
                        } catch (e) {
                            console.error('Error al parsear JSON:', e);
                        }
                    })
                    .catch(error => console.error('Error:', error));

            });




        </script>
</body>

</html>