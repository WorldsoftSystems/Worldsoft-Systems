// Definir variables globales para los botones de navegación
let prevButton, nextButton;
let selectedDayElement = null;
let selectedDay = null;

function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}

// Función para obtener y mostrar la última fecha de turno
function obtenerUltimoTurno(idPaciente) {
    $.ajax({
        url: './gets/get_ultimo_turno.php',
        type: 'GET',
        data: { id_paciente_turno: idPaciente },
        dataType: 'json',
        success: function (data) {
            if (data.ultima_fecha) {
                // Crear el texto con los datos obtenidos
                var texto = `Fecha: ${formatDate(data.ultima_fecha)}, Hora: ${data.hora}, Profesional: ${data.nom_prof}`;

                // Establecer el texto en los campos de entrada
                $('#id_paciente_turno').val(texto);
                $('#id_paciente_turno_edit').val(texto);
            } else {
                $('#id_paciente_turno').val('No hay turnos');
            }
        },
        error: function (error) {
            console.error("Error fetching latest turn: ", error);
            $('#id_paciente_turno').val('Error');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.getElementById('calendar');
    const scheduleBody = document.getElementById('schedule-body');
    const profesionalSelect = document.getElementById('profesionalSelect');
    let month = new Date().getMonth();
    let year = new Date().getFullYear();

    function fetchProfesionales() {
        fetch('./gets/get-profesionales.php')
            .then(response => response.json())
            .then(data => {
                const profesionalSelect = document.getElementById('profesionalSelect'); // Asegúrate de que esto esté definido en tu HTML

                data.forEach(prof => {
                    const option = document.createElement('option');
                    option.value = prof.id_prof;
                    option.textContent = prof.prof_full;
                    profesionalSelect.appendChild(option);
                });

                // Manejar el cambio de selección
                profesionalSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    document.getElementById('prof_name_input').value = selectedOption.textContent;
                    document.getElementById('id_prof_input').value = selectedOption.value;
                    document.getElementById('id_prof_edit').value = selectedOption.value;
                    document.getElementById('prof_name').value = selectedOption.textContent
                });
            });
    }

    
    function renderCalendar(disponibilidad = [], turnos = []) {
        calendar.innerHTML = '';
        
        const header = document.createElement('div');
        header.classList.add('calendar-header');
        
        prevButton = document.createElement('button');
        prevButton.innerText = '<';
        prevButton.onclick = () => {
            month--;
            if (month < 0) {
                month = 11;
                year--;
            }
            updateCalendar();
        };
        
        nextButton = document.createElement('button');
        nextButton.innerText = '>';
        nextButton.onclick = () => {
            month++;
            if (month > 11) {
                month = 0;
                year++;
            }
            updateCalendar();
        };
        
        const currentMonth = new Date(year, month, 1);
        const monthYear = document.createElement('div');
        monthYear.innerText = `${currentMonth.toLocaleString('default', { month: 'long' })} ${year}`;
        
        header.appendChild(prevButton);
        header.appendChild(monthYear);
        header.appendChild(nextButton);
        
        calendar.appendChild(header);
        
        const body = document.createElement('div');
        body.classList.add('calendar-body');
        
        const daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sabado'];
        
        daysOfWeek.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day-header');
            dayElement.innerText = day.charAt(0).toUpperCase() + day.slice(1, 3);
            body.appendChild(dayElement);
        });
        
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay();
        
        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyElement = document.createElement('div');
            emptyElement.classList.add('calendar-day');
            body.appendChild(emptyElement);
        }
        
        // Crear un set de los días con turnos para verificar rápidamente
        const daysWithAppointments = new Set(turnos.map(turno => {
            const [yearTurno, monthTurno, day] = turno.fecha.split('-');
            const dateTurno = new Date(yearTurno, monthTurno - 1, day);
            if (dateTurno.getFullYear() === year && dateTurno.getMonth() === month) {
                return dateTurno.getDate();
            }
            return null;
        }).filter(day => day !== null));
        
        // Crear un set de los días totalmente ocupados
        const daysFullyBooked = new Set();
        
        disponibilidad.forEach(disp => {
            const dayName = disp.dia_semana;
            const dayIntervals = disp.intervalos;
            
            // Verificar cada día del mes para ver si está completamente ocupado
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(year, month, i);
                const dayOfWeek = daysOfWeek[date.getDay()];
                
                if (dayOfWeek === dayName) {
                    const appointmentsOnDay = turnos.filter(turno => {
                        const [yearTurno, monthTurno, dayTurno] = turno.fecha.split('-');
                        const dateTurno = new Date(yearTurno, monthTurno - 1, dayTurno);
                        return dateTurno.getFullYear() === year && dateTurno.getMonth() === month && dateTurno.getDate() === i;
                    });
                    
                    // Verificar si todos los intervalos están ocupados
                    const allIntervalsOccupied = dayIntervals.every(intervalo => appointmentsOnDay.some(turno => turno.hora.startsWith(intervalo)));
                    if (allIntervalsOccupied) {
                        daysFullyBooked.add(i);
                    }
                }
            }
        });
        
        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            dayElement.innerText = i;
            dayElement.onclick = () => {
                loadSchedule(i, month + 1, year);
                const selectedDateDiv = document.getElementById('selected-date');
                selectedDateDiv.innerText = `Fecha seleccionada: ${i}/${month + 1}/${year}`;
        
                if (selectedDayElement) {
                    selectedDayElement.classList.remove('selected-day');
                }
                dayElement.classList.add('selected-day');
                selectedDayElement = dayElement;
                selectedDay = { day: i, month: month + 1, year: year }; // Almacenar el día seleccionado
            };
        
            const date = new Date(year, month, i);
            const dayOfWeek = date.getDay();
            const dayName = daysOfWeek[dayOfWeek];
        
            disponibilidad.forEach(disp => {
                if (disp.dia_semana === dayName) {
                    dayElement.classList.add('available');
                }
            });
        
            // Cambiar el color de los días con turnos a amarillo solo si están en el mes actual
            if (daysWithAppointments.has(i)) {
                dayElement.classList.add('has-appointments');
            }
        
            // Cambiar el color de los días totalmente ocupados a rojo
            if (daysFullyBooked.has(i)) {
                dayElement.classList.add('fully-booked');
            }
        
            if (selectedDay && selectedDay.day === i && selectedDay.month === month + 1 && selectedDay.year === year) {
                dayElement.classList.add('selected-day');
                selectedDayElement = dayElement;
            }
        
            body.appendChild(dayElement);
        }
        
        calendar.appendChild(body);
    }
    
    
    
    




    function updateCalendar() {
        const selectedProf = profesionalSelect.value;
        const selectedDate = new Date(year, month, 1).toISOString().slice(0, 10);

        fetch(`./gets/get-schedule.php?date=${selectedDate}&prof=${selectedProf}`)
            .then(response => response.json())
            .then(data => {
                renderCalendar(data.disponibilidad, data.todos_turnos);

                if (selectedDay) {
                    // Restaurar la selección del día después de actualizar el calendario
                    const dayElements = document.querySelectorAll('.calendar-day');
                    dayElements.forEach(dayElement => {
                        if (dayElement.innerText == selectedDay.day) {
                            dayElement.classList.add('selected-day');
                            selectedDayElement = dayElement;
                            loadSchedule(selectedDay.day, month + 1, year);
                        }
                    });
                } else {
                    // Cargar el horario del día actual si no hay un día seleccionado
                    const today = new Date();
                    loadSchedule(today.getDate(), today.getMonth() + 1, today.getFullYear());
                }
            })
            .catch(error => {
                console.error('Error al actualizar el calendario:', error);
            });
    }

    function loadSchedule(day, month, year) {
        const selectedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        const selectedProf = profesionalSelect.value;
        fetch(`./gets/get-schedule.php?date=${selectedDate}&prof=${selectedProf}`)
            .then(response => response.json())
            .then(data => {
                scheduleBody.innerHTML = '';

                const daysOfWeek = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                const date = new Date(year, month - 1, day);
                const dayName = daysOfWeek[date.getDay()];

                const disponibilidadDia = data.disponibilidad.find(disp => disp.dia_semana === dayName);
                const intervalos = disponibilidadDia ? disponibilidadDia.intervalos : [];

                if (!Array.isArray(intervalos)) {
                    console.error('Intervalos no es un array:', intervalos);
                    return;
                }

                intervalos.forEach(intervalo => {
                    const row = scheduleBody.insertRow();
                    row.insertCell(0).innerText = intervalo;

                    for (let i = 1; i < 6; i++) {
                        row.insertCell(i).innerText = '';
                    }

                    // Truncar los segundos de turno.hora para que coincida con el formato de intervalo
                    const turno = data.turnos.find(turno => turno.hora.startsWith(intervalo));

                    if (turno) {
                        row.cells[1].innerText = turno.nombre_paciente;
                        row.cells[1].classList.add('editable-cell'); // Agregar clase para identificar celdas editables

                        row.cells[1].addEventListener('click', function () {
                            // Abrir modal o pop-up aquí
                            openEditModal(turno); // Llamar función para abrir el modal de edición con datos del turno
                        });

                        row.cells[2].innerText = turno.motivo_full;

                        row.cells[2].addEventListener('click', function () {
                            // Abrir modal o pop-up aquí
                            openEditModal(turno); // Llamar función para abrir el modal de edición con datos del turno
                        });

                        row.cells[3].innerText = turno.llego;

                        row.cells[3].addEventListener('click', function () {
                            // Abrir modal o pop-up aquí
                            openEditModal(turno); // Llamar función para abrir el modal de edición con datos del turno
                        });

                        row.cells[4].innerText = turno.atendido;

                        row.cells[4].addEventListener('click', function () {
                            // Abrir modal o pop-up aquí
                            openEditModal(turno); // Llamar función para abrir el modal de edición con datos del turno
                        });

                        row.cells[5].innerText = turno.observaciones;
                        row.cells[5].title = turno.observaciones; // Muestra el texto completo en un tooltip



                        row.cells[5].addEventListener('click', function () {
                            // Abrir modal o pop-up aquí
                            openEditModal(turno); // Llamar función para abrir el modal de edición con datos del turno
                        });

                    }
                    else {
                        // Si no hay turno asignado, agregar evento de doble click para abrir el modal
                        row.cells[1].classList.add('empty-cell'); // Clase para identificar celdas vacías
                        row.cells[1].addEventListener('click', function () {
                            openCreateModal(intervalo, selectedDate); // Llamar función para abrir el modal de creación de nuevo turno
                        });
                    }

                });
            })
            .catch(error => {
                console.error('Error al cargar el horario:', error);
            });
    }

    profesionalSelect.addEventListener('change', () => {
        updateCalendar();
    });

    fetchProfesionales();
    updateCalendar();

    // Función para abrir el modal de edición con los datos del turno
    function openEditModal(turno) {
        // Rellenar el formulario con los datos del turno


        document.getElementById('turno_id').value = turno.id;
        document.getElementById('id_prof_edit').value = turno.id_prof;
        document.getElementById('fecha').value = formatDate(turno.fecha);
        document.getElementById('hora').value = turno.hora;
        document.getElementById('paciente_edit').value = turno.nombre_paciente;
        document.getElementById('paciente_id_edit').value = turno.paciente_id;
        document.getElementById('motivo').value = turno.motivo;
        document.getElementById('llego').value = turno.llego;
        document.getElementById('atendido').value = turno.atendido;
        document.getElementById('observaciones').value = turno.observaciones;
        obtenerUltimoTurno(turno.paciente_id)

        // Establecer el modo a 'edit'
        $('body').data('modal-mode', 'edit');
        // Asignar el ID del turno al botón 'Eliminar'
        document.getElementById('btnEliminar').setAttribute('data-turno-id', turno.id);

        // Agregar el evento de clic al botón 'Eliminar'
        document.getElementById('btnEliminar').onclick = function () {
            eliminarTurno(turno.id);
        }; // Usar un atributo de datos para el modo

        // Mostrar el modal
        $('#editTurnoModal').modal('show');
    }

    // Función para abrir el modal de creación de nuevo turno
    function openCreateModal(intervalo, selectedDate) {
        // Limpiar el formulario antes de abrir el modal (si es necesario)
        clearCreateForm(intervalo, selectedDate);

        // Establecer el modo a 'create'
        $('body').data('modal-mode', 'create'); // Usar un atributo de datos para el modo
        // Mostrar el modal
        $('#createTurnoModal').modal('show');
    }

    function eliminarTurno(id) {
        // Mostrar un cuadro de confirmación antes de eliminar
        const confirmacion = window.confirm('¿Estás seguro de que deseas eliminar este turno?');

        if (confirmacion) {
            // Realizar una solicitud AJAX para eliminar el turno si el usuario confirma
            fetch('./ABM/eliminarTurno.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': id
                })
            })
                .then(response => response.text())
                .then(data => {
                    // Manejar la respuesta del servidor
                    if (data === 'success') {
                        // Opcional: Cerrar el modal y/o actualizar la lista
                        $('#editTurnoModal').modal('hide');
                        alert('Turno eliminado con éxito');
                        // Actualizar la grilla de horarios o el calendario después de eliminar el turno
                        updateCalendar();
                        // Aquí puedes agregar código para actualizar la vista de la lista de turnos
                    } else {
                        alert('Error al eliminar el turno: ' + data); // Mostrar el mensaje de error completo
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            // El usuario canceló la operación
            console.log('Eliminación cancelada.');
        }
    }


    // Función para limpiar el formulario de creación de turno
    function clearCreateForm(intervalo, selectedDate) {

        // Limpiar campos del formulario según sea necesario
        document.getElementById('id_prof_input').value = profesionalSelect.value;
        document.getElementById('fecha_input').value = formatDate(selectedDate);
        document.getElementById('hora_input').value = intervalo;
        document.getElementById('paciente_input').value = '';
        document.getElementById('paciente_edit').value = '';
        document.getElementById('paciente_id_edit').value = '';
        document.getElementById('paciente_id').value = '';
        document.getElementById('motivo').value = '';
        document.getElementById('llego').value = '';
        document.getElementById('atendido').value = '';
        document.getElementById('observaciones').value = '';
        document.getElementById('id_paciente_turno_edit').value = '';
        document.getElementById('id_paciente_turno').value = '';
    }

    // Manejar el envío del formulario de creación de turno
    document.getElementById('createTurnoForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('./ABM/crearTurno.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(result => {
                alert(result);
                $('#createTurnoModal').modal('hide');
                // Actualizar la grilla de horarios o el calendario después de crear el turno
                updateCalendar();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Manejar el envío del formulario de editar de turno
    document.getElementById('editTurnoForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('./ABM/editarTurno.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(result => {
                alert(result);
                $('#editTurnoModal').modal('hide');
                // Actualizar la grilla de horarios o el calendario después de editar el turno
                updateCalendar();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

});

// CARGA DE LISTA DE PACIENTES
$(document).ready(function () {
    // Variables para almacenar los modales y la acción a realizar
    var actionToPerform = '';
    var buscarPacientesModal = new bootstrap.Modal(document.getElementById('buscarPacientesModal'));
    var createTurnoModal = new bootstrap.Modal(document.getElementById('createTurnoModal'));
    var editTurnoModal = new bootstrap.Modal(document.getElementById('editTurnoModal'));
    var agregarPacienteModal = new bootstrap.Modal(document.getElementById('agregarPacienteModal'));

    // Función para cargar pacientes con un término de búsqueda
    function cargarPacientes(busqueda) {
        $.ajax({
            url: './gets/get_pacientes.php',
            type: 'GET',
            data: { q: busqueda }, // Enviar el término de búsqueda al servidor
            dataType: 'json',
            success: function (pacientes) {
                var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
                html += '<thead class="table-custom">';
                html += '<tr>';
                html += '<th>Nombre</th>';
                html += '<th>Beneficio</th>';
                html += '<th>Parentesco</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                pacientes.forEach(function (paciente) {
                    html += '<tr data-id="' + paciente.id + '" data-name="' + paciente.nombre + '">'; // Agregar data-id y data-name
                    html += '<td>' + paciente.nombre + '</td>';
                    html += '<td>' + paciente.benef + '</td>';
                    html += '<td>' + paciente.parentesco + '</td>';
                    html += '</tr>';
                });

                html += '</tbody>';
                html += '</table>';

                $('#patientList').html(html);
            },
            error: function (error) {
                console.error("Error fetching patients: ", error);
            }
        });
    }


    // Manejar el clic en el botón "Agregar Paciente"
    document.getElementById('addPatient').addEventListener('click', function () {
        // Mostrar el modal de agregar paciente
        agregarPacienteModal.show();
    });

    // Cargar pacientes cuando se abre el modal
    $('#buscarPacientesModal').on('show.bs.modal', function () {
        cargarPacientes('');
    });

    // Buscar pacientes cuando se escribe en la barra de búsqueda
    $('#searchInput').on('keyup', function () {
        var query = $(this).val();
        cargarPacientes(query);
    });

    // Manejar clic en una fila de la tabla
    $('#patientList').on('click', 'tr', function () {
        var pacienteId = $(this).data('id');
        var pacienteNombre = $(this).data('name'); // Obtener el nombre del paciente

        // Rellenar el campo del formulario principal con el nombre del paciente y guardar el ID
        $('#paciente_input').val(pacienteNombre);
        $('#paciente_edit').val(pacienteNombre);
        $('#paciente_id_edit').val(pacienteId);
        $('#paciente_id').val(pacienteId);
        obtenerUltimoTurno(pacienteId)

        // Cerrar el modal de búsqueda
        buscarPacientesModal.hide();

        // Determinar qué modal abrir en función del modo actual
        actionToPerform = $('body').data('modal-mode');

        // Manejar el cierre y apertura de modales
        $('#buscarPacientesModal').one('hidden.bs.modal', function () {
            if (actionToPerform === 'edit') {
                editTurnoModal.show();
            } else if (actionToPerform === 'create') {
                createTurnoModal.show();
            }
        });
    });

    // Configurar el botón de cerrar y abrir otro modal
    document.getElementById('closeAndOpenModal').addEventListener('click', function () {
        // Cerrar el modal de búsqueda
        buscarPacientesModal.hide();

        // Esperar a que el modal de búsqueda se cierre completamente antes de mostrar el modal apropiado
        $('#buscarPacientesModal').one('hidden.bs.modal', function () {
            actionToPerform = $('body').data('modal-mode');
            if (actionToPerform === 'edit') {
                editTurnoModal.show();
            } else if (actionToPerform === 'create') {
                createTurnoModal.show();
            }
        });
    });

    // Acción luego de agregar paciente nuevo
    document.getElementById('formPaciente').addEventListener('submit', function (event) {
        event.preventDefault(); // Evita que el formulario se envíe de manera tradicional

        const formData = new FormData(this);

        fetch('./ABM/agregarPaciente.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const pacienteId = data.id; // Asegúrate de que tu PHP devuelva el ID del nuevo paciente

                    // Mostrar notificación
                    if (confirm('Paciente agregado exitosamente. ¿Desea continuar?')) {
                        // Llenar los campos del modal de edición con el nuevo paciente
                        $('#paciente_edit').val(formData.get('nombre'));
                        $('#paciente_input').val(formData.get('nombre'));
                        $('#paciente_id').val(pacienteId);
                        $('#paciente_id_edit').val(pacienteId);

                        // Cerrar modales y mostrar el nuevo modal en el orden correcto
                        agregarPacienteModal.hide();
                        buscarPacientesModal.hide();

                        // Usar un pequeño retraso para asegurar que los modales se cierren antes de mostrar el siguiente
                        setTimeout(function () {
                            actionToPerform = $('body').data('modal-mode');
                            if (actionToPerform === 'edit') {
                                editTurnoModal.show();
                            } else if (actionToPerform === 'create') {
                                createTurnoModal.show();
                            }
                        }, 300); // Ajusta el tiempo si es necesario
                    }
                } else {
                    console.error('Error al agregar paciente:', data.error);
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
            });
    });
});


//completar selects de modal agregar paciente
$(document).ready(function () {

    $.ajax({
        url: '../pacientes/dato/get_obras_sociales.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.siglas + ' - ' + item.razon_social;
                $('#obra_social').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: '../pacientes/dato/get_tipo_afiliado.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#tipo_afiliado').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });


    $.ajax({
        url: '../pacientes/dato/get_modalidad.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#modalidad').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: '../pacientes/dato/get_profesional.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#id_prof').append(new Option(item.nombreYapellido, item.id_prof));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

});

//boton buscar afiliado 
document.addEventListener('DOMContentLoaded', function () {
    // Obtener el botón por su ID
    var btnBuscar = document.getElementById('btnBuscar');

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function () {
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
                        $('#nombre').val(data.resultado);
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
    } else {
        console.error("No se encontró el elemento con ID 'btnBuscar'.");
    }
});

//IMPRIMIR TURNO DE PACIENTE
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('printTurnoButton').addEventListener('click', function () {
        // Importar jsPDF desde el objeto global
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Obtener datos del formulario
        const id_turno = document.getElementById('turno_id').value;

        // Función para obtener los datos del turno desde el servidor
        function fetchData(id_turno) {
            return new Promise((resolve, reject) => {
                fetch(`./gets/get_data_turno.php?id=${id_turno}`)
                    .then(response => response.json())
                    .then(data => resolve(data))
                    .catch(error => reject(error));
            });
        }

        // Función para obtener los parámetros desde el servidor
        function fetchParametros() {
            return new Promise((resolve, reject) => {
                fetch('./gets/get_parametros.php')
                    .then(response => response.json())
                    .then(data => resolve(data))
                    .catch(error => reject(error));
            });
        }

        // Generar el PDF después de obtener los datos
        Promise.all([fetchData(id_turno), fetchParametros()])
            .then(([dataTurno, dataParametros]) => {
                const profName = dataTurno.nom_prof;
                const fecha = formatDate(dataTurno.fecha);
                const hora = dataTurno.hora;
                const paciente = dataTurno.nombre_paciente;
                const motivo = dataTurno.motivo_full;

                // Obtener datos de parámetros
                const parametros = dataParametros[0] || {};
                const param1 = parametros.inst || 'No disponible';
                const param2 = parametros.localidad || 'No disponible';
                const param3 = parametros.tel || 'No disponible';

                // Agregar título al PDF centrado
                const title = 'CONSTANCIA DE TURNO';
                const pageWidth = doc.internal.pageSize.getWidth();
                const titleWidth = doc.getTextWidth(title);
                const xTitle = (pageWidth - titleWidth) / 2;
                doc.setFontSize(16);
                doc.text(title, xTitle, 10);

                // Calcular la posición vertical para los parámetros y la imagen
                const titleHeight = 10;
                const startY = 20 + titleHeight;

                // Agregar los parámetros al PDF
                doc.setFontSize(12);
                const param1Text = `Institución: ${param1}`;
                const param2Text = `Localidad: ${param2}`;
                const param3Text = `Tel: ${param3}`;

                const param1Width = doc.getTextWidth(param1Text);
                const param2Width = doc.getTextWidth(param2Text);
                const param3Width = doc.getTextWidth(param3Text);

                const xParam1 = (pageWidth - param1Width) / 2;
                const xParam2 = (pageWidth - param2Width) / 2;
                const xParam3 = (pageWidth - param3Width) / 2;

                doc.text(param1Text, xParam1, startY);
                doc.text(param2Text, xParam2, startY + 10);
                doc.text(param3Text, xParam3, startY + 20);

                const fechaHora = `${fecha} ${hora}`;
                // Agregar la tabla con los datos
                const headers = [['Campo', 'Detalle']];
                const rows = [
                    ['Paciente', paciente],
                    ['Fecha y Hora', fechaHora],
                    ['Profesional', profName],
                    ['Motivo', motivo]
                ];

                const marginLeft = 10; // Ajustar el margen izquierdo
                const tableWidth = pageWidth - 2 * marginLeft; // Ajustar el ancho de la tabla

                doc.autoTable({
                    head: headers,
                    body: rows,
                    startY: startY + 30, // Posición vertical para la tabla
                    margin: { left: marginLeft, top: 30 },
                    columnStyles: {
                        0: { cellWidth: 40 },
                        1: { cellWidth: 'auto' }
                    },
                    theme: 'striped'
                });

                // Calcular la posición para la imagen después de la tabla
                const imgUrl = '../img/logo.png'; // URL de la imagen
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2; // Centrar horizontalmente
                    const yImg = doc.autoTable.previous.finalY + 10; // Posición vertical de la imagen debajo de la tabla

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Descargar el PDF
                    window.open(doc.output('bloburl'))
                };
                img.src = imgUrl; // Cargar la imagen

            }).catch(error => {
                console.error('Error:', error);
            });
    });
});







