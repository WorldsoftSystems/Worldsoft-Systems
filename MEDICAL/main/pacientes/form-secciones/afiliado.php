<div class="section-box">
    <h5 class="section-title">Detalles del Afiliado</h5>

    <div class="row">
        <div class="col-md-4 form-group mb-3">
            <label for="tipo_afiliado">Tipo de prestación:<sup>*</sup></label>
            <select class="form-control input-rounded" id="tipo_afiliado" name="tipo_afiliado" required>
                <option value="">Seleccionar...</option>
            </select>
        </div>
        <div class="col-md-4 form-group mb-3">
            <label for="tipo_doc">Tipo de Documento:<sup>*</sup></label>
            <select class="form-control input-rounded" id="tipo_doc" name="tipo_doc" required>
                <option value="">Seleccione un tipo de documento</option>
                <option value="DNI">DNI</option>
                <option value="LC">LC</option>
                <option value="LE">LE</option>
                <option value="CI">CI</option>
                <option value="PAS">Pasaporte</option>
                <option value="OTRO">Otro</option>
            </select>
        </div>
        <div class="col-md-4 form-group mb-3">
            <label for="nro_doc">Número de Documento:<sup>*</sup></label>
            <input type="number" class="form-control input-rounded" id="nro_doc" name="nro_doc" required>
        </div>
    </div>
</div>
