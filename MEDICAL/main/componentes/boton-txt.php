<?php
// Requiere: $archivo, $label, $color (opcional)
$color = $color ?? 'primary';
?>
<button class="btn btn-<?= $color ?> mb-2" id="<?= htmlspecialchars($archivo) ?>">
  <?= htmlspecialchars($label) ?>
</button>
