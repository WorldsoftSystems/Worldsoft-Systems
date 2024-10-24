<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Ingresos y Egresos</title>
    <!--icono pestana-->
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <!-- Custom CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../estilos/styleGeneral.css">
    <link rel="stylesheet" href="../estilos/styleBotones.css">

    <!-- REPORTES -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Incluir jQuery desde el CDN de Google o cualquier otro CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="./js/script_caja.js"></script>
</head>

<body>

    <!-- Just an image -->
    <nav class="navbar bg-body-tertiary">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand" href="#">
                <img src="../img/logoBlanco.png" height="120" alt="Medical Logo" loading="lazy" />
            </a>
        </div>
    </nav>

    <button class="button" style="vertical-align:middle; margin-left:7rem"
        onclick="window.location.href = '../inicio/home.php';">
        <span>VOLVER</span>
    </button>

    <div class="container mt-4">
        <h1 class="text-center">Registro de Ingresos y Egresos</h1>

        <div class="d-flex justify-content-between my-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalMovimiento">Agregar</button>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fechaModal">Generar
                    Reporte</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Detalle</th>
                    <th>Ingreso</th>
                    <th>Egreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaMovimientos">
                <!-- Aquí se cargarán los movimientos desde la base de datos -->
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar/editar movimientos -->
    <div class="modal fade" id="modalMovimiento" tabindex="-1" aria-labelledby="modalMovimientoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMovimientoLabel">Agregar Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formMovimiento">
                        <input type="hidden" id="movimientoId">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="detalle">Detalle:*</label>
                            <select class="form-control" id="detalle" name="detalle" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ingreso" class="form-label">Ingreso</label>
                            <input type="number" step="0.01" class="form-control" id="ingreso">
                        </div>
                        <div class="mb-3">
                            <label for="egreso" class="form-label">Egreso</label>
                            <input type="number" step="0.01" class="form-control" id="egreso">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para seleccionar el rango de fechas -->
    <div class="modal fade" id="fechaModal" tabindex="-1" aria-labelledby="fechaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fechaModalLabel">Seleccione el rango de fechas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fechaDesde" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="fechaDesde">
                    </div>
                    <div class="mb-3">
                        <label for="fechaHasta" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="fechaHasta">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="filtrarYExportarPDF()">Generar PDF</button>
                    <button type="button" class="btn btn-success" onclick="">Generar Excel</button>
                </div>
            </div>
        </div>
    </div>



    <br>
    <!-- Pie de página -->
    <footer class="bg-dark text-white text-center py-4" style="margin-top:-3rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 footer-logo-text">
                    <img src="../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 50px;">
                    <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>