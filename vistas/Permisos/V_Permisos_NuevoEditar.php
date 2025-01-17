<?php
// Inicializar variables con valores predeterminados o valores recibidos
$id = '';
$permiso = '';
$id_menu = '';
$codigo_permiso = '';

if (isset($datos['permiso'])) {
    extract($datos['permiso']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}
?>

<div class="container" id="capaEditarCrearPermisos" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
    <form id="formularioPermiso" name="formularioPermiso">
        <!-- Campo oculto para el ID del permiso (solo en modo editar) -->
        <?php if ($editar == 'Editar'): ?>
            <div class="form-group" style="display: none;">
                <label for="id">ID del Permiso</label>
                <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly>
            </div>
        <?php endif; ?>

        <!-- Campo oculto para asociar el permiso a un menú específico -->
        <input type="hidden" id="id_menu" name="id_menu" value="<?php echo $id_menu; ?>">

        <!-- Mostrar el ID del menú como información (solo lectura) -->
        <div class="mb-3">
            <label for="id_menu_display" class="form-label">ID del Menú Asociado</label>
            <input type="text" class="form-control" id="id_menu_display" value="<?php echo $id_menu; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="permiso" class="form-label">Nombre del Permiso</label>
            <input type="text" class="form-control" id="permiso" name="permiso" value="<?php echo $permiso; ?>" required>
        </div>

        <div class="mb-3">
            <label for="codigo_permiso" class="form-label">Código del Permiso</label>
            <input type="text" class="form-control" id="codigo_permiso" name="codigo_permiso" value="<?php echo $codigo_permiso; ?>" required>
        </div>

        <div class="row py-1">
            <div class="col-12">
                <button type="button" class="btn btn-success" onclick="guardarPermiso()"><?php echo $editar == 'Editar' ? 'Guardar' : 'Crear'; ?></button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('capaEditarCrearPermisos').innerHTML = '';">Cancelar</button>
                <span id="msjErrorPermisos" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>
