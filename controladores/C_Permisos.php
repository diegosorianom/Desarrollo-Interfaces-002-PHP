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

    public function getPermisosHeredadosUsuario($datos = array()) {
        if (!isset($datos['id_usuario'])) {
            echo json_encode(["error" => "Error CF-05: Datos insuficientes."]);
            exit;
        }
    
        $idUsuario = $datos['id_usuario'];
        $permisosHeredados = $this->permisoModel->getPermisosHeredadosPorRol($idUsuario);
    
        echo json_encode(["correcto" => "S", "permisos_heredados" => $permisosHeredados]);
        exit;
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

    // Nuevo método para actualizar el nombre de un permiso
    public function actualizarNombrePermiso($datos = array()) {
        if (!isset($datos['id_permiso']) || !isset($datos['nuevo_nombre'])) {
            echo "Error CF-03: Datos insuficientes.";
            exit;
        }
        $id_permiso = $datos['id_permiso'];
        $nuevo_nombre = $datos['nuevo_nombre'];

        $resultado = $this->permisoModel->actualizarNombrePermiso($id_permiso, $nuevo_nombre);
        // Si $resultado es false estrictamente (por error) o un número >= 0, se considera OK
        if ($resultado !== false) {
            echo "OK";
        } else {
            echo "Error CF-03: No se pudo actualizar el nombre.";
        }

        exit;
    }

    // Nuevo método para eliminar un permiso
    public function eliminarPermiso($datos = array()) {
        if (!isset($datos['id_permiso'])) {
            echo "Error CF-03: Datos insuficientes.";
            exit;
        }
        $id_permiso = $datos['id_permiso'];

        $resultado = $this->permisoModel->eliminarPermiso($id_permiso);
        if ($resultado) {
            echo "OK";
        } else {
            echo "Error CF-03: No se pudo eliminar el permiso.";
        }
        exit;
    }

    // Nuevo método para crear un permiso
public function crearPermiso($datos = array()) {
    if (!isset($datos['nombre']) || !isset($datos['id_menu'])) {
        echo "Error CF-04: Datos insuficientes.";
        exit;
    }
    $nombre = $datos['nombre'];
    $id_menu = $datos['id_menu'];

    $resultado = $this->permisoModel->crearPermiso($nombre, $id_menu);
    if ($resultado) {
        echo "OK";
    } else {
        echo "Error CF-04: No se pudo crear el permiso.";
    }
    exit;
}

}
?>
