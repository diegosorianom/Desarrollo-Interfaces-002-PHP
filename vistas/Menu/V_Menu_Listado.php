<?php 
session_start();

// Obtener los permisos del usuario desde la sesión
$permisosUsuario = $_SESSION['permisos'] ?? [];

// Verificar si el usuario tiene el permiso 21 (Modificar Dashboard)
$tienePermisoModificar = in_array(21, array_column($permisosUsuario, 'id'));

// Verificar si el usuario tiene el permiso 17 (Acceder Dashboard)
$tienePermisoAcceder = in_array(17, array_column($permisosUsuario, 'id'));

// Se asume que $datos contiene 'menus', 'permisos', 'permisosAsignados', y 'roles'
extract($datos);

$rolSeleccionado   = isset($_GET['frol']) ? $_GET['frol'] : null;
$usuarioSeleccionado = isset($_GET['fusuario']) ? $_GET['fusuario'] : null;

// Si tenemos los roles, construimos un mapa: id_rol => nombre_del_rol
$rolesMap = [];
if (isset($roles) && is_array($roles)) {
    foreach ($roles as $rol) {
        // Asegúrate de que los índices coincidan con los nombres de las columnas en tu BD
        $rolesMap[$rol['id']] = $rol['nombre'];
    }
}

// Ordenar los menús y construir el árbol jerárquico
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

// Permisos asignados directos (posiblemente vienen en $datos)
$permisosAsignadosDirectos = isset($permisosAsignados) ? $permisosAsignados : [];

// Si se ha seleccionado un usuario, obtenemos también los permisos heredados
$heredadosMap = []; // Relación: id_permiso => array de nombres de roles
if ($usuarioSeleccionado) {
    $m_permisos = new M_Permisos();
    $heredados = $m_permisos->getPermisosHeredadosPorRol($usuarioSeleccionado);
    
    // Construir el mapa de permiso → rol(s) de origen (almacenando el nombre del rol)
    foreach ($heredados as $heredado) {
        $idPermiso = $heredado['id_permiso'];
        $rolNombre = $heredado['nombre']; // Contiene el nombre del rol (ej.: "Administrador")
        if (!isset($heredadosMap[$idPermiso])) {
            $heredadosMap[$idPermiso] = [];
        }
        // Agregamos el nombre del rol si aún no está en el arreglo
        if (!in_array($rolNombre, $heredadosMap[$idPermiso])) {
            $heredadosMap[$idPermiso][] = $rolNombre;
        }
    }
}


// Extraer los IDs de los permisos heredados para combinarlos con los asignados directamente
$heredadosIds = array_keys($heredadosMap);

// Fusionamos ambos arrays (directos e heredados) para obtener la lista final de permisos asignados
$permisosAsignadosUser = array_unique(array_merge($permisosAsignadosDirectos, $heredadosIds));

echo '<div class="menu-container">';
echo renderMenu(
    $menuTree,
    isset($permisos) ? $permisos : [],
    0,
    $rolSeleccionado,
    $usuarioSeleccionado,
    $permisosAsignadosUser,
    $heredadosMap,
    $rolesMap  // Se pasa el mapa de roles para mostrar el nombre
);
echo '</div>';

