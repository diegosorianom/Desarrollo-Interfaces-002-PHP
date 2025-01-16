<?php 
$menus = array();
extract($datos);

function renderPermisos($permisos) {
    $html = '<ul>';
    foreach ($permisos as $permiso) {
        $html .= '<li>' . htmlspecialchars($permiso['id']) . ' - ' . htmlspecialchars($permiso['permiso']) . '</li>';
    }
    $html .= '</ul>';
    return $html;
}

// Ordenar los menús
usort($menus, function($a, $b) {
    if ($a['parent_id'] == 0 && $b['parent_id'] != 0) {
        return -1;
    } elseif ($a['parent_id'] != 0 && $b['parent_id'] == 0) {
        return 1;
    } else {
        return $a['position'] - $b['position'];
    }
});

// Construir el árbol jerárquico de menús
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

// Renderizar el menú en forma de lista
function renderMenu($menuTree, $level = 0) {
    $html = '<div class="menu-list">';
    foreach ($menuTree as $menu) {
        // Fila del menú principal
        $hasChildren = !empty($menu['children']);
        $html .= '<div class="menu-row" data-menu-id="' . $menu['id'] . '" onclick="toggleOptions(' . $menu['id'] . ')">';
        $html .= '<div class="menu-content">';
        $html .= $hasChildren 
            ? '<span class="arrow" onclick="toggleChildren(' . $menu['id'] . ', event)"><i class="fas fa-chevron-right"></i></span>' 
            : '<span class="arrow-placeholder"></span>';
        $html .= '<span class="menu-name">' . $menu['label'] . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        // Mostrar permisos si están disponibles
        if (!empty($menu['permisos'])) {
            $html .= '<div class="menu-permisos">';
            $html .= '<h5>Permisos:</h5>';
            $html .= renderPermisos($menu['permisos']);
            $html .= '</div>';
        }

        // Fila de opciones (oculta por defecto)
        $html .= '<div class="menu-options" id="options-' . $menu['id'] . '" style="display: none;">';
        $html .= '<button class="btn btn-sm btn-primary me-2" onclick="obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $menu['id'] . '\')">Editar</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'above\')">Añadir Arriba</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'below\')">Añadir Abajo</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirHijo(' . $menu['id'] . ')">Añadir Hijo</button>';
        $html .= '</div>';

        // Submenús (ocultos por defecto)
        if ($hasChildren) {
            $html .= '<div class="menu-children" id="children-' . $menu['id'] . '" style="display: none;">';
            $html .= renderMenu($menu['children'], $level + 1);
            $html .= '</div>';
        }
    }
    $html .= '</div>';
    return $html;
}

// Imprimir el menú
echo '<div class="menu-container">';
echo renderMenu($menuTree);
echo '</div>';
?>

<div class="container-fluid" id="capaEditarCrear"></div>
