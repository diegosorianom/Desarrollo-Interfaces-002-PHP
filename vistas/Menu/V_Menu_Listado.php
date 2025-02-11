<?php 
// Se asume que $datos contiene 'menus', 'permisos', 'permisosAsignados', etc.
extract($datos);

$rolSeleccionado = isset($_GET['frol']) ? $_GET['frol'] : null;
$usuarioSeleccionado = isset($_GET['fusuario']) ? $_GET['fusuario'] : null;

// Ordenar los menús y construir el árbol jerárquico (ya implementado)
usort($menus, function($a, $b) {
    if ($a['parent_id'] == 0 && $b['parent_id'] != 0) {
        return -1;
    } elseif ($a['parent_id'] != 0 && $b['parent_id'] == 0) {
        return 1;
    } else {
        return $a['position'] - $b['position'];
    }
});

function buildMenuTree($menus, $parentId = 0) {
    $tree = [];
    foreach ($menus as $menu) {
        if ($menu['parent_id'] == $parentId) {
            $children = buildMenuTree($menus, $menu['id']);
            if ($children) {
                $menu['children'] = $children;
            }
            $tree[] = $menu;
        }
    }
    return $tree;
}

$menuTree = buildMenuTree($menus);

// Función para renderizar el menú
function renderMenu($menuTree, $permisos, $level = 0, $rolSeleccionado = null, $usuarioSeleccionado = null, $permisosAsignados = []) {
    $html = '<div class="menu-list">';
    foreach ($menuTree as $menu) {
        $hasChildren = !empty($menu['children']);
        $html .= '<div class="menu-row" data-menu-id="' . $menu['id'] . '" onclick="toggleOptions(' . $menu['id'] . ')">';
        $html .= '<div class="menu-content">';
        $html .= $hasChildren 
            ? '<span class="arrow" onclick="toggleChildren(' . $menu['id'] . ', event)"><i class="fas fa-chevron-right"></i></span>' 
            : '<span class="arrow-placeholder"></span>';
        $html .= '<span class="menu-name">' . htmlspecialchars($menu['label']) . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        // Permisos con checkboxes
        // Permisos con checkboxes o botones de editar/eliminar
        $html .= '<div class="menu-permissions">';
        foreach ($permisos as $permiso) {
            if ($permiso['id_menu'] == $menu['id']) {
                // Si el permiso está asignado, se marca el checkbox (en el caso de que haya rol/usuario)
                $checked = in_array($permiso['id'], $permisosAsignados) ? 'checked' : '';
                $permisoNombre = isset($permiso['nombre']) ? $permiso['nombre'] : 'Sin nombre';
                
                // Asignamos un id único a cada contenedor del permiso para facilitar su manipulación
                $html .= '<div class="permiso-item" id="permiso-item-' . $permiso['id'] . '">';
                if ($rolSeleccionado || $usuarioSeleccionado) {
                    // Si se ha seleccionado rol o usuario, se muestra el checkbox
                    $html .= '<input type="checkbox" class="permiso-checkbox" data-permiso-id="' . $permiso['id'] . '" ' . $checked . ' onchange="togglePermiso(' . $permiso['id'] . ')">';
                    $html .= ' ' . htmlspecialchars($permisoNombre);
                } else {
                    // Si NO se ha seleccionado rol/usuario, se muestra el nombre con botones para editar y eliminar
                    $html .= '<span id="permiso-nombre-' . $permiso['id'] . '">' . htmlspecialchars($permisoNombre) . '</span>';
                    $html .= ' <button type="button" class="btn btn-sm btn-info" onclick="mostrarEditarPermiso(' . $permiso['id'] . ')">Editar</button>';
                    $html .= ' <button type="button" class="btn btn-sm btn-danger" onclick="eliminarPermiso(' . $permiso['id'] . ')">Eliminar</button>';
                    // Input para editar (oculto por defecto)
                    $html .= ' <input type="text" class="form-control permiso-edit-input" id="permiso-edit-' . $permiso['id'] . '" value="' . htmlspecialchars($permisoNombre) . '" style="display:none; width:auto; display:inline-block;" onblur="actualizarNombrePermiso(' . $permiso['id'] . ')">';
                }
                $html .= '</div>';
            }
        }
        $html .= '</div>';


        // Opciones del menú (edición, añadir arriba/abajo/hijo)
        $html .= '<div class="menu-options" id="options-' . $menu['id'] . '" style="display: none;">';
        $html .= '<button class="btn btn-sm btn-primary me-2" onclick="obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $menu['id'] . '\')">Editar</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'above\')">Añadir Arriba</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'below\')">Añadir Abajo</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirHijo(' . $menu['id'] . ')">Añadir Hijo</button>';
        $html .= '</div>';

        if ($hasChildren) {
            $html .= '<div class="menu-children" id="children-' . $menu['id'] . '" style="display: none;">';
            $html .= renderMenu($menu['children'], $permisos, $level + 1, $rolSeleccionado, $usuarioSeleccionado, $permisosAsignados);
            $html .= '</div>';
        }
    }
    $html .= '</div>';
    return $html;
}

// Imprimir el menú pasando también el arreglo de permisos asignados
echo '<div class="menu-container">';
echo renderMenu($menuTree, isset($permisos) ? $permisos : [], 0, $rolSeleccionado, $usuarioSeleccionado, isset($permisosAsignados) ? $permisosAsignados : []);
echo '</div>';
?>
