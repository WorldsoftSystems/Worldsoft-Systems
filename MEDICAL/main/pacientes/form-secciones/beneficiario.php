<div class="section-box">
    <h5 class="section-title">Datos del Beneficiario</h5>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="obra_social">Obra Social:<sup>*</sup></label>
            <select class="form-control input-rounded" id="obra_social" name="obra_social" required>
                <option value="">Seleccionar...</option>
            </select>
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="boca_atencion">Boca de atención:<sup>*</sup></label>
            <select class="form-control input-rounded" id="boca_atencion" name="boca_atencion" required>
                <option value="">Seleccionar...</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="benef">Beneficiario (12):<sup>*</sup></label>
            <input type="number" class="form-control input-rounded" id="benef" name="benef" required>
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="parentesco">Parentesco (2):<sup>*</sup></label>
            <div class="input-group">
                <input type="text" class="form-control input-rounded" id="parentesco" name="parentesco" required
                    maxlength="2">
                <div class="input-group-append" id="btnBuscar">
                    <span class="button">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="ugl_paciente">UGL:<sup>*</sup></label>
            <input type="text" class="form-control input-rounded" id="ugl_paciente" name="ugl_paciente" required
                readonly>
        </div>

        <div class="col-md-6 form-group mb-3">
            <div class="row">
                <?php if ($cliente === 'UP3069149922304'): ?>
                    <div class="col-sm-6 form-group mb-2">
                        <label for="token">Token:</label>
                        <input type="text" class="form-control form-control-sm input-rounded" id="token" name="token">
                    </div>
                <?php endif; ?>
                <div class="col-sm-6 form-group mb-2">
                    <label for="nro_de_tramite">Nro de Tramite:</label>
                    <input type="text" class="form-control form-control-sm input-rounded" id="nro_de_tramite"
                        name="nro_de_tramite">
                </div>
                <div class="col-sm-6 form-group mb-2">
                    <label for="token">Token:</label>
                    <input type="text" class="form-control form-control-sm input-rounded" id="token"
                        name="token">
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="nro_hist_amb">Nro. Historia Ambulatoria:</label>
            <input type="text" class="form-control input-rounded" id="nro_hist_amb" name="nro_hist_amb">
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="nro_hist_int">Nro. Historia Internacion:</label>
            <input type="text" class="form-control input-rounded" id="nro_hist_int" name="nro_hist_int">
        </div>
    </div>
</div>