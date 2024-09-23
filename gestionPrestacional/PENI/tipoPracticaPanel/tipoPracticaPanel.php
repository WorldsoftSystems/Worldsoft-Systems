<?php
require_once '../controlador/control_tipo_prac.php';

// Procesar el formulario de agregar nuevo tipo de práctica
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $descripcion = $_POST['descripcion'];
    $cod_practica = $_POST['cod_practica'];

    if (agregarTipoPractica($descripcion, $cod_practica)) {
        $_SESSION['success_message'] = "Tipo de práctica agregado correctamente";
    } else {
        $_SESSION['error_message'] = "Error al agregar el tipo de práctica";
    }

    // Redirigir después de procesar el formulario
    header("Location: tipoPracticaPanel.php");
    exit(); // Asegurar que el script se detenga después de la redirección
}

// Procesar la solicitud de eliminar un tipo de práctica
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    // Si se ha confirmado la eliminación, proceder con la eliminación
    if (isset($_GET['confirmar'])) {
        if (eliminarTipoPractica($id)) {
            $_SESSION['success_message'] = "Tipo de práctica eliminado correctamente";
        } else {
            $_SESSION['error_message'] = "Error al eliminar el tipo de práctica";
        }

        // Redirigir después de procesar la eliminación
        header("Location: tipoPracticaPanel.php");
        exit(); // Asegurar que el script se detenga después de la redirección
    } else {
        // Si no se ha confirmado, mostrar la ventana de confirmación
        header("Location: tipoPracticaPanel.php?confirmar_eliminar=$id");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Práctica</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <style>
        .image-top-right {
            position: absolute;
            top: 10px;
            /* Ajustar según la distancia desde la parte superior */
            right: 10px;
            /* Ajustar según la distancia desde el lado derecho */
            width: 9rem;
            /* Ancho deseado de la imagen */
            height: auto;
            /* Altura ajustada automáticamente según el ancho */
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Botón para volver al panel -->
    <a href="../panelMain/panelMain.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver</a>

    <div class="container mx-auto px-4 py-8 relative"> <!-- Añadir relative para el posicionamiento absoluto -->
        <img src="../img/digital.practias.jpg" alt="Imagen" class="image-top-right hidden sm:block">

        <h1 class="text-3xl font-bold mb-4">Tipos de Práctica</h1>

        <!-- Formulario para agregar nuevo tipo de práctica -->
        <h2 class="text-2xl font-bold mb-2">Agregar Nuevo Tipo de Práctica</h2>
        <form method="post" class="mb-4">

            <div class="mb-4">
                <label for="cod_practica" class="block text-sm font-medium text-gray-700">Código de Práctica:</label>
                <input type="number" id="cod_practica" name="cod_practica" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" name="agregar"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Agregar
            </button>
        </form>

        <!-- Lista de tipos de práctica existentes -->
        <hr class="border-t border-gray-400 my-8">
        <h2 class="text-2xl font-bold mb-2">Tipos de Práctica Existentes</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Código de Práctica</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../controlador/control_tipo_prac.php';

                    $tiposPractica = obtenerTiposPractica();
                    foreach ($tiposPractica as $tipo) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2'>" . $tipo["descript"] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $tipo["cod_practica"] . "</td>";
                        echo "<td class='border px-4 py-2'>
                                <a href='tipoPracticaPanel.php?eliminar=" . $tipo["id"] . "' class='text-red-600 hover:text-red-800'>Eliminar</a> | 
                                <a href='editarTipoPractica.php?id=" . $tipo["id"] . "' class='text-blue-600 hover:text-blue-800'>Editar</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
