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
}
?>