<?php
require_once __DIR__ . '/../crear-modal.php';
crearModalCompleto([
    'idListado' => 'pracModal',
    'tituloListado' => 'Prácticas',
    'idLista' => 'listaPrac',
    'idPaginacion' => 'pagination',
    'idBotonNuevo' => 'nuevaPrac',
    'idFormulario' => 'agregarPracModal',
    'tituloFormulario' => 'Agregar Práctica',
    'formId' => 'formAgregarPrac',
    'prefijo' => 'prac',
    'archivoExtra' => __DIR__ . '/formulario-practica-extra.php',  // 🔥 IMPORTANTE
    'idAvisoPaciente' => 'avisoPaciente',
    'idBotonGuardar' => 'guardarPrac'
]);

?>
