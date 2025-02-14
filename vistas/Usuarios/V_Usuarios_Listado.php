<?php
session_start(); // Inicia sesión si no está iniciada
// Obtener los permisos del usuario de la sesión y verificar el permiso 15
$permisos = $_SESSION['permisos'] ?? [];
$tienePermiso15 = in_array(15, $permisos);

$usuarios = array();
extract($datos);

$html = '';
$html .= '<div class="table-responsive">
            <table class="table table-sm table-striped">
        ';
$html .= '<thead>
            <tr>';
            
// Mostrar columna "Editar" solo si tiene el permiso 15
if ($tienePermiso15) {
    $html .= '<th>Editar</th>';
}

$html .= '<th>Apellidos, Nombre</th>
          <th>Correo electrónico</th>
          <th>Login</th>
          <th>Estado</th>';
          
// Mostrar columna "Cambiar estado" solo si tiene el permiso 15
if ($tienePermiso15) {
    $html .= '<th>Cambiar estado</th>';
}

$html .= '</tr>
        </thead>
        <tbody>';

foreach ($usuarios as $posicion => $fila) {        
    $estilo = '';
    if ($fila['activo'] == 'N') { 
        $activo = 'Inactivo';
        $rowClass = 'table-danger';  // Resalta la fila en rojo
    } else {
        $activo = 'Activo';
        $rowClass = '';  // Sin clase especial para usuarios activos
    }

    $html .= '<tr class="' . $rowClass . '">';
    
    // Mostrar botón "Editar" solo si tiene el permiso 15
    if ($tienePermiso15) {
        $html .= '<td><button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Usuarios\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $fila['id_Usuario'] . '\')">Editar</button></td>';
    }
    
    $html .= '<td nowrap style="' . $estilo . '">' . $fila['apellido_1'] . ' ' . $fila['apellido_2'] . ', ' . $fila['nombre'] . '</td>
              <td>' . $fila['mail'] . '</td>
              <td>' . $fila['login'] . '</td>
              <td id="estado-' . $fila['id_Usuario'] . '">' . $activo . '</td>';

    // Mostrar botón "Editar estado" solo si tiene el permiso 15
    if ($tienePermiso15) {
        $html .= '<td><button class="btn btn-primary" onclick="toggleEstado(\'' . $fila['id_Usuario'] . '\')">Editar estado</button></td>';
    }

    $html .= '</tr>';
}

$html .= '</tbody>
        </table>
        </div>';

echo $html;
?>
