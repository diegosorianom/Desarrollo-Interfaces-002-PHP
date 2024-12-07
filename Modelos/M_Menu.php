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
        // Valores por defecto para los campos
        $id = '';
        $label = '';
        $url = '';
        $parent_id = '';
        $position = '';
        $level = '';
        $is_active = '';
        $action = '';
    
        // Extraer los datos proporcionados
        extract($datos);
    
        // Si hay un ID, realizar una actualización
        if (!empty($id)) {
            // Actualizar consulta
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
            // Si es un nuevo menú, ajustar las posiciones y luego insertar el nuevo menú
            // Paso 1: Consultar la máxima posición actual en el mismo nivel y parent_id
            $SQL = "SELECT MAX(position) AS max_position FROM menu WHERE parent_id='$parent_id' AND level='$level'";
            $resultado = $this->DAO->consultar($SQL);
            $maxPosition = 0;
    
            if (!empty($resultado) && isset($resultado[0]['max_position'])) {
                $maxPosition = $resultado[0]['max_position'];
            }
    
            // Paso 2: Ajustar posiciones existentes
            if ($position > 0) {
                // Mover los menús existentes para hacer espacio al nuevo
                $SQL = "UPDATE menu 
                        SET position = position + 1 
                        WHERE parent_id='$parent_id' AND level='$level' AND position >= '$position'";
                $this->DAO->actualizar($SQL);
            } else {
                // Si no se proporciona posición o es inválida, colocar al final
                $position = $maxPosition + 1;
            }
    
            // Paso 3: Insertar el nuevo menú
            $SQL = "INSERT INTO menu SET
                    label='$label',
                    url='$url',
                    parent_id='$parent_id',
                    position='$position',
                    level='$level',
                    is_active='$is_active',
                    action='$action'";
            $insertId = $this->DAO->insertar($SQL);
    
            // Paso 4: Reordenar todas las posiciones en el mismo nivel para garantizar consistencia
            $SQL = "SET @row_number = 0;
                    UPDATE menu
                    SET position = (@row_number := @row_number + 1)
                    WHERE parent_id='$parent_id' AND level='$level'
                    ORDER BY position ASC;";
            $this->DAO->actualizar($SQL);
    
            return $insertId;
        }
    }

    
    
}
?>