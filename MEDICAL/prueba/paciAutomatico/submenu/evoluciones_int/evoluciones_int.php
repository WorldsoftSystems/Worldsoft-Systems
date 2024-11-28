<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formEvoInt" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="evoIntNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="evoIntNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="evoIntBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="evoIntBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="evoIntParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="evoIntParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
