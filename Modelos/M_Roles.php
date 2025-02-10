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
    

    public function asignarRolAUsuario($rolId, $usuarioId) {
        // Verificar si ya existe la relación
        $SQL = "SELECT * FROM roles_usuarios WHERE id_rol = '$rolId' AND id_usuario = '$usuarioId'";
        $existe = $this->DAO->consultar($SQL);
    
        if (!empty($existe)) {
            return "La relación ya existe"; // 🔹 Devolvemos un mensaje claro en lugar de `false`
        }
    
        // Insertar la nueva relación
        $SQL = "INSERT INTO roles_usuarios (id_rol, id_usuario) VALUES ('$rolId', '$usuarioId')";
        $resultado = $this->DAO->insertar($SQL);
    
        if ($resultado) {
            return "Rol asignado correctamente";
        } else {
            return "Error al insertar en la base de datos";
        }
    }
    
    
    
}
?>

