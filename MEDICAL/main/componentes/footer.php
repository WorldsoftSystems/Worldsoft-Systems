<?php
// Por defecto: negro si no se define
$footerColor = $footerColor ?? 'dark'; // 'dark' o 'light'

// Determinar clase del texto <p>
$textClass = $footerColor === 'light' ? 'text-white' : 'text-dark';
?>

<footer class="bg-transparent text-center mt-5 py-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 footer-logo-text">
                <img src="/img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 40px;">
                <p class="mb-0 <?= $textClass ?>">&copy; 2025 WorldsoftSystems. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>
