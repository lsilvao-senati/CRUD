<?php
$title = "Crear Recetas";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo Recetas</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al crear el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=recetas&action=store">

                <div class="mb-3">
                    <label for="cita_id" class="form-label">Cita Id</label>
                    <select class="form-select" id="cita_id" name="cita_id" >
                        <option value="" selected disabled>-- Seleccione Cita Id --</option>
                        <?php /* TODO: Populate options from database for table citas */ ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="medicamento" class="form-label">Medicamento</label>
                    <input type="text" class="form-control" id="medicamento" name="medicamento" >
                </div>

                <div class="mb-3">
                    <label for="dosis" class="form-label">Dosis</label>
                    <input type="text" class="form-control" id="dosis" name="dosis" >
                </div>

                <div class="mb-3">
                    <label for="frecuencia" class="form-label">Frecuencia</label>
                    <input type="text" class="form-control" id="frecuencia" name="frecuencia" >
                </div>

                <div class="mb-3">
                    <label for="duracion" class="form-label">Duracion</label>
                    <input type="text" class="form-control" id="duracion" name="duracion" >
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=recetas&action=index" class="btn btn-secondary">
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