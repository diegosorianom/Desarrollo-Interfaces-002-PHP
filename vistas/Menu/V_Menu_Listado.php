<script>
    // Definimos la función en el contexto global
    function mostrarPermiso(checkbox) {
        if (checkbox.checked) {
            console.log("Permiso seleccionado: " + checkbox.value);
        } else {
            console.log("Permiso deseleccionado: " + checkbox.value);
        }
    }
</script>
<?php 
$menus = array();
$permisos = array();
$frol = isset($_GET['frol']) ? $_GET['frol'] : '';
$fusuario = isset($_GET['fusuario']) ? $_GET['fusuario'] : '';
extract($datos);

// Verifica el contenido de los menús y permisos
function renderPermisos($permisos, $frol, $fusuario) {
    if (empty($permisos)) {
        return '<p>No hay permisos asociados.</p>';
    }

    $html = '<ul>';
    foreach ($permisos as $permiso) {
        $html .= '<li>';

        // Mostrar checkbox si hay un rol o usuario seleccionado
        if (!empty($frol) || !empty($fusuario)) {
            $html .= '<input type="checkbox" class="permiso-checkbox" id="permiso-' . htmlspecialchars($permiso['id']) . '" ';
            $html .= 'value="' . htmlspecialchars($permiso['permiso']) . '" onclick="mostrarPermiso(this)" /> ';
        }

        // Mostrar el nombre del permiso
        $html .= htmlspecialchars($permiso['permiso']) . ' (Código: ' . htmlspecialchars($permiso['codigo_permiso']) . ') ';

        // Mostrar botones si NO hay un rol o usuario seleccionado
        if (empty($frol) && empty($fusuario)) {
            $html .= '<button class="btn btn-sm btn-warning ms-2" onclick="obtenerVista_EditarCrear(\'Permisos\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . htmlspecialchars($permiso['id']) . '\')">Editar</button>';
            $html .= '<button class="btn btn-sm btn-danger ms-2" onclick="eliminarPermiso(' . htmlspecialchars($permiso['id']) . ')">Eliminar</button>';
        }

        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

if (!empty($frol)) {
    echo '<p>Rol seleccionado: ' . htmlspecialchars($frol) . '</p>';
}

if (!empty($fusuario)) {
    echo '<p>Usuario seleccionado: ' . htmlspecialchars($fusuario) . '</p>';
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
function renderMenu($menuTree, $frol, $fusuario, $level = 0) {
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

        // Obtener permisos desde el menú directamente
        if (!empty($menu['permisos'])) {
            $html .= '<div class="menu-permisos">';
            $html .= '<h5>Permisos:</h5>';
            $html .= renderPermisos($menu['permisos'], $frol, $fusuario); // Pasamos $frol y $fusuario
            $html .= '</div>';
        }

        // Fila de opciones (oculta por defecto)
        $html .= '<div class="menu-options" id="options-' . $menu['id'] . '" style="display: none;">';
        $html .= '<button class="btn btn-sm btn-primary me-2" onclick="obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \'' . $menu['id'] . '\')">Editar</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'above\')">Añadir Arriba</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirMenu(' . $menu['id'] . ', \'below\')">Añadir Abajo</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-2" onclick="añadirHijo(' . $menu['id'] . ')">Añadir Hijo</button>';
        $html .= '<button class="btn btn-sm btn-info me-2" onclick="obtenerVista_EditarCrear(\'Permisos\', \'getVistaNuevoEditar\', \'capaEditarCrear\', null, \'' . $menu['id'] . '\')">Nuevo Permiso</button>';
        $html .= '</div>';

        // Submenús (ocultos por defecto)
        if ($hasChildren) {
            $html .= '<div class="menu-children" id="children-' . $menu['id'] . '" style="display: none;">';
            $html .= renderMenu($menu['children'], $frol, $fusuario, $level + 1);
            $html .= '</div>';
        }
    }
    $html .= '</div>';
    return $html;
}

// Imprimir el menú
echo '<div class="menu-container">';
echo renderMenu($menuTree, $frol, $fusuario);
echo '</div>';
?>
