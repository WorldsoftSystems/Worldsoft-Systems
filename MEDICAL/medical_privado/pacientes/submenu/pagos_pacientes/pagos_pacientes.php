<?php
$nombre = $_GET['nombre'];
$benef = $_GET['benef'];
$id = $_GET['id'];
?>

<form id="formPago" class="row g-3">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <div class="col-md-4">
        <label for="pagoNombre" class="form-label">Nombre y Apellido</label>
        <input type="text" class="form-control" id="pagoNombre" value="<?php echo $nombre; ?>" readonly>
    </div>
    <div class="col-md-4">
        <label for="pagoBenef" class="form-label">Beneficiario</label>
        <input type="number" class="form-control" id="pagoBenef" value="<?php echo $benef; ?>" readonly>
    </div>
</form>
