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

    public function guardarPermiso($datos) {
        // Extraemos los valores del array $datos
        $id             = $datos['id']             ?? '';
        $permiso        = $datos['permiso']        ?? '';
        $id_menu        = $datos['id_menu']        ?? '';
        $codigo_permiso = $datos['codigo_permiso'] ?? '';

        // Si $id está vacío, es un INSERT; si no, es un UPDATE.
        if ($id === '') {
            // Insertar
            $SQL = "INSERT INTO permisos (permiso, id_menu, codigo_permiso)
                    VALUES ('$permiso', '$id_menu', '$codigo_permiso')";
        } else {
            // Actualizar
            $SQL = "UPDATE permisos
                    SET permiso        = '$permiso',
                        id_menu        = '$id_menu',
                        codigo_permiso = '$codigo_permiso'
                    WHERE id = '$id'";
        }

        return $this->DAO->insertar($SQL);
    }    
}
