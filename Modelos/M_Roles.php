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

    public function insertarRol($datos = array()) {
        $id_rol = '';
        $nombre_rol = '';
        extract($datos);

        if (!empty($id_rol)) {
            $SQL = "UPDATE roles SET nombre_rol = '$nombre_rol' WHERE id_rol = '$id_rol'";

            return $this -> DAO -> actualizar($SQL);
        } else {
            $SQL = "INSERT INTO roles SET nombre_rol = '$nombre_rol'";
            return $this -> DAO -> insertar($SQL);
        }
    }

    public function obtenerRolesUsuario($id_rol = '', $id_usuario = '') {
        if (!empty($id_rol)) {
            $SQL = "SELECT r.id, r.nombre_rol, 1 as asignado
                FROM roles r
                WHERE r.id = '$id_rol'
                UNION
                SELECT r.id, r.nombre_rol, 0 as asignado
                FROM roles r
                WHERE r.id != '$id_rol'
                ORDER BY nombre_rol";
        } elseif (!empty($id_usuario)) {
            $SQL = "SELECT r.id, r.nombre_rol, 
                CASE WHEN ur.id_rol IS NOT NULL THEN 1 ELSE 0 END as asignado
                FROM roles r
                LEFT JOIN usuariosroles ur ON r.id = ur.id_rol AND ur.id_usuario = '$id_usuario'
                ORDER BY nombre_rol";
        } else {
            return [];
        }
    
        return $this->DAO->consultar($SQL);
    }
}
?>

