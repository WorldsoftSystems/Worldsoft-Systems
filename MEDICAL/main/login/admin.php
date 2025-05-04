<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!--icono pestana-->
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <?php include '../componentes/head-resources.php'; ?>
</head>

<body class="d-flex flex-column min-vh-100 bg-info-subtle">

    <!-- BOTÓN VOLVER -->
    <div class="container mt-3">
        <?php
        $href = '../index.php';
        $texto = 'VOLVER';
        include '../componentes/boton-volver.php';
        ?>
    </div>

    <!-- CONTENIDO CENTRADO -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="shadow-lg border-0 rounded-4 p-3"
            style="max-width: 420px; width: 100%; background-color: var(--primary-color); color: var(--text-light);">
            <div class="card-header text-center bg-transparent border-0">
                <h4 class="mb-0">Iniciar sesión como admin</h4>
            </div>
            <div class="card-body">
                <form action="login_admin.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label fw-semibold">Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control rounded-3" required
                            autocomplete="username">
                    </div>
                    <div class="mb-4">
                        <label for="clave" class="form-label fw-semibold">Contraseña</label>
                        <input type="password" name="clave" id="clave" class="form-control rounded-3" required
                            autocomplete="current-password">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-light text-primary fw-bold rounded-3">Iniciar
                            sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php include '../componentes/footer.php'; ?>

    <script>
        const loginError = "<?= isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '' ?>";

        document.addEventListener('DOMContentLoaded', () => {
            if (loginError) {
                const message = loginError === 'credenciales'
                    ? 'Contraseña incorrecta.'
                    : loginError === 'usuario'
                        ? 'Usuario no encontrado.'
                        : '';

                if (message) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                    alertDiv.style.zIndex = '1055'; // encima de todo
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        `;

                    document.body.appendChild(alertDiv);

                    // Auto-cerrar después de 5s
                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        alertDiv.classList.add('fade');
                        setTimeout(() => alertDiv.remove(), 500);
                    }, 5000);
                }
            }
        });
    </script>

</body>

</html>