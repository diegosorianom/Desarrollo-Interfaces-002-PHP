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

    public function obtenerPermisosRol() {
        $id_rol = $_GET['id_rol'] ?? '';
        if (empty($id_rol)) {
            echo json_encode([]);
            return;
        }

        $permisos = $this->modelo->obtenerPermisosRol($id_rol);
        echo json_encode($permisos);
    }

    public function obtenerRolesUsuario() {
        $id_rol = $_GET['id_rol'] ?? '';
        $id_usuario = $_GET['id_usuario'] ?? '';
    
        if (empty($id_rol) && empty($id_usuario)) {
            echo json_encode([]);
            return;
        }

        $roles = $this->modelo->obtenerRolesUsuario($id_rol, $id_usuario);
        echo json_encode($roles);
    }
}
?>

