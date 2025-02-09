<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Roles extends Modelo {
    public $DAO;

    public function __construct() {
        parent::__construct();  // Ejecutar constructor del padre
        $this->DAO = new DAO();
    }

    public function buscarRoles($filtros = array()) {
        $id_Rol = '';
        $nombre = '';
        extract($filtros);

        $SQL = "SELECT * FROM roles WHERE 1=1";

        if (!empty($id_Rol)) {
            $SQL .= " AND id_Rol = '$id_Rol'";
        }

        if (!empty($nombre)) {
            $SQL .= " AND nombre LIKE '%$nombre%'";
        }

        $SQL .= " ORDER BY nombre"; // Ordenar por nombre

        return $this->DAO->consultar($SQL); // Devuelve los roles desde la base de datos
    }

    public function insertarRol($datos = array()) {
        $id_Rol = '';
        $nombre = '';
        $descripcion = '';
        extract($datos);

        if (!empty($id_Rol)) {
            // Actualizar rol existente
            $SQL = "UPDATE roles SET 
                        nombre = '$nombre',
                        descripcion = '$descripcion'
                    WHERE id_Rol = '$id_Rol'";
            return $this->DAO->actualizar($SQL);
        } else {
            // Insertar nuevo rol
            $SQL = "INSERT INTO roles SET 
                        nombre = '$nombre',
                        descripcion = '$descripcion'";
            return $this->DAO->insertar($SQL);
        }
    }

    public function isNombreEnUso($nombre, $excludeRoleId = null) {
        $SQL = "SELECT id_Rol FROM roles WHERE nombre = '$nombre'";

        // Excluir un rol específico en caso de edición
        if (!empty($excludeRoleId)) {
            $SQL .= " AND id_Rol != '$excludeRoleId'";
        }

        $result = $this->DAO->consultar($SQL);
        return !empty($result); // Devuelve true si el nombre está en uso, false en caso contrario
    }
}
?>
