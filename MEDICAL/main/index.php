<?php require_once './app.php'; ?>
<?php require_once './componentes/init.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <!-- Icono de pestaña -->
    <link rel="icon" href="./img/logo.png" type="image/x-icon">
    <?php include './componentes/head-resources.php'; ?>
    <link rel="stylesheet" href="./estilos/styleIndex.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Contenido principal -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-start py-5">
        <div class="container">
            <div class="blurred-box">
                <div class="text-center my-4">
                    <img src="./img/logoBlanco.png" alt="Logo MEDICAL" class="img-fluid" style="max-width: 15rem;">
                </div>
                <div class="card-login">
                    <?php include './componentes/header-title.php'; ?>
                    <div class="card-body">
                        <form action="./login/login_user.php" method="POST">
                            <div class="form-group rounded-pill">
                                <label for="usuario">Usuario:</label>
                                <input type="text" name="usuario" id="usuario" class="form-control" required>
                            </div>
                            <div class="form-group rounded-pill">
                                <label for="clave">Contraseña:</label>
                                <input type="password" name="clave" id="clave" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-custom rounded-pill">Iniciar
                                sesión</button>
                            <div class="text-center mt-3 cutom-user">
                                <a href="./login/admin.php">Soy administrador</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer siempre abajo -->
    <?php
    $footerColor = 'light';
    include './componentes/footer.php';
    ?>

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <?php if (!empty($errorMsg)): ?>
        <script>
            Toastify({
                text: "<?= $errorMsg ?>",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                close: true
            }).showToast();
        </script>
    <?php endif; ?>
</body>

</html>