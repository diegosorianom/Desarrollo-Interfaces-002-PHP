<?php 
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';
class M_Menu extends Modelo {
    public $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new DAO();
    }

    public function getMenuOptions() {
        // SQL para obtener todas las opciones del menu, ordenads por nivel y posición
        $sql = "SELECT * FROM menu WHERE is_active = 1 ORDER BY level, position";
        
        // Llamada al metodo 'consultar' del DAO para ejecutar la consulta
        try {
            $menuOptions = $this -> DAO -> consultar($sql);
        } catch (Exception $e) {
            // Manejo de error si falla la consulta
            echo "Error al obtener el menú: " . $e -> getMessage();
            return [];
        }

        return $this -> formatMenu($menuOptions);
    }

    public function buscarOpcionesMenu($filtros=array()){
        $ftexto="";
        // $factivo="";
        $id="";
        extract($filtros);
        
        
        $SQL="SELECT * FROM menu WHERE 1=1";
        

        if($ftexto!=""){
            $aPalabras=explode(' ',$ftexto);

            $SQL.=" AND 1 = 2";

            foreach($aPalabras as $word){
                    $SQL.=" OR (titulo LIKE '%$word%')";
            }
        }

        // if($factivo!=''){
        //     $SQL.=" AND activo='$factivo' ";
        // }
        if($id!=''){
            $SQL.=" AND id='$id' ";
        }

        // $SQL.=' ORDER BY id ';

        $opcionesMenu = $this->DAO->consultar($SQL);
        

        return $opcionesMenu;
    }

    private function formatMenu($menuOptions) {
        // Organiza las opciones en un arreglo jerarquico por niveles
        $menu = [];

        foreach ($menuOptions as $option) {
            if ($option['level'] == 1) {
                // Opciones de nivel 1 (menú principal)
                $menu[$option['id']] = $option;
                $menu[$option['id']]['submenus'] = [];
            } elseif ($option['level'] == 2 && isset ($menu[$option['parent_id']])) {
                // Opciones de nivel 2 (submenús), asociados a su opción de nivel 1
                $menu[$option['parent_id']]['submenus'][] = $option;
            }
        }

        return $menu;
    }

    public function editarMenu($datos = array()) {
        $id = '';
        $label = '';
        $url = '';
        $parent_id = '';
        $position = '';
        $level = '';
        $is_active = '';
        $action = '';
        extract($datos);

        $SQL ="UPDATE menu SET 
            titulo = '$label', 
            url = '$url',
            parent_id = '$parent_id',
            position = '$position', 
            level = '$level',
            is_active = '$is_active',
            $action = '$action'
            WHERE id = '$id'";
        return $this -> DAO -> actualizar($SQL);
    }
    
    public function insertarMenu($datos = array()) {
        $id = '';
        $label = '';
        $url = '';
        $parent_id = '';
        $position = '';
        $level = '';
        $is_active = '';
        $action = '';
        extract($datos);

        $SQL ="INSERT INTO menu SET 
            titulo = '$label', 
            url = '$url',
            parent_id = '$parent_id',
            position = '$position', 
            level = '$level',
            is_active = '$is_active',
            $action = '$action' ";
        return $this -> DAO -> insertar($SQL);
    }    
}
?>