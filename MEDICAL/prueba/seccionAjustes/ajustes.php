<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
  // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
  header("Location: ../index.php");
}

// Lógica para cerrar sesión
if (isset($_GET['cerrar_sesion'])) {
  // Destruye todas las variables de sesión
  session_destroy();
  // Redirige al usuario a la página de inicio o a donde desees
  header("Location: ../index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <!--icono pestana-->
  <link rel="icon" href="../img/logo.png" type="image/x-icon">
  <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Font Awesome para los iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../estilos/styleGeneral.css">
  <link rel="stylesheet" href="../estilos/styleBotones.css">
  
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

  <div class="container my-5">
    <div class="row mb-4 justify-content-center">
      <div class="col-md-6">
        <div class="input-group">
          <input type="text" class="form-control" id="searchInput" placeholder="Buscar por título...">
        </div>
      </div>
    </div>


    <!-- Cards Container -->
    <div class="container my-5">
      <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center">
        <!-- Reducir el valor de g a 2 para reducir la distancia entre las tarjetas -->
        <!--PRIMERA CARD -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/crearProf/crearProf.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/profesional.png" alt="Icono Crear Profesional">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Profesional</h3>
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!--PRIMERA CARD -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/disponibilidad/disponibilidad.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/agendaProf.png" alt="Icono agenda Profesional">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Agenda profesional</h3>
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!--SEGUNDA CARD -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/crearEspecialidad/crearEspecialidad.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/especialidad.png" alt="Icono Crear Especialidad">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Especialidad</h3>
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- TERCERA CARD -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/obraSocial/obraSocial.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/obraSocial.png" class="img-fluid" alt="Obra Social">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Obra Social</h3>
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/medicacion/medicacion.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/medicamentos.png" class="img-fluid" alt="medicacion">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Medicamentos</h3>
              </div>
            </div>
          </a>

        </div>

        <!-- Cuarta Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/origen/origen.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/origen.png" class="img-fluid" alt="Origen">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Origen de ingreso</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Quinta Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/ingreso/ingreso.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/ingreso.png" class="img-fluid" alt="ingreso">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Motivo de ingreso</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>


        <!--CARD -->

        <!-- Sexta Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/egreso/egreso.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/egreso.png" class="img-fluid" alt="egreso">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Motivo de egresos</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Septima Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/actividades/actividades.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/actividades.png" class="img-fluid" alt="Actividad">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Actividades</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Octava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/categoria/categoria.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/categorias.png" class="img-fluid" alt="categoria">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Categorias</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Novena Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/tipoAfiliado/tipoAfiliado.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/tipoAfiliado.png" class="img-fluid" alt="tipo de Afiliado">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Tipo de afiliado</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Novena Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/modalidad/modalidad.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/modalidad.png" class="img-fluid" alt="Modalidad">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Modalidades</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Decima Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/familiar/familiar.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/familiares.png" class="img-fluid" alt="familiar">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Familiares</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Onceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/diag/diag.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/diagnosticos.png" class="img-fluid" alt="diagnostico">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Diagnosticos</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Doceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/judiciales/juzgados/juzgados.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/juzgados.png" class="img-fluid" alt="judiciales">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Juzgados</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Treceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/judiciales/secretarias/secretarias.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/secretarias.png" class="img-fluid" alt="judiciales">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Secretarias</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Catorceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/judiciales/curadurias/curadurias.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/curaduria.png" class="img-fluid" alt="judiciales">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Curadurias</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Catorceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/judiciales/tiposJuicios/tiposJuicios.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/judiciales.png" class="img-fluid" alt="judiciales">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Tipo de juicios</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Quinceava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/habitaciones/habitaciones.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/habitaciones.png" class="img-fluid" alt="habitaciones">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Habitaciones</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Dieciseisava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/rubros/rubros.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/rubros.png" class="img-fluid" alt="Rubros">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Rubros</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Diecisieteava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/cuentas/cuentas.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/cuentas.png" class="img-fluid" alt="cuentas">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Cuentas</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Diecisieteava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/comprobantes/comprobantes.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/comprobantes.png" class="img-fluid" alt="comprobantes">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Comprobantes</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Dieciochoava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/periodo/periodo.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/periodo.png" class="img-fluid" alt="periodo">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Periodos de pagos</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->

        <!-- Dieciochoava Card -->
        <div class="col d-flex justify-content-center">
          <a href="../todosAjustes/parametros/parametros.php">
            <div class="card h-100">
              <div class="first-content">
                <img src="../img/ajustes/parametros.png" class="img-fluid" alt="periodo">
              </div>
              <div class="third-content">
                <h3 class="mt-3">Parametros del sistema</h3>
              </div>
              <div class="second-content">
              </div>
            </div>
          </a>

        </div>
        <!--CARD -->



      </div>
    </div>


    <!-- Pie de página -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12 footer-logo-text">
            <img src="../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 50px;">
            <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.card');

        searchInput.addEventListener('input', function () {
          const searchValue = searchInput.value.toLowerCase();

          cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            if (title.includes(searchValue)) {
              card.parentElement.style.display = 'block';
            } else {
              card.parentElement.style.display = 'none';
            }
          });
        });
      });
    </script>

</body>

</html>