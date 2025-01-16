<?php
$id             = $permiso['id'] ?? '';
$permisoName    = $permiso['permiso'] ?? '';
$id_menu        = $permiso['id_menu'] ?? '';
$codigo_permiso = $permiso['codigo_permiso'] ?? '';
?>

<form id="formularioPermiso">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    
    <div class="mb-3">
        <label for="permiso">Permiso:</label>
        <input type="text" id="permiso" name="permiso" class="form-control" 
               value="<?php echo $permisoName; ?>">
    </div>
    <div class="mb-3">
        <label for="id_menu">Menú ID:</label>
        <input type="text" id="id_menu" name="id_menu" class="form-control" 
               value="<?php echo $id_menu; ?>">
    </div>
    <div class="mb-3">
        <label for="codigo_permiso">Código:</label>
        <input type="text" id="codigo_permiso" name="codigo_permiso" class="form-control" 
               value="<?php echo $codigo_permiso; ?>">
    </div>
    <button type="button" class="btn btn-primary" onclick="guardarPermiso()">Guardar</button>
</form>
