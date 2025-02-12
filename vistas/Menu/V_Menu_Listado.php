<?php 
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

/**
 * Función para renderizar el menú.
 *
 * @param array $menuTree         Árbol de menús.
 * @param array $permisos         Lista de permisos (cada permiso es un array con 'id', 'nombre' y 'id_menu').
 * @param int   $level            Nivel de profundidad (para estilos, si es necesario).
 * @param mixed $rolSeleccionado  Rol seleccionado (si lo hay).
 * @param mixed $usuarioSeleccionado Usuario seleccionado (si lo hay).
 * @param array $permisosAsignados Lista de IDs de permisos asignados (directos y heredados).
 * @param array $heredadosMap     Mapeo de permiso => [roles de origen] (para los permisos heredados).
 * @param array $rolesMap         Mapeo de id_rol => nombre_del_rol.
 *
 * @return string HTML generado.
 */
function renderMenu($menuTree, $permisos, $level = 0, $rolSeleccionado = null, $usuarioSeleccionado = null, $permisosAsignados = [], $heredadosMap = [], $rolesMap = []) {
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

        // Mostrar los permisos asociados a este menú
        $html .= '<div class="menu-permissions">';
        foreach ($permisos as $permiso) {
            if ($permiso['id_menu'] == $menu['id']) {
                // Verifica si el permiso está asignado (directa o heredado)
                $isAssigned = in_array($permiso['id'], $permisosAsignados);
                $checked    = $isAssigned ? 'checked' : '';
                $permisoNombre = isset($permiso['nombre']) ? $permiso['nombre'] : 'Sin nombre';

                $html .= '<div class="permiso-item" id="permiso-item-' . $permiso['id'] . '">';
                if ($rolSeleccionado || $usuarioSeleccionado) {
                    // Mostrar checkbox si se ha seleccionado rol o usuario
                    $html .= '<input type="checkbox" class="permiso-checkbox" data-permiso-id="' . $permiso['id'] . '" ' . $checked . ' onchange="togglePermiso(' . $permiso['id'] . ')">';
                    $html .= ' ' . htmlspecialchars($permisoNombre);
                    // Si el permiso es heredado, mostramos el nombre del rol en lugar del ID
                    if (isset($heredadosMap[$permiso['id']])) {
                        $rolesNombres = [];
                        foreach ($heredadosMap[$permiso['id']] as $idRol) {
                            // Si existe el nombre en el mapa, lo usamos; si no, mostramos el ID
                            $rolesNombres[] = isset($rolesMap[$idRol]) ? $rolesMap[$idRol] : $idRol;
                        }
                        $html .= ' <em>(Heredado de: ' . implode(', ', $rolesNombres) . ')</em>';
                    }
                } else {
                    // Mostrar botones para editar/eliminar cuando no se haya seleccionado rol/usuario
                    $html .= '<span id="permiso-nombre-' . $permiso['id'] . '">' . htmlspecialchars($permisoNombre) . '</span>';
                    $html .= ' <button type="button" class="btn btn-sm btn-info" onclick="mostrarEditarPermiso(' . $permiso['id'] . ')">Editar</button>';
                    $html .= ' <button type="button" class="btn btn-sm btn-danger" onclick="eliminarPermiso(' . $permiso['id'] . ')">Eliminar</button>';
                    $html .= ' <input type="text" class="form-control permiso-edit-input" id="permiso-edit-' . $permiso['id'] . '" value="' . htmlspecialchars($permisoNombre) . '" style="display:none; width:auto; display:inline-block;" onblur="actualizarNombrePermiso(' . $permiso['id'] . ')">';
                }
                $html .= '</div>';
            }
        }

        // Sección para crear un nuevo permiso en este menú
        $html .= '<div class="crear-permiso" style="margin-top: 5px;">';
        $html .= '<input type="text" class="form-control permiso-new-input" id="permiso-new-' . $menu['id'] . '" placeholder="Nuevo permiso" style="display:inline-block; width:70%;">';
        $html .= '<button type="button" class="btn btn-sm btn-success" onclick="crearPermiso(' . $menu['id'] . ')" style="display:inline-block;">Crear permiso</button>';
        $html .= '</div>';

        $html .= '</div>'; // Fin de la lista de permisos para este menú

        // Opciones del menú (editar, añadir arriba/abajo/hijo)
        $html .= '<div class="menu-options" id="options-' . $menu['id'] . '" style="display: none;">';
        $html .= '<button class="btn btn-sm btn-primary me-2" onclick="obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $menu['id'] . '\')">Editar</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'above\')">Añadir Arriba</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'below\')">Añadir Abajo</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirHijo(' . $menu['id'] . ')">Añadir Hijo</button>';
        $html .= '</div>';

        // Si el menú tiene hijos, se renderizan recursivamente
        if ($hasChildren) {
            $html .= '<div class="menu-children" id="children-' . $menu['id'] . '" style="display: none;">';
            $html .= renderMenu($menu['children'], $permisos, $level + 1, $rolSeleccionado, $usuarioSeleccionado, $permisosAsignados, $heredadosMap, $rolesMap);
            $html .= '</div>';
        }
    }
    $html .= '</div>';
    return $html;
}

// Imprimir el menú pasando también el mapa de roles
echo '<div class="menu-container">';
echo renderMenu(
    $menuTree,
    isset($permisos) ? $permisos : [],
    0,
    $rolSeleccionado,
    $usuarioSeleccionado,
    isset($permisosAsignados) ? $permisosAsignados : [],
    $heredadosMap,
    $rolesMap
);
echo '</div>';
?>
