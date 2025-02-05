<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$id = $_GET['id'];
?>

<form id="formAdmiAmb" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="admiAmbNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="admiAmbNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="admiAmbBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="admiAmbBenef" value="<?php echo $benef; ?>" readonly>
    </div>
</form>
