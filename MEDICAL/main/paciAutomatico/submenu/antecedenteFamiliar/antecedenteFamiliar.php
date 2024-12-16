<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$parentesco = $_GET['parentesco'];
$id = $_GET['id'];
?>

<form id="formAntecendesFamiliares" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="antecedentesFamiliaresNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="antecedentesFamiliaresNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="antecedentesFamiliaresBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="antecedentesFamiliaresBenef" value="<?php echo $benef; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="antecedentesFamiliaresParentesco" class="form-label">Parentesco</label>
        <input type="text" class="form-control" id="antecedentesFamiliaresParentesco" value="<?php echo $parentesco; ?>" readonly>
    </div>
</form>
