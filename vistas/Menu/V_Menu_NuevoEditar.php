<?php
echo json_encode($datos);

$id = '';
$label = '';
$url = '';
$parent_id = '';
$level = '';
$position = '';
$is_active = '';
$action = '';

if (isset($datos['menu'])) {
    extract($datos['menu']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}

?>
<div class="container" id="capaEditarCrear" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
    <form id="formularioNuevoEditar" name="formularioNuevoEditar">
        <?php if ($editar == 'Editar'): ?>
            <div class="form-group" style="display: none;">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>">
            </div>
        <?php endif; ?>

        <?php if (isset($parent_id) && $parent_id != ''): ?>
            <!-- Campo oculto para parent_id -->
            <input type="hidden" id="parent_id" name="parent_id" value="<?php echo $parent_id; ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="label" class="form-label">Label</label>
            <input type="text" class="form-control" id="label" name="label" value="<?php echo $label; ?>" required>
        </div>

        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="text" class="form-control" id="url" name="url" value="<?php echo $url; ?>">
        </div>

        <div class="mb-3">
            <label for="level" class="form-label">Nivel</label>
            <input type="text" class="form-control" id="level" name="level" value="<?php echo $level; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Posición</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo $position; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Activo</label>
            <select class="form-select" id="is_active" name="is_active">
                <option value="1" <?php echo (isset($is_active) && $is_active == 1) ? 'selected' : ''; ?>>Si</option>
                <option value="0" <?php echo (isset($is_active) && $is_active == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="action" class="form-label">Acción</label>
            <input type="text" class="form-control" id="action" name="action" value="<?php echo $action; ?>">
        </div>

        <div class="row py-1">
            <div class="col-12">
                <button type="button" class="btn btn-success" onclick="guardarMenu()"><?php echo $editar == 'Editar' ? 'Guardar' : 'Crear'; ?></button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('capaEditarCrear').innerHTML = '';">Cancelar</button>
                <span id="msjError" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>

