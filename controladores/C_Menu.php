<?php
require_once './controladores/Controlador.php';
require_once 'modelos/M_Menu.php';
require_once 'modelos/M_Permisos.php';  // Agregamos la inclusión del modelo de permisos
require_once './vistas/Vista.php';

class C_Menu {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new M_Menu();
    }

    public function getFormattedMenu() {
        return $this -> menuModel -> getMenuOptions();
    }

    public function getVistaFiltros($datos = array()) {
        $roles = $this->menuModel->getRoles();
        $usuarios = $this->menuModel->getUsuarios();
    
        // Asegurar que siempre se pase un array
        if (!is_array($roles)) {
            $roles = [];
        }
        if (!is_array($usuarios)) {
            $usuarios = [];
        }
    
        // Pasar los roles correctamente a la vista
        Vista::render('./vistas/Menu/V_Menu_Filtros.php', [
            'roles' => $roles,
            'usuarios' => $usuarios
        ]);
    }

    public function getVistaListadoMenu($filtros = array()) {
        $menus = $this->menuModel->buscarOpcionesMenu($filtros);
        $permisos = $this->menuModel->getPermisosPorMenu();
    
        // Obtener los filtros de usuario y rol
        $idUsuario = isset($filtros['fusuario']) ? $filtros['fusuario'] : null;
        $idRol = isset($filtros['frol']) ? $filtros['frol'] : null;
    
        // Obtener permisos asignados según rol o usuario
        $mPermisos = new M_Permisos();
        $permisosAsignados = [];
        if (!empty($idRol)) {
            $permisosAsignados = $mPermisos->getPermisosAsignados($idRol);
        } elseif (!empty($idUsuario)) {
            $permisosAsignados = $mPermisos->getPermisosAsignadosUsuario($idUsuario);
        }
    
        // Pasar los datos a la vista, incluyendo los permisos asignados
        Vista::render('./vistas/Menu/V_Menu_Listado.php', [
            'menus'              => $menus,
            'permisos'           => $permisos,
            'id_Usuario'         => $idUsuario,
            'permisosAsignados'  => $permisosAsignados,
        ]);
    }
    
    

    public function getVistaNuevoEditar($datos = array()) {
        if (!isset($datos['id']) || $datos['id'] == '') {
            if (isset($datos['menu_id']) && isset($datos['position_type'])) {
                // Obtener datos del menú base
                $menuReferencia = $this->menuModel->buscarOpcionesMenu(['id' => $datos['menu_id']]);
    
                if (!empty($menuReferencia)) {
                    $menuReferencia = $menuReferencia[0];
    
                    // Determinar nueva posición y nivel según el tipo de posición
                    if ($datos['position_type'] === 'above') {
                        $nuevoNivel = $menuReferencia['level'];
                        $nuevaPosicion = $menuReferencia['position'];
                        $parentId = $menuReferencia['parent_id'];
                    } elseif ($datos['position_type'] === 'below') {
                        $nuevoNivel = $menuReferencia['level'];
                        $nuevaPosicion = $menuReferencia['position'] + 1;
                        $parentId = $menuReferencia['parent_id'];
                    } elseif ($datos['position_type'] === 'child') {
                        $nuevoNivel = $menuReferencia['level'] + 1; // Incrementa el nivel
                        $parentId = $menuReferencia['id']; // El parent_id es el ID del menú actual
    
                        // Obtener la posición más alta entre los hijos del menú actual
                        $maxPosition = $this->menuModel->getMaxPosition($parentId, $nuevoNivel);
    
                        // Nuevo menú irá a la siguiente posición disponible
                        $nuevaPosicion = $maxPosition + 1;
                    } else {
                        // Caso de error para valores inesperados
                        Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', [
                            'error' => 'Tipo de posición no válido.'
                        ]);
                        return;
                    }
    
                    $nuevoMenu = [
                        'label' => '',
                        'url' => '',
                        'action' => '',
                        'position' => $nuevaPosicion,
                        'level' => $nuevoNivel,
                        'parent_id' => $parentId,
                        'is_active' => 1,
                    ];
    
                    Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', ['menu' => $nuevoMenu]);
                } else {
                    Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', [
                        'error' => 'El menú de referencia no existe.'
                    ]);
                }
            } else {
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php');
            }
        } else {
            // Caso de edición
            $filtros['id'] = $datos['id'];
            $menus = $this->menuModel->buscarOpcionesMenu($filtros);
    
            if (!empty($menus)) {
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', ['menu' => $menus[0]]);
            } else {
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', [
                    'error' => 'No se encontró el menú a editar.'
                ]);
            }
        }
    }

    public function guardarMenu($datos = array()) {
        if (!is_array($datos) || empty($datos)) {
            echo "Error: Datos inválidos.";
            exit;
        }
    
        // Intentar insertar o actualizar el menú
        $id = $this->menuModel->insertarMenu($datos); // Esto manejará tanto inserciones como actualizaciones
    
        if ($id > 0) {
            echo "Guardado exitosamente.";
        } else {
            echo "Error: No se pudo guardar.";
        }
    
        exit;
    }    

    public function getMenuFiltradoPorRol() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Intentamos obtener el ID de rol desde sesión
        $idRol = $_SESSION['roles'][0]['id_rol'] ?? null;
    
        // 🔥 SI NO HAY SESIÓN O EL ROL NO EXISTE, FORZAMOS `id_rol = 19` 🔥
        if ($idRol === null || empty($idRol)) {
            $idRol = 19;
        }
    
        // 🔥 IMPRIMIR EL ROL DETECTADO PARA DEPURACIÓN 🔥
        echo "<pre>🔍 ID Rol detectado: $idRol</pre>";
    
        if ($idRol == 1) {
            return $this->menuModel->getMenuOptions(); // Administrador ve TODO
        }
    
        return $this->menuModel->getMenuPorRol($idRol); // Filtra menús por rol (incluye visitante)
    }    
    
}   
?>

