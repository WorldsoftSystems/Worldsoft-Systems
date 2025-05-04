<?php
function obtenerTituloSistema(PDO $pdo = null)
{
    $defaultTitle = 'Iniciar sesión';

    try {
        if (!$pdo)
            return $defaultTitle;

        $stmt = $pdo->prepare("SELECT inst FROM parametro_sistema LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? htmlspecialchars($result['inst']) : $defaultTitle;
    } catch (Exception $e) {
        error_log("Error al obtener el título: " . $e->getMessage());
        return $defaultTitle;
    }
}

