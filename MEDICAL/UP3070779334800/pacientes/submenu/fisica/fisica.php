<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formFisica" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="fisicaNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="fisicaNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="fisicaBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="fisicaBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="fisicaParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="fisicaParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>