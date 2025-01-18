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

    // public function guardarPermiso($datos) {
    //     // Extraemos los valores del array $datos
    //     $id             = $datos['id']             ?? '';
    //     $permiso        = $datos['permiso']        ?? '';
    //     $id_menu        = $datos['id_menu']        ?? '';
    //     $codigo_permiso = $datos['codigo_permiso'] ?? '';

    //     // Si $id está vacío, es un INSERT; si no, es un UPDATE.
    //     if ($id === '') {
    //         // Insertar
    //         $SQL = "INSERT INTO permisos (permiso, id_menu, codigo_permiso)
    //                 VALUES ('$permiso', '$id_menu', '$codigo_permiso')";
    //     } else {
    //         // Actualizar
    //         $SQL = "UPDATE permisos
    //                 SET permiso        = '$permiso',
    //                     id_menu        = '$id_menu',
    //                     codigo_permiso = '$codigo_permiso'
    //                 WHERE id = '$id'";
    //     }

    //     return $this->DAO->insertar($SQL);
    // }    

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
    
    
}
