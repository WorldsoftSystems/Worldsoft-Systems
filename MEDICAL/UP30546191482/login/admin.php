<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!--icono pestana-->
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../estilos/styleBotones.css">
</head>

<body>
    <button class="button" style="vertical-align:middle; margin-top:1rem; margin-left:7rem"
        onclick="window.location.href = '../index.php';">
        <span>VOLVER</span>
    </button>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
<!--wss1593 -->
                    <div class="card-header">Iniciar sesión como admin</div>
                    <div class="card-body">
                        <form action="loginAdmin.php" method="POST">
                            <div class="form-group">
                                <label for="usuario">Usuario:</label>
                                <input type="text" name="usuario" id="usuario" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="clave">Contraseña:</label>
                                <input type="password" name="clave" id="clave" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>