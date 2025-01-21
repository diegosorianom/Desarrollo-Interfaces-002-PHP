<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Roles extends Modelo {
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new DAO();
    }

    public function obtenerRoles() {
        $SQL = 'SELECT id, nombre_rol FROM roles ORDER BY nombre_rol';
        return $this->DAO->consultar($SQL);
    }
}
?>