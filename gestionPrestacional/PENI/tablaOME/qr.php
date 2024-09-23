<?php
if(isset($_POST['beneficio'])) {
    $beneficio = $_POST['beneficio'];

    $length = strlen($beneficio);

    if ($length > 2) {
        // Obtener los últimos dos dígitos
        $ultimos_digitos = substr($beneficio, -2);

        // Obtener los primeros dígitos excluyendo los últimos dos
        $primeros_digitos = substr($beneficio, 0, $length - 2);

        // Formatear el beneficio con una coma y un guion
        $beneficio_formateado = $primeros_digitos . '-' . $ultimos_digitos;
    } else {
        // Si el beneficio tiene menos de dos dígitos, mantenerlo igual
        $beneficio_formateado = $beneficio;
    }

    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Generar QR</title>
        <script src='./qrcodejs/qrcode.min.js'></script> <!-- Ajusta la ruta según la ubicación del archivo qrcode.min.js -->
    </head>
    <body>
        <h1>QR de: $beneficio_formateado</h1>
        <div id='codigoQR'></div>

        <script>
            var beneficio = '$beneficio_formateado';
            var codigoQR = new QRCode(document.getElementById('codigoQR'), {
                text: beneficio,
                width: 200,
                height: 200
            });
        </script>
    </body>
    </html>";
} else {
    echo "No se recibió el número de beneficio.";
}
?>
