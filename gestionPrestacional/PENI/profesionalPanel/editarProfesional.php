<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <style>
        /* Personalizar estilos adicionales aquí si es necesario */
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <?php
        require_once '../controlador/control_prof.php';
        if (isset($_GET['editar'])) {
            $id = $_GET['editar'];
            $profesional = obtenerProfesionalPorID($id);
            if ($profesional) {
                ?>
                <h1 class="text-3xl font-bold mb-4">Editar Profesional</h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $profesional['cod_prof']; ?>">
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required value="<?php echo $profesional['nombre']; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required
                            value="<?php echo $profesional['apellido']; ?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad:</label>
                        <select id="especialidad" name="especialidad" required
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            <option value="psiquiatria" <?php if ($profesional['especialidad'] === 'psiquiatria')
                                echo ' selected'; ?>>Psiquiatría</option>
                            <option value="psicologia" <?php if ($profesional['especialidad'] === 'psicologia')
                                echo ' selected'; ?>>Psicología</option>
                        </select>
                    </div>

                    <button type="submit" name="actualizar"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                </form>
                <?php
            } else {
                echo "<p>Profesional no encontrado</p>";
            }
        }
        ?>
    </div>
</body>

</html>