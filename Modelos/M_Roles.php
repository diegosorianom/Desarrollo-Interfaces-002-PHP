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

    public function obtenerPermisosRol($id_rol) {
        $SQL = "SELECT p.id, p.permiso, p.codigo_permiso 
            FROM permisos p 
            INNER JOIN permisosroles pr ON p.id = pr.id_permiso 
            WHERE pr.id_rol = '$id_rol'";
        return $this->DAO->consultar($SQL);
    }
}
?>