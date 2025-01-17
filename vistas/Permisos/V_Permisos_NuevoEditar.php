<?php
// Debug: Print all variables
echo "<pre>Debug: All variables in V_Permisos_NuevoEditar:\n";
print_r(get_defined_vars());
echo "</pre>";

// Extract the permiso array from the datos array
$permiso = $datos['permiso'] ?? [];

$id = $permiso['id'] ?? '';
$permisoName = $permiso['permiso'] ?? '';
$id_menu = $permiso['id_menu'] ?? '';
$codigo_permiso = $permiso['codigo_permiso'] ?? '';

// Debug: Print extracted variables
echo "<pre>Debug: Extracted variables:\n";
echo "id: $id\n";
echo "permisoName: $permisoName\n";
echo "id_menu: $id_menu\n";
echo "codigo_permiso: $codigo_permiso\n";
echo "</pre>";
?>

<form id="formularioPermiso">
    <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">
    
    <div class="mb-3">
        <label for="permiso">Permiso:</label>
        <input type="text" id="permiso" name="permiso" class="form-control" 
               value="<?php echo htmlspecialchars($permisoName); ?>">
    </div>
    <div class="mb-3">
        <label for="id_menu">Menú ID:</label>
        <input type="text" id="id_menu" name="id_menu" class="form-control" 
               value="<?php echo htmlspecialchars($id_menu); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="codigo_permiso">Código:</label>
        <input type="text" id="codigo_permiso" name="codigo_permiso" class="form-control" 
               value="<?php echo htmlspecialchars($codigo_permiso); ?>">
    </div>
    <button type="button" class="btn btn-primary" onclick="guardarPermiso()">Guardar</button>
</form>

<script>
console.log('Form data:', {
    id: document.getElementById('id').value,
    permiso: document.getElementById('permiso').value,
    id_menu: document.getElementById('id_menu').value,
    codigo_permiso: document.getElementById('codigo_permiso').value
});
</script>

