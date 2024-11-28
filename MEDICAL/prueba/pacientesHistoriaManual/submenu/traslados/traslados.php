<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formTras" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="trasNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="trasNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="trasBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="trasBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="trasParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="trasParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
