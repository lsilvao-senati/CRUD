<?php
$title = "Crear Citas";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo Citas</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al crear el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=citas&action=store">

                <div class="mb-3">
                    <label for="paciente_id" class="form-label">Paciente Id</label>
                    <select class="form-select" id="paciente_id" name="paciente_id" >
                        <option value="" selected disabled>-- Seleccione Paciente Id --</option>
                        <?php /* TODO: Populate options from database for table pacientes */ ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">Doctor Id</label>
                    <select class="form-select" id="doctor_id" name="doctor_id" >
                        <option value="" selected disabled>-- Seleccione Doctor Id --</option>
                        <?php /* TODO: Populate options from database for table doctores */ ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" >
                </div>

                <div class="mb-3">
                    <label for="hora" class="form-label">Hora</label>
                    <input type="text" class="form-control" id="hora" name="hora" >
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="3" ></textarea>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=citas&action=index" class="btn btn-secondary">
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