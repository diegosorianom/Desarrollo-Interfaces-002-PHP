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
        $factivo='';
        extract($filtros);

        $SQL = "SELECT * 
                    FROM usuarios 
                    WHERE 1=1";

        if ($ftexto!='') {
            $aPalabras = explode(' ', $ftexto);
            
            foreach($aPalabras as $palabra) {
                $conditions[] = "(nombre LIKE '%$palabra%' OR apellido_1 LIKE '%$palabra%' OR apellido_2 LIKE '%$palabra%')";
            }

            $SQL .= " AND (" .implode(' OR ', $conditions) . ")";

            // $SQL.=" AND (nombre LIKE '%$ftexto%'
            //             OR apellido_1 LIKE '%$ftexto%'
            //             OR apellido_2 LIKE '%$ftexto%' ) ";
        }

        // if ($ftexto != ''){
        //     $aPalabras = explode(' ', $ftexto);
        //     $SQL.= ' AND ( 1=2 ';
        //     foreach($aPalabras as $palabra) {
        //         $SQL = " OR nombre LIKE '%$palabra'
        //                  OR apellido_1 LIKE '%$palabra'
        //                  OR apellido_2 LIKE '%$palabra'
        //                  OR mail LIKE '%$palabra'
        //                  OR login LIKE '%$palabra' ";
        //     }
        //     $SQL.= ' ) ';
        // }

        if ($factivo!='') {
            $SQL." AND activo='$factivo' ";
        }

        $SQL.=' ORDER BY apellido_1, apellido_2, nombre, login';

        $usuarios = $this -> DAO -> consultar($SQL);

        return $usuarios;
    }
}
?>