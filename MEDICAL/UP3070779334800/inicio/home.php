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
  <!-- Incluir Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../estilos/styleGeneral.css">
  <link rel="stylesheet" href="../estilos/styleBotones.css">

  <!-- scripts -->
  <!-- Bootstrap JS (opcional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    .chat-container {
      position: fixed;
      bottom: 0.5rem;
      right: 0;
      width: 300px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      display: none;
      /* Inicialmente oculto */
      z-index: 9999;
    }

    .chat-header {
      background-color: var(--primary-color) !important;
      color: #fff;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chat-header h5 {
      margin: 0;
    }

    .chat-header button {
      background: none;
      border: none;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
    }

    .messages {
      height: 300px;
      overflow-y: auto;
      padding: 10px;
    }

    .question-list {
      max-height: 200px;
      overflow-y: auto;
      padding: 10px;
    }

    .message {
      margin-bottom: 10px;
    }

    .message.user {
      color: white;
      background-color: var(--primary-color) !important;
      border-radius: 0.2rem;
      padding: 1px;
    }

    .message.bot {
      color: #333;
    }

    .question-list button {
      background-color: var(--primary-color) !important;
      color: #fff;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 5px;
      text-align: left;
    }

    .question-list button:hover {
      background-color: var(--primary-color) !important;
    }

    .chat-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-image: url('../img/bot.png');
      background-size: cover;
      background-position: center;
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 9999;
    }

    /* Estilo del tooltip */
    .fixed-tooltip {
      position: fixed;
      bottom: 90px;
      /* Ajustar según sea necesario */
      right: 30px;
      /* Ajustar según sea necesario */
      background-color: #333;
      color: #fff;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 800;
      /* Asegúrate de que esté por encima del botón */
    }
  </style>
  </style>
</head>

<body>
  <!-- Just an image -->
  <nav class="navbar bg-body-tertiary">
    <div class="container navbar-custom d-flex justify-content-center">
      <a class="navbar-brand" href="#">
        <img src="../img/logoBlanco.png" height="160rem" alt="Medical Logo" loading="lazy" />
      </a>
    </div>
  </nav>



  <button class="button" style="vertical-align:middle; margin-left:7rem" onclick="confirmLogout(event)">
    <span>Cerrar sesión</span>
  </button>


  <!-- Cards Container -->
  <div class="container my-5">
    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center" style="margin-top:-3rem;">
      <!-- Reducir el valor de g a 2 para reducir la distancia entre las tarjetas -->
      <!--PRIMERA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../pacientes/paciente.php">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/pacientes.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Pacientes</h3>
            </div>
          </div>
        </a>
      </div>
      <!--CARD -->

      <!--SEGUNDA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../turnos/calendario.php">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/agenda.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Agendas</h3>
            </div>
          </div>
        </a>
      </div>
      <!--CARD -->

      <!-- TERCERA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../estadisticas/estadisticas.php">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/estadisticas.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Estaditicas</h3>
            </div>
          </div>
        </a>
      </div>
      <!--CARD -->

      <!-- CUARTA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../caja/caja.php">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/caja.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Caja</h3>
            </div>
          </div>
        </a>
      </div>
      <!--CARD -->

      <!-- QUINTA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../gastos/gastos.php">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/gastos.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Gastos</h3>
            </div>
          </div>
        </a>
      </div>
      <!--CARD -->

      <!-- SEXTA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="../seccionAjustes/ajustes.php" class="card-link">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/home/configuracion.png" class="img-fluid" alt="">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Ajustes</h3>
            </div>
          </div>
        </a>
      </div>

      <!--CARD -->
    </div>
  </div>


  <!-- Chatbox -->
  <div class="fixed-tooltip">Chatea con Zoe!</div>
  <div class="chat-button" id="chatButton"></div>
  <div class="chat-container" id="chatContainer">
    <div class="chat-header">
      <h5>Chatbot</h5>
      <button id="minimizeButton">✖</button>
    </div>
    <div class="messages" id="messages"></div>
    <div class="question-list" id="questionList">
      <!-- Las preguntas se generarán aquí con JavaScript -->
    </div>
  </div>





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





  <script>
    function confirmLogout(event) {
      // Evitar la acción predeterminada del botón
      event.preventDefault();

      // Mostrar una ventana de confirmación
      var userConfirmed = confirm("¿Estás seguro de que deseas cerrar sesión?");

      // Si el usuario confirma, redirigir al script de cierre de sesión
      if (userConfirmed) {
        window.location.href = '../inicio/logout.php';
      }
      // Si el usuario cancela, no hacer nada
    }

    // Definición de respuestas del chatbot
    document.addEventListener('DOMContentLoaded', () => {

      const questionList = document.getElementById('questionList');
      const messages = document.getElementById('messages');
      const chatButton = document.getElementById('chatButton');
      const chatContainer = document.getElementById('chatContainer');
      const minimizeButton = document.getElementById('minimizeButton');

      // Función para cargar preguntas y respuestas desde la base de datos
      function loadQuestions() {
        $.ajax({
          url: './gets/get_pyr.php', // Archivo PHP que obtiene las preguntas y respuestas desde la base de datos
          method: 'GET',
          dataType: 'json',
          success: function (questions) {
            displayQuestions(questions); // Mostrar las preguntas en la interfaz
          },
          error: function (xhr, status, error) {
            console.error("Error al cargar las preguntas: ", error);
          }
        });
      }

      // Función para mostrar las preguntas como botones
      function displayQuestions(questions) {
        questionList.innerHTML = ''; // Limpiar lista de preguntas
        questions.forEach((question, index) => {
          const button = document.createElement('button');
          button.className = 'btn btn-primary';
          button.textContent = question.text;
          button.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevenir el cierre del chat
            displayAnswer(questions, index); // Mostrar la respuesta cuando se hace clic
          });
          questionList.appendChild(button);
        });
      }

      // Función para mostrar la respuesta al hacer clic en una pregunta
      function displayAnswer(questions, index) {
        const question = questions[index];
        messages.innerHTML += `<div class="message user">${question.text}</div>`;
        messages.innerHTML += `<div class="message bot">${question.answer}</div>`;
        messages.scrollTop = messages.scrollHeight;  // Desplazar hacia abajo
      }

      // Mostrar el chat cuando se hace clic en el botón de abrir chat
      chatButton.addEventListener('click', () => {
        chatContainer.style.display = 'block';
        chatButton.style.display = 'none';
        loadQuestions(); // Cargar las preguntas cuando se abre el chat
      });

      // Minimizar el chat
      minimizeButton.addEventListener('click', () => {
        chatContainer.style.display = 'none';
        chatButton.style.display = 'flex';
      });

      // Cerrar el chat si se hace clic fuera de él
      document.addEventListener('click', (event) => {
        if (!chatContainer.contains(event.target) && !chatButton.contains(event.target)) {
          chatContainer.style.display = 'none';
          chatButton.style.display = 'flex';
        }
      });

    });

  </script>
</body>

</html>