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
    
    
}
?>