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

    public function getVistaListadoOpcionesMenu($filtros=array()){
        $opcionesMenu=$this->menuModel->buscarOpcionesMenu($filtros);
        Vista::render('vistas/menu/V_Menu_Listado.php',array('opcionesMenu'=>$opcionesMenu));
    }

    public function getVistaNuevoEditar ($filtros=array()) {
        if (!isset($datos['id']) || $datos['id']=='') {
            Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php');
        } else {
            
        }
    }

    public function guardarOpcionMenu
}
?>