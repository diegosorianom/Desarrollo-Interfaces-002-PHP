<?php
require_once 'DAO.php';

class M_Roles {
    private $DAO;

    public function __construct() {
        $this->DAO = new DAO();
    }

    public function buscarRoles() {
        $SQL = "SELECT * FROM roles";
        return $this->DAO->consultar($SQL);
    }

    public function insertarActualizarRol($datos = array()) {
        $id = '';
        $nombre = '';
        extract($datos);

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

    public function eliminarRol($id) {
        $SQL = "DELETE FROM roles WHERE id = '$id'";
        return $this->DAO->borrar($SQL);
    }
}
?>

