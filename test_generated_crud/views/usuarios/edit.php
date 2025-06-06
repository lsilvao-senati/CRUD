<?php
$title = "Editar Usuarios";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Editar Usuarios</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al actualizar el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=usuarios&action=update">

                <input type="hidden" name="id" value="<?= $usuarios->id ?>">

                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                           value="<?= $usuarios->nombre_usuario ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control" id="password" name="password"
                           value="<?= $usuarios->password ?>" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <input type="text" class="form-control" id="rol" name="rol"
                           value="<?= $usuarios->rol ?>" required>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=usuarios&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include(__DIR__ . '/../layout.php');
?>