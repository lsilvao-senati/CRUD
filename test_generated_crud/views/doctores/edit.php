<?php
$title = "Editar Doctores";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Editar Doctores</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al actualizar el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=doctores&action=update">

                <input type="hidden" name="id" value="<?= $doctores->id ?>">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?= $doctores->nombre ?>" required>
                </div>

                <div class="mb-3">
                    <label for="numero_colegiatura" class="form-label">Numero Colegiatura</label>
                    <input type="text" class="form-control" id="numero_colegiatura" name="numero_colegiatura"
                           value="<?= $doctores->numero_colegiatura ?>" required>
                </div>

                <div class="mb-3">
                    <label for="especialidad_id" class="form-label">Especialidad Id</label>
                    <select class="form-select" id="especialidad_id" name="especialidad_id" >
                        <option value="">-- Seleccione Especialidad Id --</option>
                        <?php
                        /* TODO: Populate options from database for table especialidades.
                           The value for this field is <?= $doctores->especialidad_id ?>.
                           You would typically loop through results from a query to the 'especialidades' table
                           and if a row's ID matches <?= $doctores->especialidad_id ?>, you add 'selected' to its <option>.

                           Example:
                           // Assuming $fk_options is an array of [value => text] for the dropdown
                           // and $current_fk_value holds the current foreign key value for this field.
                           // $pk_col_name would be the primary key column name of the foreign_key_to_table (e.g. 'id')
                           // $display_col_name would be the column name to display from the foreign_key_to_table (e.g. 'nombre')

                           // foreach ($fk_options as $option_row) { // Assuming $fk_options is fetched in controller
                           //    $selected_attr = ($current_fk_value == $option_row[$pk_col_name]) ? 'selected' : '';
                           //    echo "<option value='" . $option_row[$pk_col_name] . "' {$selected_attr}>" . $option_row[$display_col_name] . "</option>";
                           // }
                        */
                        ?>
                        <?php if (isset($doctores->especialidad_id)): ?>
                            <option value="<?= htmlspecialchars($doctores->especialidad_id) ?>" selected >
                                <?= htmlspecialchars($doctores->especialidad_id) ?> (Current Value - Needs Options Population)
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=doctores&action=index" class="btn btn-secondary">
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