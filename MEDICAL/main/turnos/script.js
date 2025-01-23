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


    function renderCalendar(disponibilidad = [], turnos = [], ausencias = []) {
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

        const daysOfWeek = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

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


        const daysOnVacation = new Set();

        ausencias.forEach(ausencia => {
            const startDate = new Date(ausencia.fecha_inicio);
            const endDate = new Date(ausencia.fecha_fin);

            startDate.setDate(startDate.getDate() + 1);
            // Agregar un día a la fecha
            endDate.setDate(endDate.getDate() + 1);

            // Marcar todos los días dentro del rango de la ausencia
            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                if (d.getMonth() === month) { // Asegurarse que esté en el mes correcto
                    daysOnVacation.add(d.getDate());
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

            // Pintar los días de vacaciones de gris
            if (daysOnVacation.has(i)) {
                dayElement.style.backgroundColor = '#C19ADE';  // Gris para días de vacaciones
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
                renderCalendar(data.disponibilidad, data.todos_turnos, data.ausencias);

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

                // Crear un array con las horas de las ausencias y los motivos
                const ausenciasHoras = data.ausencias.map(aus => {
                    const ausenciaStart = new Date(aus.fecha_inicio);

                    // Crear un objeto Date para la fecha de fin y establecer la hora a las 23:59:59
                    const ausenciaEnd = new Date(aus.fecha_fin);
                    // Agregar un día a la fecha
                    ausenciaEnd.setDate(ausenciaEnd.getDate() + 1);
                    return {
                        start: ausenciaStart,
                        end: ausenciaEnd,
                        motivo: aus.motivo // Obtener el motivo de la ausencia
                    };
                });


                intervalos.forEach(intervalo => {
                    const row = scheduleBody.insertRow();
                    row.insertCell(0).innerText = intervalo;

                    for (let i = 1; i < 7; i++) {
                        row.insertCell(i).innerText = '';
                    }

                    const turno = data.turnos.find(turno => turno.hora.startsWith(intervalo));
                    // Comprobar si el intervalo está dentro de una ausencia
                    const isAbsent = ausenciasHoras.some(ausencia => {
                        const intervaloHora = new Date(`${selectedDate}T${intervalo}:00`);

                        // Compara correctamente el intervalo con la ausencia
                        // Asegúrate de que el final de la ausencia se considere hasta el último segundo del 29
                        return intervaloHora >= ausencia.start && intervaloHora <= ausencia.end;
                    });

                    // Si es una ausencia, marcar la celda con el motivo
                    if (isAbsent) {
                        // Buscar el motivo de la ausencia para ese intervalo
                        const ausencia = ausenciasHoras.find(ausencia => {
                            const intervaloHora = new Date(`${selectedDate}T${intervalo}:00`);

                            // Compara correctamente el intervalo con la ausencia
                            return intervaloHora >= ausencia.start && intervaloHora <= ausencia.end;
                        });

                        row.cells[1].innerText = ausencia ? ausencia.motivo : 'Motivo desconocido';
                        row.cells[1].style.backgroundColor = '#d3d3d3'; // Gris para ausente
                        row.cells[1].classList.add('absent-cell');
                        for (let i = 2; i < 7; i++) {
                            row.cells[i].style.backgroundColor = '#d3d3d3'; // Resto de las celdas también en gris
                        }
                    } else {
                        if (turno) {
                            row.cells[1].innerText = turno.nombre_paciente;
                            row.cells[1].classList.add('editable-cell');
                            row.cells[1].addEventListener('click', () => openEditModal(turno));
                            row.cells[2].innerText = turno.motivo_full;
                            row.cells[2].addEventListener('click', () => openEditModal(turno));
                            row.cells[3].innerText = turno.llego;
                            row.cells[3].addEventListener('click', () => openEditModal(turno));
                            row.cells[4].innerText = turno.atendido;
                            row.cells[4].addEventListener('click', () => openEditModal(turno));
                            row.cells[5].innerText = turno.observaciones;
                            row.cells[5].title = turno.observaciones;
                            row.cells[5].addEventListener('click', () => openEditModal(turno));
                            row.cells[6].innerText = turno.telefono || '';
                            row.cells[6].title = turno.telefono || '';
                            row.cells[6].addEventListener('click', () => openEditModal(turno));

                            // Establecer colores en base a la llegada y atención
                            if (turno.llego === 'SI' && turno.atendido === 'SI') {
                                for (let i = 0; i < 7; i++) {
                                    row.cells[i].style.backgroundColor = '#7abaf5'; // Color si llegó y fue atendido
                                }
                            } else if (turno.llego === 'SI') {
                                for (let i = 0; i < 7; i++) {
                                    row.cells[i].style.backgroundColor = '#ff8d30'; // Color si llegó pero no fue atendido
                                }
                            }
                        } else {
                            // Si no hay turno en el intervalo, marcar la celda como vacía y habilitarla para agregar turno
                            for (let i = 1; i < 7; i++) {
                                row.cells[i].classList.add('empty-cell');
                                row.cells[i].addEventListener('click', () => openCreateModal(intervalo, selectedDate));
                            }
                        }
                    }
                });

                // Añadir Sobreturno al Final
                const sobreturnos = data.turnos.filter(turno => !intervalos.some(intervalo => turno.hora.startsWith(intervalo)));
                sobreturnos.forEach(sobreturno => {
                    const sobreturnoRow = scheduleBody.insertRow();
                    sobreturnoRow.classList.add('sobreturno-cell'); // Estilo especial para sobreturno
                    sobreturnoRow.insertCell(0).innerText = `Sobreturno: ${sobreturno.hora}`;

                    for (let i = 1; i < 7; i++) {
                        sobreturnoRow.insertCell(i).innerText = '';
                    }

                    sobreturnoRow.cells[1].innerText = sobreturno.nombre_paciente;
                    sobreturnoRow.cells[1].addEventListener('click', () => openEditModal(sobreturno));
                    sobreturnoRow.cells[2].innerText = sobreturno.motivo_full;
                    sobreturnoRow.cells[2].addEventListener('click', () => openEditModal(sobreturno));
                    sobreturnoRow.cells[3].innerText = sobreturno.llego;
                    sobreturnoRow.cells[3].addEventListener('click', () => openEditModal(sobreturno));
                    sobreturnoRow.cells[4].innerText = sobreturno.atendido;
                    sobreturnoRow.cells[4].addEventListener('click', () => openEditModal(sobreturno));
                    sobreturnoRow.cells[5].innerText = sobreturno.observaciones;
                    sobreturnoRow.cells[5].title = sobreturno.observaciones;
                    sobreturnoRow.cells[5].addEventListener('click', () => openEditModal(sobreturno));
                    sobreturnoRow.cells[6].innerText = sobreturno.telefono;
                    sobreturnoRow.cells[6].title = sobreturno.telefono;
                    sobreturnoRow.cells[6].addEventListener('click', () => openEditModal(sobreturno));

                    // Lógica de colores para los sobreturnos
                    if (sobreturno.llego === 'SI' && sobreturno.atendido === 'SI') {
                        for (let i = 0; i < 7; i++) {
                            sobreturnoRow.cells[i].style.backgroundColor = '#7abaf5'; // Color si llegó y fue atendido
                        }
                    } else if (sobreturno.llego === 'SI') {
                        for (let i = 0; i < 7; i++) {
                            sobreturnoRow.cells[i].style.backgroundColor = '#ff8d30'; // Color si llegó pero no fue atendido
                        }
                    }
                });

                // Crear la fila con el botón para añadir sobreturno
                const addSobreturnoRow = scheduleBody.insertRow();
                const addSobreturnoCell = addSobreturnoRow.insertCell(0);
                addSobreturnoCell.colSpan = 7;
                addSobreturnoCell.style.textAlign = 'center';

                const addSobreturnoButton = document.createElement('button');
                addSobreturnoButton.innerText = 'Agregar Sobreturno';
                addSobreturnoButton.classList.add('btn', 'btn-custom'); // Agregar clases de estilo
                addSobreturnoButton.addEventListener('click', () => openCreateModal(null, selectedDate));

                addSobreturnoCell.appendChild(addSobreturnoButton);
            })
            .catch(error => console.error('Error al cargar el horario:', error));
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
        document.getElementById('fechas_input').value = formatDate(selectedDate);
        document.getElementById('hora_input').value = intervalo;
        document.getElementById('paciente_input').value = '';
        document.getElementById('paciente_edit').value = '';
        document.getElementById('paciente_id_edit').value = '';
        document.getElementById('paciente_id').value = '';
        document.getElementById('llego_input').value = 'NO';
        document.getElementById('atendido_input').value = 'NO';
        document.getElementById('observaciones').value = '';
        document.getElementById('id_paciente_turno_edit').value = '';
        document.getElementById('id_paciente_turno').value = '';

        // Ocultar el botón "Agregar"
        const addButton = document.getElementById('add_modalidad_button');
        addButton.style.display = 'none';
    }


    $(document).ready(function () {
        $('#fechas_input').datepicker({
            format: 'dd/mm/yyyy',    // Formato de fecha que se enviará
            multidate: true,         // Permitir múltiples selecciones
            todayHighlight: true,    // Resaltar la fecha actual
            autoclose: false         // Mantener abierto el selector hasta que se haga clic fuera
        });
    });

    // Manejar el envío del formulario de creación de turno
    document.getElementById('createTurnoForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const fechas = document.getElementById('fechas_input').value.split(','); // Separar las fechas seleccionadas

        fechas.forEach(fecha => {
            formData.set('fecha_input', fecha.trim());  // Configurar cada fecha individualmente

            fetch('./ABM/crearTurno.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    $('#createTurnoModal').modal('hide');
                    updateCalendar();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
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




//completar selects de modal agregar paciente
$(document).ready(function () {

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

$(document).ready(function () {

    $('#modalidad_act_turno').on('change', function () {
        const modalidadValue = $(this).val();
        if (modalidadValue) {
            $('#add_modalidad_button').show(); // Mostrar el botón
        } else {
            $('#add_modalidad_button').hide(); // Ocultar el botón
        }
    });

    // Acción al hacer clic en el botón
    $('#add_modalidad_button').on('click', function () {
        const modalidadValue = $('#modalidad_act_turno').val();
        const pacienteId = $('#paciente_id').val();

        if (modalidadValue && pacienteId) {
            // Llamar a PHP con AJAX
            $.ajax({
                url: './ABM/agregar_modaliad_paci.php',
                type: 'POST',
                data: { modalidad: modalidadValue, paciente_id: pacienteId },
                success: function (response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            alert('Modalidad agregada con éxito');

                            // Recargar el select `motivo`
                            $.ajax({
                                url: './gets/get_todas_las_practicas.php',
                                type: 'GET',
                                dataType: 'json',
                                data: { paciente_id: pacienteId },
                                success: function (data) {
                                    $('#motivo_input').empty();

                                    data.forEach(function (item) {
                                        const optionText = item.codigo + ' - ' + item.descripcion;
                                        $('#motivo_input').append(new Option(optionText, item.id));
                                    });
                                },
                                error: function (error) {
                                    console.error('Error al recargar los motivos:', error);
                                }
                            });
                        } else {
                            alert('Hubo un error al agregar la modalidad.');
                        }
                    } catch (e) {
                        console.error('Error en la respuesta del servidor:', response);
                        alert('Respuesta inesperada del servidor.');
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud:', error);
                }
            });
        } else {
            alert('Por favor, seleccione una modalidad válida.');
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Elemento <select>
    const motivoSelect = document.getElementById("motivo");

    // Función para cargar actividades
    function cargarActividades() {
        fetch("./gets/get_practicas.php") // Cambia la ruta si es necesario
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error al obtener las actividades");
                }
                return response.json();
            })
            .then((data) => {
                // Vaciar opciones previas
                motivoSelect.innerHTML = '<option value="">Seleccionar...</option>';

                // Añadir opciones al <select>
                data.forEach((actividad) => {
                    const option = document.createElement("option");
                    option.value = actividad.id; // Usar el ID como valor
                    option.textContent = `${actividad.codigo} - ${actividad.descripcion}`; // Mostrar código y descripción
                    motivoSelect.appendChild(option);
                });
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    // Cargar actividades al cargar la página
    cargarActividades();
});





$(document).on('click', '.list-group-item', function () {
    var pacienteNombre = $(this).text(); // Obtener el texto seleccionado
    var pacienteId = $(this).data('id'); // Obtener el ID del atributo data-id


    if (pacienteId) {
        // Asignar los valores al input visible y al oculto
        $('#paciente_input').val(pacienteNombre);
        $('#paciente_id').val(pacienteId).trigger('change'); // Trigger para otros eventos dependientes

        // Limpiar la lista de sugerencias
        $('#pacientes_list').empty();

        // Realizar la llamada AJAX
        $.ajax({
            url: './gets/get_todas_las_practicas.php',
            type: 'GET',
            dataType: 'json',
            data: { paciente_id: pacienteId }, // Pasar el paciente_id al PHP
            success: function (data) {
                $('#motivo').empty(); // Limpiar opciones anteriores
                $('#motivo_input').empty(); // Limpiar opciones anteriores

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
    } else {
        console.error("El ID del paciente no es válido.");
    }
});






//IMPRIMIR TURNOS DE PROF
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('generatePdfBtn').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        let profesionalId = document.getElementById('profesionalSelect').value;
        const fechaDesde = document.getElementById('fechaDesde').value;
        const fechaHasta = document.getElementById('fechaHasta').value;

        // Si es "all", no filtrar por profesional
        profesionalId = profesionalId === "all" ? "" : profesionalId;

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
                // Agrupar turnos por profesional
                const turnosPorProfesional = dataTurnos.reduce((acc, turno) => {
                    const nombreProfesional = turno.nom_prof || 'Desconocido';
                    if (!acc[nombreProfesional]) {
                        acc[nombreProfesional] = [];
                    }
                    acc[nombreProfesional].push([
                        turno.hora,
                        turno.nombre_paciente,
                        formatDate(turno.fecha),
                        turno.observaciones
                    ]);
                    return acc;
                }, {});

                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const parametros = dataParametros[0] || {};
                const param1 = parametros.inst || 'No disponible';
                const param2 = parametros.localidad || 'No disponible';
                const param3 = parametros.tel || 'No disponible';

                // Configuración de la página
                const title = 'Turnos Asignados';
                const pageWidth = doc.internal.pageSize.getWidth();
                const titleWidth = doc.getTextWidth(title);
                const xTitle = (pageWidth - titleWidth) / 2;
                doc.setFontSize(16);
                doc.text(title, xTitle, 10);

                const dateRange = `Desde: ${formattedFechaDesde} Hasta: ${formattedFechaHasta}`;
                const dateRangeWidth = doc.getTextWidth(dateRange);
                const xDateRange = (pageWidth - dateRangeWidth) / 2;
                doc.setFontSize(14);
                doc.text(dateRange, xDateRange, 20);

                doc.setFontSize(12);
                const startY = 30;
                const marginX = 15; // Margen inicial desde la izquierda
                const spacing = 10; // Espaciado horizontal entre textos

                const param1Text = `Institución: ${param1}`;
                const param2Text = `Localidad: ${param2}`;
                const param3Text = `Telefono: ${param3}`;

                // Posiciones X para cada texto
                let currentX = marginX;
                doc.text(param1Text, currentX, startY); // Primer texto
                currentX += doc.getTextWidth(param1Text) + spacing; // Suma el ancho del texto + espaciado
                doc.text(param2Text, currentX, startY); // Segundo texto
                currentX += doc.getTextWidth(param2Text) + spacing; // Suma el ancho del texto + espaciado
                doc.text(param3Text, currentX, startY); // Tercer texto);

                let currentY = startY + 10;

                // Renderizar la tabla de turnos para cada profesional
                Object.keys(turnosPorProfesional).forEach((profesional, index) => {
                    if (index > 0) {
                        doc.addPage();
                        currentY = 5;
                    }

                    // Título de profesional
                    const subtitle = `Profesional: ${profesional}`;
                    const subtitleWidth = doc.getTextWidth(subtitle);
                    const xSubtitle = (pageWidth - subtitleWidth) / 2;
                    doc.setFontSize(12);
                    doc.text(subtitle, xSubtitle, currentY);
                    currentY += 3;

                    // Configuración de la tabla para los turnos del profesional actual
                    doc.autoTable({
                        head: [['Hora', 'Paciente', 'Fecha', 'Observaciones']],
                        body: turnosPorProfesional[profesional],
                        startY: currentY + 4,
                        margin: { left: 2, right: 2 },
                        theme: 'striped',
                        styles: {
                            fontSize: 10,
                            cellPadding: 2,
                            overflow: 'linebreak'
                        },
                        columnStyles: {
                            0: { cellWidth: 20 },
                            1: { cellWidth: 170 },
                            2: { cellWidth: 25 },
                            3: { cellWidth: 75 },
                        }
                    });
                });

                // Agregar el logo al final de la última página
                const imgUrl = '../img/logo.png';
                const img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = doc.internal.pageSize.height - imgHeight - 10;
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;
            }).catch(error => {
                console.error('Error:', error);
            });
    });

});

document.addEventListener('DOMContentLoaded', function () {
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

//AGREGAR PACIENTE
$(document).ready(function () {
    $(document).ready(function () {
        function buscarPacientes(inputSelector, listSelector, hiddenIdSelector) {
            $(inputSelector).on('keyup', function () {
                let searchQuery = $(this).val();

                if (searchQuery.length > 2) { // Ejecutar la búsqueda solo si el término tiene más de 2 caracteres
                    $.ajax({
                        url: './gets/get_pacientes.php',
                        method: 'GET',
                        data: { q: searchQuery },
                        success: function (data) {
                            let pacientesList = $(listSelector);
                            pacientesList.empty(); // Limpiar la lista anterior

                            data.forEach(function (paciente) {
                                let pacienteOption = `<a href="#" class="list-group-item list-group-item-action" data-id="${paciente.id}" data-nombre="${paciente.nombre}" data-modalidad="${paciente.modalidad}">${paciente.nombre}</a>`;
                                pacientesList.append(pacienteOption);
                            });
                        }
                    });
                } else {
                    $(listSelector).empty(); // Limpiar la lista si no hay suficientes caracteres
                }
            });

            // Manejar el clic en el paciente seleccionado
            $(listSelector).on('click', 'a', function (e) {
                e.preventDefault();
                let nombre = $(this).data('nombre');
                let id = $(this).data('id');
                let modalidad = $(this).data('modalidad');

                $(inputSelector).val(nombre);
                $(hiddenIdSelector).val(id);
                // Seleccionar automáticamente la modalidad en el dropdown
                if (modalidad) {
                    $('#modalidad_act_turno').val(modalidad);
                }
                $(listSelector).empty(); // Limpiar la lista de resultados
            });

            // Ocultar lista de resultados si se hace clic fuera del input
            $(document).click(function (e) {
                if (!$(e.target).closest(inputSelector).length && !$(e.target).closest(listSelector).length) {
                    $(listSelector).empty();
                }
            });
        }

        // Inicializar la función para ambos inputs
        buscarPacientes('#paciente_input', '#pacientes_list', '#paciente_id');
        buscarPacientes('#paciente_edit', '#pacientes_list_edit', '#paciente_id_edit');
    });
});

//AGREGAR PACIENTE
document.addEventListener('DOMContentLoaded', function () {
    // Al abrir el modal "agregarPacienteModal", cerrar "createTurnoModal"
    $('#agregarPacienteModal').on('show.bs.modal', function () {
        $('#createTurnoModal').modal('hide'); // Oculta el modal principal
    });

    // Al cerrar el modal "agregarPacienteModal", volver a abrir "createTurnoModal"
    $('#agregarPacienteModal').on('hidden.bs.modal', function () {
        $('#createTurnoModal').modal('show'); // Vuelve a mostrar el modal principal
    });
});

// Función para convertir el input en un select
function convertirInputEnSelect() {
    var uglInput = document.getElementById('ugl_paciente');
    var uglValue = uglInput.value;  // Guardar el valor actual

    // Crear el nuevo elemento select
    var selectUGL = document.createElement('select');
    selectUGL.className = 'form-control';
    selectUGL.id = 'ugl_paciente';
    selectUGL.name = 'ugl_paciente';
    selectUGL.required = true;

    // Agregar la opción predeterminada
    var defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccione UGL';
    selectUGL.appendChild(defaultOption);

    // Obtener UGLs mediante AJAX
    fetch('../pacientes/dato/obtener_ugl.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                var option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.descripcion;
                selectUGL.appendChild(option);
            });

            // Establecer el valor actual si está disponible
            selectUGL.value = uglValue;

            // Reemplazar el input por el select
            uglInput.parentNode.replaceChild(selectUGL, uglInput);
        })
        .catch(error => console.error('Error al cargar UGLs:', error));
}




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
        url: '../pacientes/dato/get_modalidad.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#modalidad_act').append(new Option(optionText, item.id));
                $('#modalidad_act_turno').append(new Option(optionText, item.id));
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
        url: '../pacientes/dato/get_bocas_atencion.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.boca;
                $('#boca_atencion').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('btnCompletarManualmente').addEventListener('click', function () {
        var nombreInput = document.getElementById('nombre');
        nombreInput.removeAttribute('readonly');  // Elimina el atributo readonly
        nombreInput.focus();  // Opcional: pone el foco en el campo para que el usuario pueda escribir

        // Para 'fecha_nac'
        var fechaNacInput = document.getElementById('fecha_nac');
        fechaNacInput.removeAttribute('readonly');  // Elimina el atributo readonly
        fechaNacInput.focus();  // Opcional: pone el foco en el campo para que el usuario pueda escribir

        // Para 'ugl_paciente'
        convertirInputEnSelect();  // Llama a la función para convertir el input en select

        // Enfoca el nuevo select si es necesario
        var uglPacienteSelect = document.getElementById('ugl_paciente');
        uglPacienteSelect.focus();  // Opcional: pone el foco en el select
    });

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
                    function convertDateFormat(dateStr) {
                        const parts = dateStr.split('/');
                        // Asegúrate de que tienes el formato esperado
                        if (parts.length === 3) {
                            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Devuelve en formato "yyyy-MM-dd"
                        }
                        return dateStr; // Devuelve original si no es un formato esperado
                    }



                    // Actualizar el campo de nombre y apellido con el resultado
                    $('#ugl_paciente').val(data.resultado.ugl);
                    $('#nombre').val(data.resultado.nombreApellido); // Asigna nombre y apellido
                    // Convertir la fecha y asignarla
                    const fechaNac = convertDateFormat(data.resultado.fecha_nac);
                    $('#fecha_nac').val(fechaNac); // Asigna fecha de nacimiento

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

    document.getElementById('guardarPacienteBtn').addEventListener('click', function (e) {
        e.preventDefault(); // Evita la acción predeterminada del formulario

        // Obtén el formulario
        const form = document.getElementById('formPaciente');

        // Crear el objeto FormData con los datos del formulario
        const formData = new FormData(form);

        // Enviar la solicitud al servidor usando fetch
        fetch(form.action, {
            method: form.method,
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mostrar alerta de éxito
                    alert(data.message || "Paciente agregado correctamente.");

                    // Cerrar el modal actual
                    const modal = bootstrap.Modal.getInstance(document.getElementById('agregarPacienteModal'));
                    if (modal) {
                        modal.hide();
                    }

                    $('#agregarPacienteModal').on('hidden.bs.modal', function () {
                        $('#createTurnoModal').modal('show'); // Vuelve a mostrar el modal principal
                    });

                    // Asignar los valores al formulario externo
                    document.getElementById('paciente_input').value = data.nombre; // Asigna el nombre concatenado
                    document.getElementById('paciente_id').value = data.id;       // Asigna el ID



                    // Limpia el formulario actual
                    form.reset();
                } else {
                    // Mostrar alerta de error
                    alert(data.message || "Error al agregar el paciente.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error al procesar la solicitud.");
            });
    });

});

document.addEventListener("DOMContentLoaded", function () {
    // Obtener el botón de generar QR
    const qrButton = document.getElementById("qrPaciente");

    if (qrButton) {
        qrButton.addEventListener("click", function () {
            const pacienteInfo = document.getElementById("paciente_edit").value;

            if (pacienteInfo) {
                // Extraer el contenido relevante del paciente
                const match = pacienteInfo.match(/(\d{12})\/(\d{2})/);
                if (match) {
                    const qrContent = `${match[1]}-${match[2]}`;

                    // Generar el QR en el contenedor del modal
                    const qrContainer = document.getElementById("qrContainer");
                    qrContainer.innerHTML = ""; // Limpiar cualquier QR previo
                    new QRCode(qrContainer, {
                        text: qrContent,
                        width: 128,
                        height: 128,
                    });

                    // Mostrar el modal del QR
                    const qrModal = new bootstrap.Modal(document.getElementById("qrModal"));
                    qrModal.show();
                } else {
                    alert("No se encontró un número válido en la información del paciente.");
                }
            } else {
                alert("El campo de información del paciente está vacío.");
            }
        });
    } else {
        console.error("El botón con id 'qrPaciente' no se encuentra en el DOM.");
    }
});











