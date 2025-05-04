<?php
function crearModalCompleto($config)
{
    // 🔥 Definir variables globales para los dos modales
    $nombre = $_GET['nombre'] ?? '';
    $benef = $_GET['benef'] ?? '';
    $parentesco = $_GET['parentesco'] ?? '';
    $id = $_GET['id'] ?? '';

    // -------------------------
    // Variables necesarias
    $idModalListado = $config['idListado'];
    $tituloListado = $config['tituloListado'];
    $idLista = $config['idLista'];
    $idPaginacion = $config['idPaginacion'];
    $idBotonNuevo = $config['idBotonNuevo'];
    $idAvisoPaciente = $config['idAvisoPaciente'] ?? null;

    $idModalFormulario = $config['idFormulario'];
    $tituloFormulario = $config['tituloFormulario'];
    $formId = $config['formId'];
    $prefijo = $config['prefijo'];
    $archivoExtra = $config['archivoExtra'];
    // -------------------------
    ?>

    <!-- Modal de Listado -->
    <div class="modal fade" id="<?= $idModalListado ?>" tabindex="-1" aria-labelledby="<?= $idModalListado ?>Label">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?= $idModalListado ?>Label"><?= htmlspecialchars($tituloListado) ?></h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                </div>

                <?php if ($idAvisoPaciente): ?>
                    <h5 id="<?= $idAvisoPaciente ?>" class="text-danger text-center d-none">
                        Paciente sin afiliación en PAMI
                    </h5>
                <?php endif; ?>

                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Nombre y Apellido</label>
                            <input type="text" id="pracListadoNombreCarga" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Beneficiario</label>
                            <input type="text" id="pracListadoBenef" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Parentesco</label>
                            <input type="text" id="pracListadoParentesco" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div id="<?= $idLista ?>" class="scrollable-content">
                                <!-- Lista dinámica -->
                            </div>
                            <div id="<?= $idPaginacion ?>" class="mt-3">
                                <!-- Paginación -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="button-form-cancel btn-volver">Volver</button>
                    <button type="button" class="button-form" id="<?= $idBotonNuevo ?>">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Formulario -->
    <div class="modal fade" id="<?= $idModalFormulario ?>" tabindex="-1" aria-labelledby="<?= $idModalFormulario ?>Label">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?= $idModalFormulario ?>Label"><?= htmlspecialchars($tituloFormulario) ?>
                    </h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #dc3545; font-size: 1.25rem; border: none; background: transparent;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="<?= $formId ?>" class="row g-3">
                        <?php
                        $prefijoActual = $prefijo . "Form"; // ejemplo: pracForm
                        include __DIR__ . '/formulario-paciente-base.php';

                        if (!empty($archivoExtra) && file_exists($archivoExtra)) {
                            include $archivoExtra;
                        }
                        ?>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="button-form-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="<?= $config['idBotonGuardar'] ?>" class="button-form">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>