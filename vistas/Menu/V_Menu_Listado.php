<?php 
    $menus = array();
    extract($datos);

    usort($menus, function($a, $b) {
        if ($a['parent_id'] == 0 && $b['parent_id'] != 0) {
            return -1;
        } elseif ($a['parent_id'] != 0 && $b['parent_id'] == 0) {
            return 1;
        } else {
            return $a['position'] - $b['position'];
        }
    });

    $html='';
    // $html.='<button type="button" class="btn btn-primary" onclick="obtenerVista(\'Menu\', \'getVistaListadoMenu\', \'capaContenido\')">Recargar</button>';
    $html.='<div class="table-responsive">
                <table class="table table-sm table-striped">
    ';
    $html.='<thead>
                <tr>
                    <th>Nombre de menu</th>
                    <th>URL</th>
                    <th>ID del padre</th>
                    <th>Position</th>
                    <th>Nivel</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
        </thead>
        <tbody>';
    foreach($menus as $posicion => $fila) {
        $html .= '<tr>
            <td>' . $fila['label'] . '</td>
            <td>' . $fila['url'] . '</td>
            <td>' . $fila['parent_id'] . '</td>
            <td>' . $fila['position'] . '</td>
            <td>' . $fila['level'] . '</td>
            <td>' . ($fila['is_active'] == 1 ? 'Activo' : 'Inactivo') . '</td>
            <td>
                <button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $fila['id'] . '\')">
                    Editar
                </button>
                <button class="btn btn-success" onclick="obtenerVista(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', {parent_id: ' . $fila['id'] . '})">
                    Nueva Opción
                </button>
            </td>
        </tr>';
    }
    $html.='</tbody>
    </table>
    </div>';

    echo $html;
?>

<div class="container-fluid" id="capaEditarCrear"></div>