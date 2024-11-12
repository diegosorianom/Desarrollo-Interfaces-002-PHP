<?php
require_once 'modelos/M_Menu.php';

class C_Menu {
    private $menuModel;

    public function __construct() {
        $this -> menuModel = new M_Menu();
    }

    public function getFormattedMenu() {
        return $this -> menuModel -> getMenuOptions();
    }
}
?>