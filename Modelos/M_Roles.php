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
        $id = '';
        $nombre = '';
        extract($filtros);

        $SQL = "SELECT * FROM roles WHERE 1=1";

        if (!empty($id)) {
            $SQL .= " AND id = '$id'";
        }

        if (!empty($nombre)) {
            $SQL .= " AND nombre LIKE '%$nombre%'";
        }

        $SQL .= " ORDER BY nombre"; // Ordenar por nombre

        return $this->DAO->consultar($SQL); // Devuelve los roles desde la base de datos
    }

    public function insertarRol($datos = array()) {
        $id = '';
        $nombre = '';
        extract($datos); // Extraer solo las variables disponibles
    
        if (!empty($id)) {
            // Actualizar rol existente
            $SQL = "UPDATE roles SET nombre = '$nombre' WHERE id = '$id'";
            return $this->DAO->actualizar($SQL);
        } else {
            // Insertar nuevo rol
            $SQL = "INSERT INTO roles (nombre) VALUES ('$nombre')";
            return $this->DAO->insertar($SQL);
        }
    }
    

    public function isNombreEnUso($nombre, $excludeRoleId = null) {
        $SQL = "SELECT id FROM roles WHERE nombre = '$nombre'";

        // Excluir un rol específico en caso de edición
        if (!empty($excludeRoleId)) {
            $SQL .= " AND id != '$excludeRoleId'";
        }

        $result = $this->DAO->consultar($SQL);
        return !empty($result); // Devuelve true si el nombre está en uso, false en caso contrario
    }
}
?>
