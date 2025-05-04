<?php
// Requiere 3 variables: $href, $img, $title
?>

<div class="col d-flex justify-content-center">
    <a href="<?= htmlspecialchars($href) ?>">
        <div class="card h-100">
            <div class="first-content">
                <img src="<?= htmlspecialchars($img) ?>" class="img-fluid" alt="<?= htmlspecialchars($title) ?>">
            </div>
            <div class="third-content">
                <h3 class="mt-3"><?= htmlspecialchars($title) ?></h3>
            </div>
        </div>
    </a>
</div>