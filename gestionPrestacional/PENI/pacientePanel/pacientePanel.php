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
                    <label for="benef" class="block text-sm font-medium text-gray-700 mr-2">Beneficio(12 digitos):</label>
                    <input type="number" id="benef" name="benef" required maxlength="12"
                        class="mt-1 p-2 block w-1/2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mr-2">
                </div>

                <div class="mb-4 flex items-center">
                    <label for="parentesco" class="block text-sm font-medium text-gray-700 mr-2">Parentesco(2 digitos):</label>
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
            <div class="mb-4">
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha y Hora:</label>
                <input type="datetime-local" id="fecha" name="fecha" required readonly
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>


            <button type="submit" name="agregar" id="btnAgregar"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" >
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
        <!-- Aquí va el script de JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/cheerio"></script>
        <script>
            
             fetch('https://worldtimeapi.org/api/timezone/America/Argentina/Buenos_Aires')
                .then(response => response.json())
                .then(data => {
                    // Crear un objeto Date a partir del datetime recibido
                    const fecha = new Date(data.datetime);

                    // Obtener los componentes individuales de la fecha y hora
                    const year = fecha.getFullYear();
                    const month = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados
                    const day = String(fecha.getDate()).padStart(2, '0');
                    const hours = String(fecha.getHours()).padStart(2, '0');
                    const minutes = String(fecha.getMinutes()).padStart(2, '0');

                    // Formatear la fecha y hora en el formato requerido por el input datetime-local
                    const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;

                    // Asignar el valor formateado al input
                    document.getElementById('fecha').value = formattedDate;
                })
                .catch(error => console.error('Error:', error));
        
            
            
            document.getElementById('btnBuscar').addEventListener('click', function () {
                // Obtener los valores de los campos de "Beneficio" y "Parentesco"
                var beneficio = $('#benef').val();
                var parentesco = $('#parentesco').val();

                // Realizar la solicitud al backend
                fetch(`https://worldsoftsystems.com.ar/buscar?beneficio=${beneficio}&parentesco=${parentesco}`, {
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
                    .catch (error => {
                            console.error(error);
                            // Muestra un mensaje de error si ocurre un error durante la solicitud
                            alert("Error al buscar el nombre y apellido.");
                        });
            });


            // Obtener el campo de fecha y hora por su ID
            var fechaInput = document.getElementById('fecha');

            // Obtener la fecha y hora actual en formato local (hora de Argentina)
            var fechaActual = new Date();
            var offset = -3; // UTC -3 horas para Argentina
            fechaActual.setHours(fechaActual.getHours() + offset);

            // Formatear la fecha y hora en el formato deseado (YYYY-MM-DDTHH:MM)
            var fechaFormateada = fechaActual.toISOString().slice(0, 16);

            // Establecer el valor del campo de fecha y hora al valor actual
            fechaInput.value = fechaFormateada;


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


            // Dentro de tu función cargarPacientesPorProfesional en tu archivo HTML
            function cargarPacientesPorProfesional(cod_prof) {
                console.log("Cargando pacientes para el profesional con el código: " + cod_prof);

                fetch('../controlador/control_paciente.php?obtenerPacientesPorProfesional=true&cod_prof=' + cod_prof)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Hubo un problema al cargar los pacientes. Estado de la respuesta: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Datos de pacientes cargados con éxito:", data);
                        mostrarPacientes(data); // Llamar a la función para mostrar los pacientes
                    })
                    .catch(error => {
                        console.error('Error al cargar pacientes:', error);
                        // Aquí puedes mostrar un mensaje de error al usuario si lo deseas
                    });
            }



            // Función para editar un paciente
            function editarPaciente(cod_paci) {
                // Aquí puedes implementar la lógica para editar el paciente con el ID cod_paci
                // Por ejemplo, puedes redirigir a una página de edición con el ID del paciente en la URL
                window.location.href = 'editarPaciente.php?editar=' + cod_paci;
            }

            function mostrarPacientes(pacientes) {
                var contenedorPacientes = document.getElementById('contenedorPacientes');
                contenedorPacientes.innerHTML = ''; // Limpiar cualquier contenido anterior de pacientes

                // Ordenar pacientes por fecha de forma descendente
                pacientes.sort(function (a, b) {
                    return new Date(b.fecha) - new Date(a.fecha);
                });

                // Crear la tabla y sus encabezados
                var table = document.createElement('table');
                table.classList.add('table'); // Agregar la clase 'table'
                var thead = document.createElement('thead');
                var headerRow = document.createElement('tr');
                headerRow.innerHTML = '<th>Nombre y Apellido</th><th>Beneficio</th><th>Profesional</th><th>Práctica</th><th>Diagnóstico</th><th>Fecha</th>'; //<th>Acciones</th>
                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Crear el cuerpo de la tabla
                var tbody = document.createElement('tbody');
                pacientes.forEach(function (paciente) {
                    var row = document.createElement('tr');
                    // Llamar a la función para obtener el nombre del profesional por AJAX
                    fetch('../controlador/control_paciente.php?obtenerNombreProfesional&cod_prof=' + paciente.cod_prof)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Formatear la fecha
                                var fecha = new Date(paciente.fecha);
                                var dia = fecha.getDate();
                                var mes = fecha.getMonth() + 1;
                                var año = fecha.getFullYear();
                                var hora = fecha.getHours();
                                var minutos = fecha.getMinutes();
                                var segundos = fecha.getSeconds();

                                // Asegurar que los números de día, mes, hora, minutos y segundos estén en el formato de dos dígitos
                                dia = (dia < 10 ? '0' : '') + dia;
                                mes = (mes < 10 ? '0' : '') + mes;
                                hora = (hora < 10 ? '0' : '') + hora;
                                minutos = (minutos < 10 ? '0' : '') + minutos;
                                segundos = (segundos < 10 ? '0' : '') + segundos;

                                var fechaFormateada = dia + '/' + mes + '/' + año + ' ' + hora + ':' + minutos + ':' + segundos;

                                // Agregar los datos del paciente a la fila de la tabla
                                row.innerHTML = '<td>' + paciente.nombreYapellido + '</td><td>' + paciente.benef + '</td><td>' + data.nombreProfesional + '</td><td>' + paciente.cod_practica + '</td><td>' + paciente.cod_diag + '</td><td>' + fechaFormateada ; // + '</td><td><button onclick="editarPaciente(' + paciente.cod_paci + ')">Editar</button></td>'
                            } else {
                                console.error('Error al obtener el nombre del profesional:', data.message);
                                // Si hay un error, mostrar solo los detalles del paciente sin el nombre del profesional
                                row.innerHTML = '<td>' + paciente.nombreYapellido + '</td><td>' + paciente.benef + '</td><td></td><td>' + paciente.cod_practica + '</td><td>' + paciente.cod_diag + '</td><td>' + paciente.fecha + '</td><td><button onclick="editarPaciente(' + paciente.cod_paci + ')">Editar</button></td>'; // Agregado: Fecha y botón de Editar
                            }
                            // Agregar la fila a la tabla
                            tbody.appendChild(row);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Si hay un error, mostrar solo los detalles del paciente sin el nombre del profesional
                            row.innerHTML = '<td>' + paciente.nombreYapellido + '</td><td>' + paciente.benef + '</td><td></td><td>' + paciente.cod_practica + '</td><td>' + paciente.cod_diag + '</td><td>' + paciente.fecha + '</td><td><button onclick="editarPaciente(' + paciente.cod_paci + ')">Editar</button></td>'; // Agregado: Fecha y botón de Editar
                            // Agregar la fila a la tabla
                            tbody.appendChild(row);
                        });
                });
                // Agregar el cuerpo de la tabla al elemento contenedor
                table.appendChild(tbody);
                contenedorPacientes.appendChild(table);
            }




            // Agrega un event listener para detectar cambios en el elemento select con id cod_prof
            document.getElementById('cod_prof').addEventListener('change', function () {
                var cod_prof = this.value; // Obtener el valor seleccionado del profesional
                cargarPacientesPorProfesional(cod_prof); // Llamar a la función para cargar pacientes por profesional
            });



        </script>
</body>

</html>