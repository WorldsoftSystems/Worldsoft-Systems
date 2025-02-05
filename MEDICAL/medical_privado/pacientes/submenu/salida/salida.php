<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$id = $_GET['id'];
?>

<form id="formSali" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="saliNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="saliNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="saliBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="saliBenef" value="<?php echo $benef; ?>" readonly>
    </div>
</form>
