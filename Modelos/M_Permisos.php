<?php 
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Permisos {
    public $DAO;

    public function __construct() {
        $this->DAO = new DAO();
    }

    // Obtener todos los permisos disponibles
    public function getPermisos() {
        $SQL = "SELECT id, nombre, id_menu FROM permisos";
        return $this->DAO->consultar($SQL);
    }

    // Obtener permisos asignados a un rol
    public function getPermisosAsignados($idRol) {
        $SQL = "SELECT id_permiso FROM permisos_roles WHERE id_rol = '$idRol'";
        $result = $this->DAO->consultar($SQL);
        return array_column($result, 'id_permiso');
    }

    // Obtener permisos asignados a un usuario
    public function getPermisosAsignadosUsuario($idUsuario) {
        $SQL = "SELECT id_permiso FROM permisos_usuarios WHERE id_usuario = '$idUsuario'";
        $result = $this->DAO->consultar($SQL);
        return array_column($result, 'id_permiso');
    }

    // Obtener los permisos que un usuario hereda por pertenecer a un rol
    public function getPermisosHeredadosPorRol($idUsuario) {
        $SQL = "SELECT pr.id_permiso, pr.id_rol, r.nombre AS nombre
                FROM permisos_roles pr
                INNER JOIN roles_usuarios ur ON pr.id_rol = ur.id_rol
                INNER JOIN roles r ON r.id = pr.id_rol
                WHERE ur.id_usuario = '" . addslashes($idUsuario) . "'";
        $result = $this->DAO->consultar($SQL);
        // Devuelve un array de arrays asociativos, cada uno con 'id_permiso', 'id_rol' y 'rol_nombre'
        return $result;
    }
    
    // Asignar un permiso a un rol
    public function asignarPermisoRol($idRol, $idPermiso) {
        $SQL = "INSERT INTO permisos_roles (id_rol, id_permiso) VALUES ('$idRol', '$idPermiso')";
        return $this->DAO->insertar($SQL);
    }

    // Eliminar un permiso de un rol
    public function removerPermisoRol($idRol, $idPermiso) {
        $SQL = "DELETE FROM permisos_roles WHERE id_rol = '$idRol' AND id_permiso = '$idPermiso'";
        return $this->DAO->actualizar($SQL);
    }

    // Asignar un permiso a un usuario
    public function asignarPermisoUsuario($idUsuario, $idPermiso) {
        $SQL = "INSERT INTO permisos_usuarios (id_usuario, id_permiso) VALUES ('$idUsuario', '$idPermiso')";
        return $this->DAO->insertar($SQL);
    }

    // Eliminar un permiso de un usuario
    public function removerPermisoUsuario($idUsuario, $idPermiso) {
        $SQL = "DELETE FROM permisos_usuarios WHERE id_usuario = '$idUsuario' AND id_permiso = '$idPermiso'";
        return $this->DAO->actualizar($SQL);
    }

    // Actualizar el nombre del permiso
    public function actualizarNombrePermiso($id_permiso, $nuevo_nombre) {
        // Es recomendable sanitizar los datos o usar consultas preparadas
        $SQL = "UPDATE permisos SET nombre = '" . addslashes($nuevo_nombre) . "' WHERE id = '" . addslashes($id_permiso) . "'";
        return $this->DAO->actualizar($SQL);
    }

    // Eliminar permiso
    public function eliminarPermiso($id_permiso) {
        // Primero, eliminar registros en permisos_roles que referencian el permiso
        $SQL1 = "DELETE FROM permisos_roles WHERE id_permiso = '" . addslashes($id_permiso) . "'";
        $this->DAO->actualizar($SQL1);
        
        // Luego, eliminar el permiso de la tabla permisos
        $SQL2 = "DELETE FROM permisos WHERE id = '" . addslashes($id_permiso) . "'";
        return $this->DAO->actualizar($SQL2);
    }
    
    // Crear un permiso y a su vez añadirlo al rol administrador
    public function crearPermiso($nombre, $id_menu) {
        // Insertar el permiso en la tabla permisos
        $SQL = "INSERT INTO permisos (nombre, id_menu) VALUES ('" . addslashes($nombre) . "', '" . addslashes($id_menu) . "')";
        $idPermiso = $this->DAO->insertar($SQL); // Se obtiene el ID del nuevo permiso

        // Si se insertó correctamente, asignarlo al rol Administrador (id_rol = 1)
        if ($idPermiso) {
            $SQL2 = "INSERT INTO permisos_roles (id_rol, id_permiso) VALUES ('1', '$idPermiso')";
            $this->DAO->insertar($SQL2);
        }

        return $idPermiso;
    }

    // Final del modelo Permisos
}
?>
