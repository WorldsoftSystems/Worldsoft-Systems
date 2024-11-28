<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formMedi" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="mediNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="mediNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="mediBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="mediBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="mediParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="mediParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
