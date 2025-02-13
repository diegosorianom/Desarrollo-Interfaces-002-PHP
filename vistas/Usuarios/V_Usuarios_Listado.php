<?php
session_start(); // Inicia sesión si no está iniciada
$idRol = $_SESSION['id_rol'] ?? 19; // Asigna el rol Visitante si no hay uno definido

$usuarios = array();
extract($datos);

$html = '';
$html .= '<div class="table-responsive">
            <table class="table table-sm table-striped">
        ';
$html .= '<thead>
            <tr>';
            
// Si el usuario no es visitante, mostramos las columnas de edición
if ($idRol != 19) {
    $html .= '<th>Editar</th>';
}

$html .= '<th>Apellidos, Nombre</th>
            <th>Correo electrónico</th>
            <th>Login</th>
            <th>Estado</th>';
            
if ($idRol != 19) {
    $html .= '<th>Cambiar estado</th>';
}

$html .= '</tr>
        </thead>
        <tbody>';

foreach ($usuarios as $posicion => $fila) {        
    $estilo = '';
    if ($fila['activo'] == 'N') { 
        $activo = 'Inactivo';
        $rowClass = 'table-danger';  // Cambia el texto a rojo
    } else {
        $activo = 'Activo';
        $rowClass = '';  // Sin clase especial para filas activas
    }

    $html .= '<tr class="' . $rowClass . '">';
    
    // Solo si el rol NO es visitante, mostramos la columna de edición
    if ($idRol != 19) {
        $html .= '<td><button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Usuarios\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $fila['id_Usuario'] . '\')">Editar</button></td>';
    }
    
    $html .= '<td nowrap style="' . $estilo . '">' . $fila['apellido_1'] . ' ' . $fila['apellido_2'] . ', ' . $fila['nombre'] . '</td>
              <td>' . $fila['mail'] . '</td>
              <td>' . $fila['login'] . '</td>
              <td id="estado-' . $fila['id_Usuario'] . '">' . $activo . '</td>';

    // Solo si el rol NO es visitante, mostramos el botón de cambiar estado
    if ($idRol != 19) {
        $html .= '<td><button class="btn btn-primary" onclick="toggleEstado(\'' . $fila['id_Usuario'] . '\')">Editar estado</button></td>';
    }

    $html .= '</tr>';
}

$html .= '</tbody>
        </table>
        </div>';

echo $html;
?>
