<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Permisos extends Modelo {
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new DAO();
    }

    public function buscarPermisos($filtros = array()) {
        $id = '';
        $permiso = '';
        $id_menu = '';
        $codigo_permiso = '';
        extract($filtros);

        $SQL = 'SELECT * FROM permisos WHERE 1=1';

        if ($id != '') {
            $SQL .= " AND id = '$id'";
        }

        if ($permiso != '') {
            $SQL .= " AND permiso LIKE '%$permiso%'";
        }

        if ($id_menu != '') {
            $SQL .= " AND id_menu = '$id_menu'";
        }

        if ($codigo_permiso != '') {
            $SQL .= " AND codigo_permiso LIKE '%$codigo_permiso%'";
        }

        $SQL .= ' ORDER BY id';
        return $this->DAO->consultar($SQL);
    }

    public function insertarPermisoRol($id_rol, $id_permiso) {
        try {
            // Validar que el rol y el permiso existan
            $SQL_validacion = "SELECT 
                (SELECT COUNT(*) FROM roles WHERE id = '$id_rol') as rol_existe,
                (SELECT COUNT(*) FROM permisos WHERE id = '$id_permiso') as permiso_existe";
            
            $validacion = $this->DAO->consultar($SQL_validacion);
            
            if ($validacion[0]['rol_existe'] == 0) {
                throw new Exception("El rol especificado no existe");
            }
            
            if ($validacion[0]['permiso_existe'] == 0) {
                throw new Exception("El permiso especificado no existe");
            }
    
            // Verificar si ya existe la relación
            $SQL_existe = "SELECT COUNT(*) as existe FROM permisosroles 
                          WHERE id_rol = '$id_rol' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->consultar($SQL_existe);
            
            if ($resultado[0]['existe'] > 0) {
                return true; // Ya existe la relación
            }
    
            // Insertar la nueva relación
            $SQL = "INSERT INTO permisosroles (id_rol, id_permiso) 
                    VALUES ('$id_rol', '$id_permiso')";
            
            $resultado = $this->DAO->insertar($SQL);
            
            if ($resultado === false) {
                throw new Exception("Error al insertar en la base de datos");
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en insertarPermisoRol: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminarPermisoRol($id_rol, $id_permiso) {
        try {
            // Verificar que la relación exista antes de eliminar
            $SQL_existe = "SELECT COUNT(*) as existe FROM permisosroles 
                          WHERE id_rol = '$id_rol' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->consultar($SQL_existe);
            
            if ($resultado[0]['existe'] == 0) {
                return true; // No existe la relación, consideramos éxito
            }
    
            $SQL = "DELETE FROM permisosroles 
                    WHERE id_rol = '$id_rol' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->borrar($SQL);
            
            if ($resultado === false) {
                throw new Exception("Error al eliminar de la base de datos");
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en eliminarPermisoRol: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Método adicional para verificar permisos existentes
    public function obtenerPermisosRol($id_rol) {
        $SQL = "SELECT id_permiso FROM permisosroles WHERE id_rol = '$id_rol'";
        return $this->DAO->consultar($SQL);
    }

    public function insertarPermiso($datos) {
        // Extract values from the $datos array
        $permiso        = $datos['permiso']        ?? '';
        $id_menu        = $datos['id_menu']        ?? '';
        $codigo_permiso = $datos['codigo_permiso'] ?? '';
    
        // Construct the INSERT SQL query
        $SQL = "INSERT INTO permisos (permiso, id_menu, codigo_permiso)
                VALUES ('$permiso', '$id_menu', '$codigo_permiso')";
    
        // Execute and return the result
        return $this->DAO->insertar($SQL);
    }

    public function actualizarPermiso($datos) {
        // Extract values from the $datos array
        $id             = $datos['id']             ?? '';
        $permiso        = $datos['permiso']        ?? '';
        $id_menu        = $datos['id_menu']        ?? '';
        $codigo_permiso = $datos['codigo_permiso'] ?? '';
    
        // If no ID is provided, return false (invalid operation)
        if ($id === '') {
            return false;
        }
    
        // Construct the UPDATE SQL query
        $SQL = "UPDATE permisos
                SET permiso        = '$permiso',
                    id_menu        = '$id_menu',
                    codigo_permiso = '$codigo_permiso'
                WHERE id = '$id'";
    
        // Execute and return the result
        return $this->DAO->actualizar($SQL);
    }
    
    public function eliminarPermiso($id) {
        if (empty($id)) {
            return false; // Return false if no ID is provided
        }
    
        // Construct the DELETE SQL query
        $SQL = "DELETE FROM permisos WHERE id = '$id'";
    
        // Use the `borrar` method from the DAO to execute the query
        return $this->DAO->borrar($SQL) > 0;
    }
    
    
    public function obtenerPermisosUsuario($id_usuario) {
        $SQL = "SELECT p.id, p.permiso, p.codigo_permiso 
                FROM permisos p 
                INNER JOIN permisosusuario pu ON p.id = pu.id_permiso 
                WHERE pu.id_usuario = '$id_usuario'";
        return $this->DAO->consultar($SQL);
    }

    public function insertarPermisoUsuario($id_usuario, $id_permiso) {
        try {
            $SQL_validacion = "SELECT 
                (SELECT COUNT(*) FROM usuarios WHERE id_Usuario = '$id_usuario') as usuario_existe,
                (SELECT COUNT(*) FROM permisos WHERE id = '$id_permiso') as permiso_existe";
            
            $validacion = $this->DAO->consultar($SQL_validacion);
            
            if ($validacion[0]['usuario_existe'] == 0) {
                throw new Exception("El usuario especificado no existe");
            }
            
            if ($validacion[0]['permiso_existe'] == 0) {
                throw new Exception("El permiso especificado no existe");
            }

            $SQL_existe = "SELECT COUNT(*) as existe FROM permisosusuario 
                          WHERE id_usuario = '$id_usuario' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->consultar($SQL_existe);
            
            if ($resultado[0]['existe'] > 0) {
                return true; // Ya existe la relación
            }

            $SQL = "INSERT INTO permisosusuario (id_usuario, id_permiso) 
                    VALUES ('$id_usuario', '$id_permiso')";
            
            $resultado = $this->DAO->insertar($SQL);
            
            if ($resultado === false) {
                throw new Exception("Error al insertar en la base de datos");
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en insertarPermisoUsuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminarPermisoUsuario($id_usuario, $id_permiso) {
        try {
            $SQL_existe = "SELECT COUNT(*) as existe FROM permisosusuario 
                          WHERE id_usuario = '$id_usuario' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->consultar($SQL_existe);
            
            if ($resultado[0]['existe'] == 0) {
                return true; // No existe la relación, consideramos éxito
            }

            $SQL = "DELETE FROM permisosusuario
                    WHERE id_usuario = '$id_usuario' AND id_permiso = '$id_permiso'";
            
            $resultado = $this->DAO->borrar($SQL);
            
            if ($resultado === false) {
                throw new Exception("Error al eliminar de la base de datos");
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en eliminarPermisoUsuario: " . $e->getMessage());
            throw $e;
        }
    }
}

