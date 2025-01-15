<?php
$permisos = array();
extract($datos);

$html = '<div class="table-responsive"><table class="table table-striped">';
$html .= '<thead>
            <tr>
                <th>ID</th>
                <th>Permiso</th>
                <th>Menú</th>
                <th>Código</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>';
foreach ($permisos as $permiso) {
    $html .= '<tr>
                <td>' . $permiso['id'] . '</td>
                <td>' . $permiso['permiso'] . '</td>
                <td>' . $permiso['id_menu'] . '</td>
                <td>' . $permiso['codigo_permiso'] . '</td>
                <td><button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Permisos\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $permiso['id'] . '\')">Editar</button></td>
                <td><button class="btn btn-danger" onclick="eliminarPermiso(\'' . $permiso['id'] . '\')">Eliminar</button></td>
              </tr>';
}
$html .= '</tbody></table></div>';
echo $html;
?>
