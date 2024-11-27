<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formModa" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="modaNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="modaNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="modaBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="modaBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="modaParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="modaParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
