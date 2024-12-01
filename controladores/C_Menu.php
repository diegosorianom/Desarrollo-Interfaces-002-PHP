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
                $menuReferencia = $this->menuModel->buscarOpcionesMenu(['id' => $datos['menu_id']])[0];
    
                // Determinar nueva posición según el tipo
                $nuevaPosicion = $datos['position_type'] === 'below'
                    ? $menuReferencia['position'] + 1 // Posición para "abajo"
                    : $menuReferencia['position']; // Posición para "arriba"
    
                $nuevoMenu = [
                    'label' => '', // El usuario ingresará esto
                    'url' => '',
                    'action' => '',
                    'position' => $nuevaPosicion, // Nueva posición calculada
                    'level' => $menuReferencia['level'], // Mismo nivel que el menú base
                    'parent_id' => $menuReferencia['parent_id'], // Mismo padre
                    'is_active' => 1, // Activado por defecto
                ];
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', ['menu' => $nuevoMenu]);
            } else {
                // Caso de nuevo menú sin referencia
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php');
            }
        } else {
            // Caso de edición
            $filtros['id'] = $datos['id'];
            $menus = $this->menuModel->buscarOpcionesMenu($filtros);
            Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', ['menu' => $menus[0]]);
        }
    }
    
    
    

    public function guardarMenu($datos = array()) {
        if (!is_array($datos) || empty($datos)) {
            echo "Error: Datos inválidos.";
            exit;
        }
    
        if (empty($datos['id'])) {
            // Actualizar posiciones de los menús existentes
            $this->menuModel->actualizarPosiciones($datos['level'], $datos['position']);
    
            // Insertar el nuevo menú
            $id = $this->menuModel->insertarMenu($datos);
        } else {
            // Editar menú existente
            $id = $this->menuModel->editarMenu($datos);
        }
    
        if ($id > 0) {
            echo "Guardado exitosamente.";
        } else {
            echo "Error: No se pudo guardar.";
        }
    
        exit;
    }
    
    
    
}
?>