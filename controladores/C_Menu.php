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

    public function getVistaNuevoEditar($datos=array()) {
        if (empty($datos['id'])) {
            Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php');
        } else {
            $filtros['id'] = $datos['id'];
            $opcionesMenu = $this->menuModel->buscarOpcionesMenu($filtros);
            if (!empty($opcionesMenu)) {
                Vista::render('./vistas/Menu/V_Menu_NuevoEditar.php', array('opcionMenu' => $opcionesMenu[0]));
            } else {
                // Handle the case where no menu option is found
            }
        }
    }

    public function guardarMenu($datos = array()) {
        $respuesta['correcto']='S';
        $respuesta['msj']='Creado correctamente.';

        if ($datos['id'] !== null && $datos['id'] !== '' && isset($datos['id'])) {
            $id=$this->menuModel->editarMenu($datos);
        } else {
            $id=$this->menuModel->insertarMenu($datos);
        }

        if ($id > 0) {

        } else {
            $respuesta['correcto']='N';
            $respuesta['msj']='Error al crear';
        }
        echo json_encode($respuesta);
    }
}
?>