<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../index.php"); // Redirigir a la página de inicio de sesión si el usuario no ha iniciado sesión
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/icono.ico" type="image/x-icon">
    <title>Generador de QR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            position: relative;
        }

        #logo img {
            position: absolute;
            top: 0px;
            left: 5px;
            width: 5rem;
        }

        #logoOME img{
            width: 100px; /* Ajusta el tamaño del logo según sea necesario */
            margin-bottom: 20px;
        }

        #container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin: 2rem auto;
            max-width: 400px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        #qrCode {
            margin-top: 20px;
        }

        #textoAdicional {
            margin-top: 20px;
            color: #999;
        }

        /* Ajusta el tamaño del código QR */
        #qrCode img {
            width: 100px;
            height: auto;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
</head>
<body>
    <div id="logo">
        <img src="../img/icono.ico" alt="Logo">
    </div>
    <div id="container">
        <div id="logoOME">
            <img src="../img/logo.png" alt="Logo">
        </div>
        <h1>QR Fácil - Validador de OME</h1>
        <div id="form">
            <label for="beneficio">Número de beneficio:</label>
            <input type="text" id="beneficio" maxlength="12">
            <label for="parentesco">Parentesco (2 dígitos):</label>
            <input type="text" id="parentesco" maxlength="2">
            <button onclick="generarQR()">Generar QR</button>
        </div>
        <div id="qrCode"></div>
        <div id="textoAdicional">Todos los derechos reservados Worldsoft Systems</div>
    </div>

    <script>
        function generarQR() {
            var beneficio = document.getElementById('beneficio').value;
            var parentesco = document.getElementById('parentesco').value;

            if (!beneficio || !parentesco) {
                alert("Ambos campos no pueden estar vacíos.");
                return;
            }

            if (parentesco.length !== 2 || isNaN(parentesco)) {
                alert("Por favor ingrese solo 2 dígitos numéricos para el parentesco.");
                return;
            }

            var data = beneficio + "-" + parentesco;
            var qr = qrcode(0, 'L');
            qr.addData(data);
            qr.make();

            document.getElementById('qrCode').innerHTML = qr.createImgTag();

             // Enviar solicitud AJAX para actualizar el conteo de QR
             var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_qr_count.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log("QR count updated successfully");
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
