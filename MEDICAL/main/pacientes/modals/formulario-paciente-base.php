<div class="row">
    <div class="col-md-4 mb-3">
        <label for="<?= $prefijoActual ?>NombreCarga" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="<?= $prefijoActual ?>NombreCarga" name="nombre" readonly value="<?= htmlspecialchars($nombre) ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="<?= $prefijoActual ?>Benef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="<?= $prefijoActual ?>Benef" name="benef" readonly value="<?= htmlspecialchars($benef) ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="<?= $prefijoActual ?>Parentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="<?= $prefijoActual ?>Parentesco" name="parentesco" readonly value="<?= htmlspecialchars($parentesco) ?>">
    </div>
</div>