function renderMenu($menuTree, $permisos, $level = 0, $rolSeleccionado = null, $usuarioSeleccionado = null, $permisosAsignados = [], $heredadosMap = [], $rolesMap = []) {
    // Obtener los permisos del usuario desde la sesión
    $permisosUsuario = $_SESSION['permisos'] ?? [];

    // Verificar permisos
    $tienePermisoModificar = in_array(21, array_column($permisosUsuario, 'id'));
    $tienePermisoVerPermisos = in_array(23, array_column($permisosUsuario, 'id'));

    // Si el usuario no tiene permisos de acceso ni de ver permisos, salir
    if (!$tienePermisoModificar && !$tienePermisoVerPermisos) {
        return '';
    }

    $html = '<div class="menu-list">';
    
    foreach ($menuTree as $menu) {
        $hasChildren = !empty($menu['children']);
        $menuOnClick = $tienePermisoModificar ? "toggleOptions({$menu['id']})" : "";

        $html .= '<div class="menu-row" data-menu-id="' . $menu['id'] . '" onclick="' . $menuOnClick . '">';
        $html .= '<div class="menu-content">';

        $toggleChildren = $hasChildren ? "toggleChildren({$menu['id']}, event)" : "";
        $html .= $hasChildren 
            ? '<span class="arrow" onclick="' . $toggleChildren . '"><i class="fas fa-chevron-right"></i></span>' 
            : '<span class="arrow-placeholder"></span>';

        $html .= '<span class="menu-name">' . htmlspecialchars($menu['label']) . '</span>';
        $html .= '</div>'; // Fin de .menu-content
        $html .= '</div>'; // Fin de .menu-row

        // ✅ Si el usuario tiene SOLO el permiso 23 (pero no el 21), mostrar solo los nombres de los permisos
        if ($tienePermisoVerPermisos && !$tienePermisoModificar) {
            $html .= '<div class="menu-permissions">';

            foreach ($permisos as $permiso) {
                if ($permiso['id_menu'] == $menu['id']) {
                    $permisoNombre = isset($permiso['nombre']) ? $permiso['nombre'] : 'Sin nombre';
                    $html .= '<div class="permiso-item">';
                    $html .= '<span>' . htmlspecialchars($permisoNombre) . '</span>';
                    
                    // Mostrar roles de los que proviene el permiso si es heredado
                    if (isset($heredadosMap[$permiso['id']])) {
                        $rolesNombres = [];
                        foreach ($heredadosMap[$permiso['id']] as $idRol) {
                            $rolesNombres[] = isset($rolesMap[$idRol]) ? $rolesMap[$idRol] : $idRol;
                        }
                        $html .= ' <em>(Heredado de: ' . implode(', ', $rolesNombres) . ')</em>';
                    }

                    $html .= '</div>'; // Fin .permiso-item
                }
            }

            $html .= '</div>'; // Fin de .menu-permissions
        }

        // ✅ Si el usuario tiene permiso 21 (con o sin 23), mostrar la funcionalidad normal
        if ($tienePermisoModificar) {
            $html .= '<div class="menu-permissions">';

            foreach ($permisos as $permiso) {
                if ($permiso['id_menu'] == $menu['id']) {
                    $isAssigned = in_array($permiso['id'], $permisosAsignados);
                    $checked = $isAssigned ? 'checked' : '';
                    $permisoNombre = isset($permiso['nombre']) ? $permiso['nombre'] : 'Sin nombre';

                    $html .= '<div class="permiso-item" id="permiso-item-' . $permiso['id'] . '">';
                    if ($rolSeleccionado || $usuarioSeleccionado) {
                        $html .= '<input type="checkbox" class="permiso-checkbox" data-permiso-id="' . $permiso['id'] . '" ' . $checked . ' onchange="togglePermiso(' . $permiso['id'] . ')">';
                        $html .= ' ' . htmlspecialchars($permisoNombre);

                        if (isset($heredadosMap[$permiso['id']])) {
                            $rolesNombres = [];
                            foreach ($heredadosMap[$permiso['id']] as $idRol) {
                                $rolesNombres[] = isset($rolesMap[$idRol]) ? $rolesMap[$idRol] : $idRol;
                            }
                            $html .= ' <em>(Heredado de: ' . implode(', ', $rolesNombres) . ')</em>';
                        }
                    } else {
                        $html .= '<span id="permiso-nombre-' . $permiso['id'] . '">' . htmlspecialchars($permisoNombre) . '</span>';
                        $html .= ' <button type="button" class="btn btn-sm btn-info" onclick="mostrarEditarPermiso(' . $permiso['id'] . ')">Editar</button>';
                        $html .= ' <button type="button" class="btn btn-sm btn-danger" onclick="eliminarPermiso(' . $permiso['id'] . ')">Eliminar</button>';
                        $html .= ' <input type="text" class="form-control permiso-edit-input" id="permiso-edit-' . $permiso['id'] . '" value="' . htmlspecialchars($permisoNombre) . '" style="display:none; width:auto; display:inline-block;" onblur="actualizarNombrePermiso(' . $permiso['id'] . ')">';
                    }
                    $html .= '</div>'; // Fin .permiso-item
                }
            }

            // Opción para crear nuevo permiso solo si tiene permiso 21
            $html .= '<div class="crear-permiso" style="margin-top: 5px;">';
            $html .= '<input type="text" class="form-control permiso-new-input" id="permiso-new-' . $menu['id'] . '" placeholder="Nuevo permiso" style="display:inline-block; width:70%;">';
            $html .= '<button type="button" class="btn btn-sm btn-success" onclick="crearPermiso(' . $menu['id'] . ')" style="display:inline-block;">Crear permiso</button>';
            $html .= '</div>';

            $html .= '</div>'; // Fin de .menu-permissions
        }

        if ($hasChildren) {
            $html .= '<div class="menu-children" id="children-' . $menu['id'] . '" style="display: none;">';
            $html .= renderMenu($menu['children'], $permisos, $level + 1, $rolSeleccionado, $usuarioSeleccionado, $permisosAsignados, $heredadosMap, $rolesMap);
            $html .= '</div>'; // Fin de .menu-children
        }
    }
    $html .= '</div>'; // Fin de .menu-list

    return $html;
}




echo '</div>';
?>
