<?php
require_once '../controlador/control_tipo_prac.php';

// Verificar si se recibió un ID válido en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los detalles del tipo de práctica por su ID
    $tipoPractica = obtenerTipoPracticaPorID($id);

    // Verificar si se encontró el tipo de práctica
    if ($tipoPractica) {
        // Verificar si se envió el formulario de actualización
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
            $descripcion = $_POST['descripcion'];
            $cod_practica = $_POST['cod_practica'];

            // Actualizar el tipo de práctica en la base de datos
            if (actualizarTipoPractica($id, $descripcion, $cod_practica)) {
                $_SESSION['alert_message'] = "Tipo de práctica actualizado correctamente";
                header("Location: tipoPracticaPanel.php");
                exit();
            } else {
                $_SESSION['alert_message'] = "Error al actualizar el tipo de práctica";
            }
        }
    } else {
        $_SESSION['alert_message'] = "Tipo de práctica no encontrado";
        header("Location: tipoPracticaPanel.php");
        exit();
    }
} else {
    $_SESSION['alert_message'] = "ID de tipo de práctica no proporcionado";
    header("Location: tipoPracticaPanel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tipo de Práctica</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <style>
        /* Personalizar estilos adicionales aquí si es necesario */
    </style>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold mb-4">Editar Tipo de Práctica</h1>

        <!-- Formulario para editar tipo de práctica -->
        <form method="post" class="mb-4">
            <div class="mb-4">
                <label for="cod_practica" class="block text-sm font-medium text-gray-700">Código de Práctica:</label>
                <input type="number" id="cod_practica" name="cod_practica" required value="<?php echo $tipoPractica['cod_practica']; ?>"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required value="<?php echo $tipoPractica['descript']; ?>"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" name="actualizar"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Actualizar
            </button>
        </form>

    </div>

</body>

</html>
