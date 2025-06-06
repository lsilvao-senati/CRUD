<?php
$title = "Editar Citas";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Editar Citas</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al actualizar el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=citas&action=update">

                <input type="hidden" name="id" value="<?= $citas->id ?>">

                <div class="mb-3">
                    <label for="paciente_id" class="form-label">Paciente Id</label>
                    <select class="form-select" id="paciente_id" name="paciente_id" >
                        <option value="">-- Seleccione Paciente Id --</option>
                        <?php
                        /* TODO: Populate options from database for table pacientes.
                           The value for this field is <?= $citas->paciente_id ?>.
                           You would typically loop through results from a query to the 'pacientes' table
                           and if a row's ID matches <?= $citas->paciente_id ?>, you add 'selected' to its <option>.

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
                        <?php if (isset($citas->paciente_id)): ?>
                            <option value="<?= htmlspecialchars($citas->paciente_id) ?>" selected >
                                <?= htmlspecialchars($citas->paciente_id) ?> (Current Value - Needs Options Population)
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">Doctor Id</label>
                    <select class="form-select" id="doctor_id" name="doctor_id" >
                        <option value="">-- Seleccione Doctor Id --</option>
                        <?php
                        /* TODO: Populate options from database for table doctores.
                           The value for this field is <?= $citas->doctor_id ?>.
                           You would typically loop through results from a query to the 'doctores' table
                           and if a row's ID matches <?= $citas->doctor_id ?>, you add 'selected' to its <option>.

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
                        <?php if (isset($citas->doctor_id)): ?>
                            <option value="<?= htmlspecialchars($citas->doctor_id) ?>" selected >
                                <?= htmlspecialchars($citas->doctor_id) ?> (Current Value - Needs Options Population)
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha"
                           value="<?= $citas->fecha ?>" >
                </div>

                <div class="mb-3">
                    <label for="hora" class="form-label">Hora</label>
                    <input type="text" class="form-control" id="hora" name="hora"
                           value="<?= $citas->hora ?>" >
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="3" ><?= $citas->motivo ?></textarea>
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=citas&action=index" class="btn btn-secondary">
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