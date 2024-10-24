<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formJudi" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="judiNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="judiNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="judiBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="judiBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="judiParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="judiParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
