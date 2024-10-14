<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFESIONALES</title>
    <!-- Icono de pestaña -->
    <link rel="icon" href="../../img/logo.png" type="image/x-icon">

    <link rel="stylesheet" href="../../estilos/styleBotones.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100vw;
            height: 100vh;
            background-image: url("../../img/login_back.png");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 400;
            letter-spacing: 0.02em;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden; /* Evitar desplazamiento horizontal */
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .blurred-box {
            position: relative;
            width: 300px; /* Ancho reducido */
            padding: 20px;
            color:#ffffff; /* Fondo blanco más transparente */
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .blurred-box:after {
            content: '';
            width: 100%;
            height: 100%;
            background: inherit;
            position: absolute;
            left: 0;
            top: 0;
            box-shadow: inset 0 0 0 150px rgba(255, 255, 255, 0.1);
            filter: blur(10px);
            z-index: -1;
        }

        .card {
            background-color: transparent;
            border: none;
        }

        .card-header {
            background-color: transparent;
            border-bottom: none;
            color: #fff;
            text-align: center;
            font-size: 1.25rem; /* Tamaño de fuente reducido */
        }

        .form-control {
            border-radius: 2px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            border-radius: 2px;
        }

        .cutom-user:hover{
            text-decoration: none !important;
        }

        footer.bg-dark {
            background-color: transparent !important; /* Cambiar el fondo del footer a blanco */
            color: #000; /* Texto en color negro */
            padding: 1rem 0; /* Reducir el padding */
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .footer-logo-text img {
            margin-right: 0.5rem;
        }

        .button{
            background-color: #2e8ec9 !important;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="d-flex flex-column align-items-center">
            <button class="button" onclick="window.location.href = '../../index.php';">
                <span>VOLVER</span>
            </button>

            <div class="container">
                <div class="blurred-box">
                    <div class="text-center my-4">
                        <img src="../../img/logoBlanco.png" alt="Logo MEDICAL" class="img-fluid"
                            style="max-width: 15rem;">
                    </div>
                    <div class="card">
                        <div class="card-header">Iniciar sesión como Profesional</div>
                        <div class="card-body">
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET['error'] == "credenciales_incorrectas" || $_GET['error'] == "usuario_no_encontrado") {
                                    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
                                }
                            }
                            ?>
                            <form action="./login_prof.php" method="POST">
                                <div class="form-group">
                                    <label for="usuario">Usuario:</label>
                                    <input type="text" name="usuario" id="usuario" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="clave">Contraseña:</label>
                                    <input type="password" name="clave" id="clave" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-custom">Iniciar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 footer-logo-text">
                    <img src="../../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 40px;">
                    <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>