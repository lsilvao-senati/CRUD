<?php
$title = "Crear Tratamientos";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo Tratamientos</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al crear el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=tratamientos&action=store">

                <div class="mb-3">
                    <label for="cita_id" class="form-label">Cita Id</label>
                    <select class="form-select" id="cita_id" name="cita_id" >
                        <option value="" selected disabled>-- Seleccione Cita Id --</option>
                        <?php /* TODO: Populate options from database for table citas */ ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" >
                </div>

                <div class="mb-3">
                    <label for="duracion" class="form-label">Duracion</label>
                    <input type="text" class="form-control" id="duracion" name="duracion" >
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" ></textarea>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=tratamientos&action=index" class="btn btn-secondary">
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