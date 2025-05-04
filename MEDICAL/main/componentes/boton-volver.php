<?php
// Variables opcionales con valores por defecto
$href = $href ?? '../index.php';
$texto = $texto ?? '';
$type = $type ?? 'button';
$extraOnClick = $extraOnClick ?? null;
$icon = $icon ?? null; // 🔥 Nuevo: icono opcional
?>

<button class="button d-flex align-items-center gap-2" 
    type="<?= htmlspecialchars($type) ?>" 
    style="vertical-align:middle; margin-top:1rem; margin-left:7rem"
    onclick="<?= $extraOnClick ? $extraOnClick : "window.location.href = '$href';" ?>">
    
    <?php if ($icon): ?>
        <i class="<?= htmlspecialchars($icon) ?>"></i>
    <?php endif; ?>
    
    <span><?= htmlspecialchars($texto) ?></span>
</button>
