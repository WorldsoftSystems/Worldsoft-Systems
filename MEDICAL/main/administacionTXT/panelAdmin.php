<?php
// Conexión a la base de datos
require_once "../conexion.php";

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar el valor de 'inst'
$sql = "SELECT inst FROM parametro_sistema LIMIT 1";
$result = $conn->query($sql);

// Obtener el valor
$title = "Iniciar sesión"; // Valor por defecto
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['inst'];
}

// Determinar cliente desde la sesión
$cliente = isset($_SESSION['up']) ? $_SESSION['up'] : null;

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <!--icono pestana-->
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

    <?php include '../componentes/head-resources.php'; ?>

    <style>
        /* Estilo para centrar los botones */
        .center-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 20px;
        }
    </style>
</head>

<body>
    <!-- BOTÓN VOLVER -->
    <div class="container mt-3">
        <?php
        $href = '../login/admin.php';
        $texto = 'VOLVER';
        include '../componentes/boton-volver.php';
        ?>
    </div>

    <div class="text-center mb-4">
        <h1>Admin de <?= htmlspecialchars($title) ?></h1>
    </div>

    <!-- Contenedor de botones -->
    <div class="container">
        <?php if ($cliente === 'UP3054610431800'): ?>
            <?php
            $botones = [
                ['archivo' => 'txt_ugl6.php', 'label' => 'Generar TXT DE UGL 6'],
                ['archivo' => 'txt_ugl10.php', 'label' => 'Generar TXT DE UGL 10'],
                ['archivo' => 'txt_ugl37.php', 'label' => 'Generar TXT DE UGL 37'],
            ];
            foreach ($botones as $btn) {
                extract($btn);
                include '../componentes/boton-txt.php';
            }
            ?>
            <!-- FIN  UP3054610431800 -->
        <?php elseif ($cliente === 'UP3063207857500'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $int = ['txt_ugl35_INT.php', 'txt_ugl08_INT.php', 'txt_ugl06_INT.php'];
                    foreach ($int as $archivo) {
                        $label = 'Generar TXT DE ' . strtoupper(str_replace(['.php', 'txt_'], '', $archivo));
                        include '../componentes/boton-txt.php';
                    }
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $amb = ['txt_ugl35_AMB.php', 'txt_ugl08_AMB.php', 'txt_ugl06_AMB.php'];
                    foreach ($amb as $archivo) {
                        $label = 'Generar TXT DE ' . strtoupper(str_replace(['.php', 'txt_'], '', $archivo));
                        include '../componentes/boton-txt.php';
                    }
                    ?>
                </div>
            </div>
            <!-- FIN  UP3063207857500 -->

        <?php elseif ($cliente === 'UP3066408967600'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $archivo = 'txt_pq0352_int.php';
                    $label = 'Generar TXT INT';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $archivo = 'txt_pq0352_amb.php';
                    $label = 'Generar TXT AMB';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3066408967600 -->

        <?php elseif ($cliente === 'UP3060454669500'): ?>
            <div class="row">
                <div class="col">
                    <h4>UGL 06</h4>
                    <?php
                    $archivo = 'txt_pq0231_ugl_6.php';
                    $label = 'Generar TXT UGL 06';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>UGL 37</h4>
                    <?php
                    $archivo = 'txt_pq0231_ugl_37.php';
                    $label = 'Generar TXT UGL 37';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3060454669500 -->

        <?php elseif ($cliente === 'UP3069149922304'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $archivo = 'txt_pq0303_int.php';
                    $label = 'Generar TXT INT';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $archivo = 'txt_pq0303_amb.php';
                    $label = 'Generar TXT AMB';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3069149922304 -->

        <?php elseif ($cliente === 'UP3058060423000'): ?>
            <div class="row">
                <div class="col">
                    <h4>UGL 06</h4>
                    <?php
                    $archivo = 'pq0236_ugl_6.php';
                    $label = 'Generar TXT UGL_06';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>UGL 10</h4>
                    <?php
                    $archivo = 'pq0236_ugl_10.php';
                    $label = 'Generar TXT UGL_10';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3058060423000 -->

        <?php elseif ($cliente === 'UP3060909879800'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $archivo = 'txt_pq0106_int.php';
                    $label = 'Generar TXT INT';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $archivo = 'txt_pq0106_amb.php';
                    $label = 'Generar TXT AMB';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3060909879800 -->

        <?php elseif ($cliente === 'UP3065642500400'): ?>
            <div class="row">
                <div class="col">
                    <h4>UGL 29 INT</h4>
                    <?php
                    $archivo = 'pq0238_ugl_29_int.php';
                    $label = 'Generar TXT UGL_29';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>UGL 35 INT</h4>
                    <?php
                    $archivo = 'pq0238_ugl_35_int.php';
                    $label = 'Generar TXT UGL_35';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>UGL 35 AMB</h4>
                    <?php
                    $archivo = 'pq0238_ugl_35_amb.php';
                    $label = 'Generar TXT UGL_35';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>UGL 29 AMB</h4>
                    <?php
                    $archivo = 'pq0238_ugl_29_amb.php';
                    $label = 'Generar TXT UGL_29';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP30656425004 -->

        <?php elseif ($cliente === 'UP3068518703100'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $archivo = 'pq0321_int.php';
                    $label = 'Generar TXT INT';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $archivo = 'pq0321_amb.php';
                    $label = 'Generar TXT AMB';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3068518703100 -->

        <?php elseif ($cliente === 'UP3070838436001'): ?>
            <div class="row">
                <div class="col">
                    <h4>INT</h4>
                    <?php
                    $archivo = 'pq0327_int.php';
                    $label = 'Generar TXT INT';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
                <div class="col">
                    <h4>AMB</h4>
                    <?php
                    $archivo = 'pq0327_amb.php';
                    $label = 'Generar TXT AMB';
                    include '../componentes/boton-txt.php';
                    ?>
                </div>
            </div>
            <!-- FIN  UP3070838436001 -->

        <?php else: ?>
            <?php
            $archivo = 'generate_txt.php';
            $label = 'Generar TXT';
            include '../componentes/boton-txt.php';
            ?>
        <?php endif; ?>

        <!-- ✅ Botón único para todos -->
        <?php include '../componentes/boton-registrar-usuario.php'; ?>
    </div>

    <!-- Tabla de usuarios -->
    <div class="container mt-5">
        <h2>Lista de Usuarios</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenarán los usuarios -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <?php include '../componentes/modal-registro.php'; ?>


    <!-- Pie de página -->
    <?php include "../componentes/footer.php"; ?>
    <!-- Fin Pie de página -->

    <script>
        //TXT
        document.addEventListener("DOMContentLoaded", function () {

            const archivos = [
                { id: "generate_txt.php", ruta: "./gets/generate_txt.php" },

                // PQ0222
                { id: "txt_ugl6.php", ruta: "./gets/pq0222/txt_ugl6.php" },
                { id: "txt_ugl10.php", ruta: "./gets/pq0222/txt_ugl10.php" },
                { id: "txt_ugl37.php", ruta: "./gets/pq0222/txt_ugl37.php" },

                // PQ0241 INT
                { id: "txt_ugl35_INT.php", ruta: "./gets/pq0241/txt_ugl35_INT.php" },
                { id: "txt_ugl08_INT.php", ruta: "./gets/pq0241/txt_ugl08_INT.php" },
                { id: "txt_ugl06_INT.php", ruta: "./gets/pq0241/txt_ugl06_INT.php" },

                // PQ0241 AMB
                { id: "txt_ugl35_AMB.php", ruta: "./gets/pq0241/txt_ugl35_AMB.php" },
                { id: "txt_ugl08_AMB.php", ruta: "./gets/pq0241/txt_ugl08_AMB.php" },
                { id: "txt_ugl06_AMB.php", ruta: "./gets/pq0241/txt_ugl06_AMB.php" },

                // PQ0352
                { id: "txt_pq0352_int.php", ruta: "./gets/pq0352/txt_pq0352_int.php" },
                { id: "txt_pq0352_amb.php", ruta: "./gets/pq0352/txt_pq0352_amb.php" },

                // PQ0231
                { id: "txt_pq0231_ugl_6.php", ruta: "./gets/pq0231/txt_pq0231_ugl_6.php" },
                { id: "txt_pq0231_ugl_37.php", ruta: "./gets/pq0231/txt_pq0231_ugl_37.php" },

                // PQ0303
                { id: "txt_pq0303_int.php", ruta: "./gets/pq0303/txt_pq0303_int.php" },
                { id: "txt_pq0303_amb.php", ruta: "./gets/pq0303/txt_pq0303_amb.php" },

                // PQ0236
                { id: "pq0236_ugl_6.php", ruta: "./gets/pq0236/pq0236_ugl_6.php" },
                { id: "pq0236_ugl_10.php", ruta: "./gets/pq0236/pq0236_ugl_10.php" },

                // PQ0106
                { id: "txt_pq0106_int.php", ruta: "./gets/pq0106/txt_pq0106_int.php" },
                { id: "txt_pq0106_amb.php", ruta: "./gets/pq0106/txt_pq0106_amb.php" },

                //PQ0238
                { id: "pq0238_ugl_29_int.php", ruta: "./gets/pq0238/pq0238_ugl_29_int.php" },
                { id: "pq0238_ugl_35_int.php", ruta: "./gets/pq0238/pq0238_ugl_35_int.php" },
                { id: "pq0238_ugl_29_amb.php", ruta: "./gets/pq0238/pq0238_ugl_29_amb.php" },
                { id: "pq0238_ugl_35_amb.php", ruta: "./gets/pq0238/pq0238_ugl_35_amb.php" },

                //PQ0321
                { id: "pq0321_int.php", ruta: "./gets/pq0321/pq0321_int.php" },
                { id: "pq0321_amb.php", ruta: "./gets/pq0321/pq0321_amb.php" },

                //PQ0327
                { id: "pq0327_int.php", ruta: "./gets/pq0327/pq0327_int.php" },
                { id: "pq0327_amb.php", ruta: "./gets/pq0327/pq0327_amb.php" },
            ];

            archivos.forEach(({ id, ruta }) => {
                const boton = document.getElementById(id);
                if (boton) {
                    boton.addEventListener("click", () => descargarTxt(ruta));
                }
            });

            function descargarTxt(url) {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const blob = new Blob([data.content], { type: "text/plain" });
                        const enlace = document.createElement("a");
                        enlace.href = URL.createObjectURL(blob);
                        enlace.download = data.filename;
                        document.body.appendChild(enlace);
                        enlace.click();
                        document.body.removeChild(enlace);
                    })
                    .catch(error => console.error("Error al generar TXT:", error));
            }


            //lista usuarios
            fetch('./gets/get_usuarios.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector("table tbody");
                    data.forEach(usuario => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                    <td>${usuario.id}</td>
                    <td>${usuario.usuario}</td>
                `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => console.error("Error al cargar usuarios:", error));
        });


    </script>

</body>

</html>