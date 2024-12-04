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
        if($id!=''){
            $SQL.=" AND id='$id' ";
        }

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
        // Default values for all fields
        $id = '';
        $label = '';
        $url = '';
        $parent_id = '';
        $position = '';
        $level = '';
        $is_active = '';
        $action = '';
        $isAbove = false; // Default value for position logic
        $isEdit = false;  // Default value for edit status
        
        // Extract provided data into the variables
        extract($datos);
        
        // Check if 'isAbove' and 'isEdit' are passed and set them accordingly
        if (isset($datos['isAbove'])) {
            $isAbove = $datos['isAbove'];
        }
        
        if (isset($datos['isEdit'])) {
            $isEdit = $datos['isEdit'];
        }
        
        // If it's an edit (ID exists), perform an update
        if ($isEdit && !empty($id)) {
            // Update query if ID exists
            $SQL = "UPDATE menu SET
                    label='$label',
                    url='$url',
                    parent_id='$parent_id',
                    position='$position',
                    level='$level',
                    is_active='$is_active',
                    action='$action'
                    WHERE id='$id'";
            return $this->DAO->actualizar($SQL);
        } else {
            // Insert query when ID doesn't exist or it's a new menu item
            
            // If the position is 'above', set the position to the invoking item's position
            if ($isAbove) {
                // Position remains the same as the invoking item
                $SQL = "INSERT INTO menu SET
                        label='$label',
                        url='$url',
                        parent_id='$parent_id',
                        position='$position',
                        level='$level',
                        is_active='$is_active',
                        action='$action'";
            } else {
                // If the item is being added below, increment the position
                $SQL = "INSERT INTO menu SET
                        label='$label',
                        url='$url',
                        parent_id='$parent_id',
                        position=position + 1,   -- This will add the item below the invoking item
                        level='$level',
                        is_active='$is_active',
                        action='$action'";
            }
            return $this->DAO->insertar($SQL);
        }
    }   
    
        
    public function actualizarPosiciones($nivel, $posicion) {
        $SQL = "UPDATE menu SET position = position + 1 WHERE level = $nivel AND position >= $posicion";
        $this->DAO->actualizar($SQL);
    }    

    public function obtenerUltimaPosicion($parentId) {
        $SQL = "SELECT MAX(position) AS ultima_posicion FROM menu WHERE parent_id = $parentId";
        $resultado = $this->DAO->consultar($SQL);
    
        return isset($resultado[0]['ultima_posicion']) ? (int)$resultado[0]['ultima_posicion'] : 0;
    }
    
}
?>