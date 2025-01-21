<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Roles.php';
require_once 'vistas/Vista.php';


class C_Roles extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Roles();
    }

    public function getRolesDropdown() {
        return $this->modelo->obtenerRoles();
    }
    
}

?>