$.ajax({
    url: './gets/get_todas_las_practicas.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            var optionText = item.codigo + ' - ' + item.descripcion;
            $('#motivo').append(new Option(optionText, item.id));
            $('#motivo_input').append(new Option(optionText, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

//IMPRIMIR TURNOS DE PROF
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('generatePdfBtn').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        const profesionalId = document.getElementById('profesionalSelect').value;
        const fechaDesde = document.getElementById('fechaDesde').value;
        const fechaHasta = document.getElementById('fechaHasta').value;

        function fetchDataProf(profesionalId, fechaDesde, fechaHasta) {
            return new Promise((resolve, reject) => {
                fetch(`./gets/get_turno_de_profesional.php?id_prof=${profesionalId}&fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`)
                    .then(response => response.json())
                    .then(data => resolve(data))
                    .catch(error => reject(error));
            });
        }

        function fetchParametros() {
            return new Promise((resolve, reject) => {
                fetch('./gets/get_parametros.php')
                    .then(response => response.json())
                    .then(data => resolve(data))
                    .catch(error => reject(error));
            });
        }

        Promise.all([fetchDataProf(profesionalId, fechaDesde, fechaHasta), fetchParametros()])
            .then(([dataTurnos, dataParametros]) => {
                const rows = dataTurnos.map(turnos => [
                    turnos.hora,
                    turnos.nombre_paciente,
                    turnos.motivo_full,
                    turnos.llego,
                    turnos.atendido,
                    turnos.observaciones
                ]);

                const nombreProfesional = dataTurnos.length > 0 ? dataTurnos[0].nom_prof : 'Desconocido';
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const parametros = dataParametros[0] || {};
                const param1 = parametros.inst || 'No disponible';
                const param2 = parametros.localidad || 'No disponible';
                const param3 = parametros.tel || 'No disponible';

                // Título centrado
                const title = 'Turnos Asignados';
                const pageWidth = doc.internal.pageSize.getWidth();
                const titleWidth = doc.getTextWidth(title);
                const xTitle = (pageWidth - titleWidth) / 2;
                doc.setFontSize(16);
                doc.text(title, xTitle, 10);

                // Fechas centradas
                const dateRange = `Desde: ${formattedFechaDesde} Hasta: ${formattedFechaHasta}`;
                const dateRangeWidth = doc.getTextWidth(dateRange);
                const xDateRange = (pageWidth - dateRangeWidth) / 2;
                doc.setFontSize(14);
                doc.text(dateRange, xDateRange, 20);

                // Parámetros centrados
                doc.setFontSize(12);
                const startY = 30;

                const param1Text = `Institución: ${param1}`;
                const param2Text = `Localidad: ${param2}`;
                const param3Text = `Telefono: ${param3}`;

                const param1Width = doc.getTextWidth(param1Text);
                const param1X = (pageWidth - param1Width) / 2;
                const param2Width = doc.getTextWidth(param2Text);
                const param2X = (pageWidth - param2Width) / 2;
                const param3Width = doc.getTextWidth(param3Text);
                const param3X = (pageWidth - param3Width) / 2;

                doc.text(param1Text, param1X, startY);
                doc.text(param2Text, param2X, startY + 10);
                doc.text(param3Text, param3X, startY + 20);

                // Subtítulo centrado
                const subtitle = `Profesional: ${nombreProfesional}`;
                const subtitleWidth = doc.getTextWidth(subtitle);
                const xSubtitle = (pageWidth - subtitleWidth) / 2;
                doc.setFontSize(12);
                doc.text(subtitle, xSubtitle, startY + 35);

                // Estimación del ancho total de la tabla
                const tableWidth = 200;
                let marginLeft = (pageWidth - tableWidth) / 2;
                marginLeft -= 15;

                doc.autoTable({
                    head: [['Hora', 'Paciente', 'Motivo', 'Llegó', 'Atendido', 'Observaciones']],
                    body: rows,
                    startY: startY + 50,
                    margin: { left: marginLeft, top: startY + 50 },
                    theme: 'striped',
                    styles: {
                        fontSize: 10, // Fuente más pequeña
                        cellPadding: 2,
                        overflow: 'linebreak'
                    },
                    columnStyles: {
                        0: { cellWidth: 30 },
                        1: { cellWidth: 70 },
                        2: { cellWidth: 40 },
                        3: { cellWidth: 20 },
                        4: { cellWidth: 20 },
                        5: { cellWidth: 50 }
                    }
                });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = doc.internal.pageSize.height - imgHeight - 10;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'))
                };
                img.src = imgUrl;
            }).catch(error => {
                console.error('Error:', error);
            });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('recordatorioBtn').addEventListener('click', function () {
        const profesionalId = document.getElementById('profesionalSelect').value;
        const fechaDesde = document.getElementById('fechaDesde').value;
        const fechaHasta = document.getElementById('fechaHasta').value;

        // Validar que todos los campos estén llenos
        if (!profesionalId || !fechaDesde || !fechaHasta) {
            alert('Por favor, complete todos los campos.');
            return;
        }

        // Redirigir a la URL con los parámetros
        const url = `./gets/generar_excel_para_recordatorio.php?profesional=${profesionalId}&fechaDesde=${fechaDesde}&fechaHasta=${fechaHasta}`;
        window.location.href = url;
    });
});













