<?php
$title = "Crear Especialidades";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo Especialidades</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al crear el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=especialidades&action=store">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=especialidades&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar
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