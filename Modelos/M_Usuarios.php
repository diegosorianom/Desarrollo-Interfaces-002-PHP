<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';
class M_Usuarios extends Modelo {
    public $DAO;


    public function __construct() {
        parent::__construct();  // Ejecutar constructor
        $this->DAO = new DAO();
    }

    public function buscarUsuarios($filtros = array()) {
        $ftexto='';
        $activo='';
        extract($filtros);

        $SQL = "SELECT * 
                    FROM usuarios 
                    WHERE 1=1";

        if ($ftexto!='') {
            $aPalabras = explode(' ', $ftexto);
            
            $SQL.=" AND (nombre LIKE '%$ftexto%'
                        OR apellido_1 LIKE '%$ftexto%'
                        OR apellido_2 LIKE '%$ftexto%' ) ";
        }

        if ($activo!='') {
            $SQL." AND activo='$activo' ";
        }

        $usuarios = $this -> DAO -> consultar($SQL);

        return $usuarios;
    }
}
?>