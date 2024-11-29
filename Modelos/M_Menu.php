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

    public function insertarMenu($datos = array()) {
        $label = isset($datos['label']) ? "'" . addslashes($datos['label']) . "'" : "NULL";
        $url = isset($datos['url']) ? "'" . addslashes($datos['url']) . "'" : "NULL";
        $parent_id = isset($datos['parent_id']) && $datos['parent_id'] !== '' ? intval($datos['parent_id']) : "NULL";
        $position = isset($datos['position']) ? intval($datos['position']) : "NULL";
        $level = isset($datos['level']) ? intval($datos['level']) : "NULL";
        $is_active = isset($datos['is_active']) ? intval($datos['is_active']) : "NULL";
        $action = isset($datos['action']) ? "'" . addslashes($datos['action']) . "'" : "NULL";
    
        $SQL = "INSERT INTO menu SET
                label=$label,
                url=$url,
                parent_id=$parent_id,
                position=$position,
                level=$level,
                is_active=$is_active,
                action=$action";
        return $this->DAO->insertar($SQL);
    }
    

    public function editarMenu($datos = array()) {
        // Validar que $datos sea un arreglo y contenga el ID
        if (!is_array($datos) || empty($datos['id'])) {
            throw new InvalidArgumentException("Datos inválidos o faltan el ID para editar.");
        }
    
        // Validación y saneamiento de los campos
        $id = intval($datos['id']); // El ID siempre debe ser un entero
        $label = isset($datos['label']) ? "'" . addslashes($datos['label']) . "'" : "NULL";
        $url = isset($datos['url']) ? "'" . addslashes($datos['url']) . "'" : "NULL";
        $parent_id = isset($datos['parent_id']) && $datos['parent_id'] !== '' ? intval($datos['parent_id']) : "NULL";
        $position = isset($datos['position']) ? intval($datos['position']) : "NULL";
        $level = isset($datos['level']) ? intval($datos['level']) : "NULL";
        $is_active = isset($datos['is_active']) ? intval($datos['is_active']) : "NULL";
        $action = isset($datos['action']) ? "'" . addslashes($datos['action']) . "'" : "NULL";
    
        // Crear la consulta SQL
        $SQL = "UPDATE menu SET
                    label=$label,
                    url=$url,
                    parent_id=$parent_id,
                    position=$position,
                    level=$level,
                    is_active=$is_active,
                    action=$action
                WHERE id=$id";
    
        // Ejecutar la consulta y devolver el resultado
        return $this->DAO->actualizar($SQL);
    }
    
}
?>