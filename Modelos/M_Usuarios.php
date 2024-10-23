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
        $id_Usuario='';
        extract($filtros);

        $SQL = "SELECT * 
                    FROM usuarios 
                    WHERE 1=1";

        if ($id_Usuario != '') {
            $SQL.=" AND id_Usuario='$id_Usuario' ";
        }

        if ($ftexto!='') {
            $aPalabras = explode(' ', $ftexto);
            
            foreach($aPalabras as $palabra) {
                $conditions[] = "(nombre LIKE '%$palabra%' OR apellido_1 LIKE '%$palabra%' OR apellido_2 LIKE '%$palabra%')";
            }

            $SQL .= " AND (" .implode(' OR ', $conditions) . ")";
        }

        if ($factivo!='') {
            $SQL." AND activo='$factivo' ";
        }
        
        $SQL.=' ORDER BY apellido_1, apellido_2, nombre, login';

        $usuarios = $this -> DAO -> consultar($SQL);

        return $usuarios;
    }

    public function insertarUsuario($datos = Array()) {
        $nombre='';
        $apellido_1='';
        $apellido_2='';
        $sexo='H';
        $fecha_Alta=date('Y-m-d');
        $mail='';
        $movil='';
        $login='asdfasdf';
        $pass='asdfasdf';
        $activo='S';
        extract($datos);

        $pass = MD5($pass);

        $SQL = "INSERT INTO usuarios SET 
                    nombre='$nombre',
                    apellido_1='$apellido_1',
                    apellido_2='$apellido_2',
                    sexo='$sexo',
                    fecha_Alta='$fecha_Alta',
                    mail='$mail',
                    movil='$movil',
                    login='$login', 
                    pass='$pass',
                    activo='$activo' ";
        return $this -> DAO -> insertar($SQL);
        // si es de php a php --> return / si es de php a js --> echo
    }

    public function editarUsuarios($datos = Array()) {
        $nombre='';
        $apellido_1='';
        $apellido_2='';
        $sexo='H';
        $fecha_Alta=date('Y-m-d');
        $mail='';
        $movil='';
        $login='asdfasdf';
        $pass='asdfasdf';
        $activo='S';
        extract($datos);

        $pass = MD5($pass);

        $SQL = "INSERT INTO usuarios SET 
                    nombre='$nombre',
                    apellido_1='$apellido_1',
                    apellido_2='$apellido_2',
                    sexo='$sexo',
                    fecha_Alta='$fecha_Alta',
                    mail='$mail',
                    movil='$movil',
                    login='$login', 
                    pass='$pass',
                    activo='$activo' ";
        return $this -> DAO -> insertar($SQL);
    }
}
?>