<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formRespon" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="responNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="responNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="responBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="responBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="responParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="responParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
