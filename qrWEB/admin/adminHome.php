<?php
include('../conect.php');
session_start();

// Definir la tabla por defecto o la seleccionada
$tabla_seleccionada = isset($_GET['tabla']) ? $_GET['tabla'] : 'usuarios_rosario';

// Consulta dinámica dependiendo de la tabla seleccionada
$sql = "SELECT * FROM $tabla_seleccionada";
$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Administración de Usuarios</h2>

        <!-- Botones para cambiar de tabla -->
        <div class="text-center mb-3">
            <a href="?tabla=usuarios_rosario"
                class="btn btn-primary <?php echo ($tabla_seleccionada == 'usuarios_rosario') ? 'active' : ''; ?>">Usuarios
                Rosario</a>
            <a href="?tabla=usuarios_wss"
                class="btn btn-primary <?php echo ($tabla_seleccionada == 'usuarios_wss') ? 'active' : ''; ?>">Usuarios
                WSS</a>
        </div>

        <!-- Botón para agregar un nuevo usuario -->
        <div class="text-center mb-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar
                Usuario</button>
        </div>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Password</th>
                    <th>Cant. de usos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['cant_qr'] . "</td>";
                        echo "<td>";
                        echo "<form method='post' action='./abm/eliminar_usuario.php' style='display:inline-block;' onsubmit='return confirmDelete();' id='formEliminar_" . $row['id'] . "'>";
                        echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                        echo "<input type='hidden' name='tabla' value='" . $tabla_seleccionada . "'>";
                        echo "<button type='button' class='btn btn-danger btn-sm' onclick='eliminarUsuario(" . $row['id'] . ")'>Eliminar</button>";
                        echo "</form>";
                        echo "</form>";
                        echo "<button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editUserModal' onclick='loadUserData(" . $row['id'] . ", \"" . $row['user'] . "\", \"" . $row['password'] . "\", \"" . $row['cant_qr'] . "\")'>Editar</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar usuario -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenedor para el mensaje -->
                    <div id="mensajeUsuario" class="alert" style="display: none;"></div>

                    <form id="formAgregarUsuario" action="./abm/agregar_usuario.php" method="POST"
                        onsubmit="agregarUsuario(event);">
                        <input type="hidden" name="tabla" value="<?php echo $tabla_seleccionada; ?>">
                        <div class="mb-3">
                            <label for="user" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="user" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarUsuario" onsubmit="editarUsuario(event);">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="hidden" name="tabla" value="<?php echo $tabla_seleccionada; ?>">
                        <div class="mb-3">
                            <label for="user" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="user" id="edit_user" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="edit_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="cant_qr" class="form-label">Cantidad de usos</label>
                            <input type="number" class="form-control" name="cant_qr" id="edit_cant_qr" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function agregarUsuario(event) {
            event.preventDefault(); // Evita que el formulario se envíe de manera convencional

            var form = document.getElementById("formAgregarUsuario");
            var formData = new FormData(form);
            var mensajeUsuario = document.getElementById("mensajeUsuario");

            // Limpiar el mensaje anterior
            mensajeUsuario.style.display = "none";
            mensajeUsuario.className = "alert"; // Reiniciar clases

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    mensajeUsuario.style.display = "block"; // Mostrar el contenedor de mensaje

                    if (data.success) {
                        mensajeUsuario.className = "alert alert-success"; // Clase para éxito
                        mensajeUsuario.innerHTML = data.message; // Muestra el mensaje de éxito

                        // Recargar la tabla de usuarios
                        return fetch('./abm/obtener_usuarios.php?tabla=' + formData.get('tabla'));
                    } else {
                        mensajeUsuario.className = "alert alert-danger"; // Clase para error
                        mensajeUsuario.innerHTML = data.message; // Muestra el mensaje de error
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.querySelector('tbody').innerHTML = html; // Actualiza la tabla con el nuevo HTML
                })
                .catch(error => {
                    console.error('Error:', error);
                    mensajeUsuario.style.display = "block"; // Mostrar el contenedor de mensaje
                    mensajeUsuario.className = "alert alert-danger"; // Clase para error
                    mensajeUsuario.innerHTML = "Error al agregar el usuario.";
                });
        }



        function loadUserData(id, user, password, cant_qr) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_user').value = user;
            document.getElementById('edit_password').value = password;
            document.getElementById('edit_cant_qr').value = cant_qr;
        }

        function confirmDelete() {
            return confirm('¿Estás seguro de que quieres eliminar este usuario?');
        }

        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                var formData = new FormData();
                formData.append('delete_id', id);
                formData.append('tabla', '<?php echo $tabla_seleccionada; ?>');

                fetch('./abm/eliminar_usuario.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            // Recargar la tabla de usuarios
                            return fetch('./abm/obtener_usuarios.php?tabla=' + formData.get('tabla'));
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('tbody').innerHTML = html; // Actualiza la tabla con el nuevo HTML
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al eliminar el usuario.');
                    });
            }
        }




        function editarUsuario(event) {
            event.preventDefault(); // Evita el envío normal del formulario

            var form = document.getElementById("formEditarUsuario");
            var formData = new FormData(form);

            fetch('./abm/editar_usuario.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        // Recargar la tabla de usuarios
                        return fetch('./abm/obtener_usuarios.php?tabla=' + formData.get('tabla'));
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.querySelector('tbody').innerHTML = html; // Actualiza la tabla con el nuevo HTML
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el usuario.');
                });
        }


    </script>
</body>

</html>


<?php
$conexion->close();
?>