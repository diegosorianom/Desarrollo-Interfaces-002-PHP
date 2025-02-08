<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Permisos.php';
require_once 'vistas/Vista.php';

class C_Permisos {
    private $permisoModel;

    public function __construct() {
        $this->permisoModel = new M_Permisos();
    }

    public function getListaPermisos() {
        return $this->permisoModel->getPermisos();
    }

    public function actualizarPermiso($datos = array()) {
        if (!isset($datos['id_permiso']) || !isset($datos['asignado'])) {
            echo "Error CF-01: Datos insuficientes.";
            exit;
        }

        $idPermiso = $datos['id_permiso'];
        $asignado = $datos['asignado'];
        $idRol = isset($datos['frol']) ? $datos['frol'] : null;
        $idUsuario = isset($datos['fusuario']) ? $datos['fusuario'] : null;

        if (!$idRol && !$idUsuario) {
            echo "Error CF-02: No se seleccionó rol ni usuario.";
            exit;
        }

        if ($idRol) {
            if ($asignado == "1") {
                $this->permisoModel->asignarPermisoRol($idRol, $idPermiso);
            } else {
                $this->permisoModel->removerPermisoRol($idRol, $idPermiso);
            }
        } elseif ($idUsuario) {
            if ($asignado == "1") {
                $this->permisoModel->asignarPermisoUsuario($idUsuario, $idPermiso);
            } else {
                $this->permisoModel->removerPermisoUsuario($idUsuario, $idPermiso);
            }
        }

        echo "OK";
        exit;
    }
}
?>
