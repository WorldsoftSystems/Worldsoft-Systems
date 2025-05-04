<?php require_once '../componentes/auth.php'; ?>
<?php require_once '../componentes/init.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <!--icono pestana-->
  <link rel="icon" href="../img/logo.png" type="image/x-icon">
  <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
  <?php include '../componentes/head-resources.php'; ?>
  <link rel="stylesheet" href="../estilos/chatBot.css">
  <!-- scripts -->
  <script defer src="./asistente/index.js"></script>
</head>

<body>
  <!-- NAVBAR -->
  <?php
  include '../componentes/navbar.php';
  ?>
  <!-- FIN NAVBAR -->

  <button class="button" style="vertical-align:middle; margin-left:7rem" onclick="confirmLogout(event)">
    <span>Cerrar sesión</span>
  </button>


  <!-- Cards Container -->
  <div class="container my-5">

    <!-- Bienvenida arriba de las cards -->
    <div class="row mb-4">
      <div class="col text-center">
        <h1 class="display-5 fw-semibold">
          Bienvenido, <?php echo htmlspecialchars($title); ?>
        </h1>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center">
      <?php
      $cards = [
        ['href' => '../pacientes/paciente.php', 'img' => '../img/home/pacientes.png', 'title' => 'Pacientes'],
        ['href' => '../turnos/calendario.php', 'img' => '../img/home/agenda.png', 'title' => 'Agenda de Turnos'],
        ['href' => '../estadisticas/estadisticas.php', 'img' => '../img/home/estadisticas.png', 'title' => 'Estadísticas'],
        ['href' => '../caja/caja.php', 'img' => '../img/home/caja.png', 'title' => 'Caja'],
        ['href' => '../gastos/gastos.php', 'img' => '../img/home/gastos.png', 'title' => 'Gastos'],
        ['href' => '../seccionAjustes/ajustes.php', 'img' => '../img/home/configuracion.png', 'title' => 'Ajustes'],
      ];

      foreach ($cards as $card) {
        $href = $card['href'];
        $img = $card['img'];
        $title = $card['title'];
        include '../componentes/card.php';
      }
      ?>
    </div>

  </div>
  <!-- FIN Cards Container -->

  <!-- Chatbox -->
  <?php include '../componentes/chatbot.php'; ?>
  <!-- FIN Chatbox -->

  <!-- Pie de página -->
  <?php include "../componentes/footer.php"; ?>
  <!-- Fin Pie de página -->

  <script>
    // Confirmación de cierre de sesión
    function confirmLogout(e) {
      e.preventDefault();
      if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
        window.location.href = '../inicio/logout.php';
      }
    }

    // Chatbot: lógica principal
    document.addEventListener('DOMContentLoaded', () => {
      const chatButton = document.getElementById('chatButton');
      const chatContainer = document.getElementById('chatContainer');
      const minimizeButton = document.getElementById('minimizeButton');
      const questionList = document.getElementById('questionList');
      const messages = document.getElementById('messages');

      // Mostrar el chat
      chatButton.addEventListener('click', () => {
        chatContainer.style.display = 'block';
        chatButton.style.display = 'none';
        loadQuestions();
      });

      // Ocultar/minimizar el chat
      minimizeButton.addEventListener('click', () => {
        chatContainer.style.display = 'none';
        chatButton.style.display = 'flex';
      });

      // Cerrar si se hace clic fuera del chat
      document.addEventListener('click', (e) => {
        if (!chatContainer.contains(e.target) && !chatButton.contains(e.target)) {
          chatContainer.style.display = 'none';
          chatButton.style.display = 'flex';
        }
      });

      // Cargar preguntas desde el servidor
      function loadQuestions() {
        $.getJSON('./gets/get_pyr.php', (questions) => {
          renderQuestions(questions);
        }).fail((xhr, status, error) => {
          console.error("Error cargando preguntas:", error);
        });
      }

      // Renderizar botones con preguntas
      function renderQuestions(questions) {
        questionList.innerHTML = '';
        questions.forEach((q, i) => {
          const btn = document.createElement('button');
          btn.className = 'btn btn-primary mb-2';
          btn.textContent = q.text;
          btn.onclick = () => showAnswer(q);
          questionList.appendChild(btn);
        });
      }

      // Mostrar pregunta y respuesta
      function showAnswer(q) {
        messages.innerHTML += `<div class="message user">${q.text}</div>`;
        messages.innerHTML += `<div class="message bot">${q.answer}</div>`;
        messages.scrollTop = messages.scrollHeight;
      }
    });

  </script>
</body>

</html>