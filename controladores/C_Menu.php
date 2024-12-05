<?php
require_once './controladores/Controlador.php';
require_once 'modelos/M_Menu.php';
require_once './vistas/Vista.php';

class C_Menu {
    private $menuModel;

    public function __construct() {
        $this -> menuModel = new M_Menu();
    }

    public function getFormattedMenu() {
        return $this -> menuModel -> getMenuOptions();
    }

    public function getVistaFiltros($datos=array()){
        Vista::render('./vistas/Menu/V_Menu_Filtros.php');
    }

    public function getVistaListadoMenu($filtros=array()) {
        $menus = $this -> menuModel -> buscarOpcionesMenu($filtros);
        Vista::render('./vistas/Menu/V_Menu_Listado.php', array('menus' => $menus));
    }    

    public function getVistaNuevoEditar($datos = array()) {
        if (!isset($datos['id']) || $datos['id'] == '') {
            if (isset($datos['menu_id']) && isset($datos['position_type'])) {
                // Obtener datos del menú base
                $menuReferencia = $this->menuModel->buscarOpcionesMenu(['id' => $datos['menu_id']]);
    
                if (!empty($menuReferencia)) {
                    $menuReferencia = $menuReferencia[0];
    
                    // Determinar nueva posición y nivel según el tipo
                    if ($datos['position_type'] === 'child') {
                        $nuevoNivel = $menuReferencia['level'] + 1;
                        $nuevaPosicion = $this->menuModel->obtenerUltimaPosicion($menuReferencia['id']) + 1;
                        $parentId = $menuReferencia['id'];
                    } else {
                        $nuevoNivel = $menuReferencia['level'];
                        $nuevaPosicion = $datos['position_type'] === 'below' ? $menuReferencia['position'] + 1 : $menuReferencia['position'];
                        $parentId = $menuReferencia['parent_id'];
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
                    // Manejar el caso donde no se encuentra el menú de referencia
                    Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', [
                        'error' => 'El menú de referencia no existe.'
                    ]);
                }
            } else {
                // Caso de nuevo menú sin referencia
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
    
        // Attempt to insert or update the menu
        $id = $this->menuModel->insertarMenu($datos); // This will handle both insert and update
    
        if ($id > 0) {
            echo "Guardado exitosamente.";
        } else {
            echo "Error: No se pudo guardar.";
        }
    
        exit;
    }
    
}
?>