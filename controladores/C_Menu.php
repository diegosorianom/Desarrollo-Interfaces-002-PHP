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

    public function getVistaNuevoEditar($datos=array()) {
        if (!isset($datos['id']) || $datos['id']=='') {
            // Nuevo
            Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php');
        } else {
            $filtros['id'] = $datos['id'];
            $menus = $this -> menuModel -> buscarOpcionesMenu($filtros);
            Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', array('menu' => $menus[0]));
        }
    }    

    public function guardarMenu($datos = array()) {
        // Valida los datos
        if (!is_array($datos) || empty($datos)) {
            echo "Error: Datos inválidos.";
            exit;
        }
    
        // Lógica para guardar o editar
        if (!empty($datos['id'])) {
            $id = $this->menuModel->editarMenu($datos);
        } else {
            $id = $this->menuModel->insertarMenu($datos);
        }
    
        // Verifica el resultado
        if ($id > 0) {
            echo "Guardado exitosamente.";
        } else {
            echo "Error: No se pudo guardar.";
        }
    
        exit;
    }
}
?>