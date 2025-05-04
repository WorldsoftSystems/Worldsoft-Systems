<!-- Modal de Bootstrap 5 para registrar usuarios -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registroModalLabel">Registro de un nuevo usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="registrarNuevoUsuario.php" method="POST">
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label">Contraseña:</label>
            <input type="password" name="clave" id="clave" class="form-control" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success">Crear usuario</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
