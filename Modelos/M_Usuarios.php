<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';
class M_Usuarios extends Modelo {
    public $DAO;
    
    public function __construct() {
        parent::__construct();  // Ejecutar constructor
        $this->DAO = new DAO();
    }

    public function login($datos = array()){
        $usuario = '';
        $pass = '';
        extract($datos);
        $usuario = addslashes($usuario);
    
        $SQL = "SELECT * FROM usuarios
                WHERE login='$usuario' AND pass=MD5('$pass')";
        $usuarios = $this->DAO->consultar($SQL);
        $id_Usuario = '';
    
        if (!empty($usuarios)) { 
            // Usuario encontrado
            $_SESSION['login'] = $usuario;
            $_SESSION['usuario'] = $usuarios[0]['nombre'];
            $_SESSION['id_Usuario'] = $usuarios[0]['id_Usuario'];
            $id_Usuario = $usuarios[0]['id_Usuario']; 
    
            // 🔹 Obtener los roles del usuario con nombre
            $_SESSION['roles'] = $this->obtenerRolesUsuario($id_Usuario);
    
            // 🔹 Obtener los permisos del usuario
            $_SESSION['permisos'] = $this->obtenerPermisosUsuario($id_Usuario);
    
            // 🔹 Registrar en logs para depuración
            error_log("Usuario ID: $id_Usuario, Roles: " . json_encode($_SESSION['roles']) . ", Permisos: " . json_encode($_SESSION['permisos']));
        }
        return $id_Usuario;
    }
    
    public function obtenerPermisosUsuario($id_Usuario) {
        $permisos = [];
    
        // 🔹 Obtener permisos directos del usuario desde permisos_usuarios
        $SQL = "SELECT id_permiso FROM permisos_usuarios WHERE id_usuario = '$id_Usuario'";
        $resultado = $this->DAO->consultar($SQL);
        foreach ($resultado as $permiso) {
            $permisos[] = $permiso['id_permiso'];
        }
    
        // 🔹 Obtener los roles del usuario
        $roles = $this->obtenerRolesUsuario($id_Usuario); 
    
        // 🔹 Convertir el array de roles en una lista de valores separados por comas para SQL
        $rolesIds = array_column($roles, 'id_rol'); // Extraer solo los IDs de los roles
        if (!empty($rolesIds)) {
            $rolesString = implode(',', $rolesIds); // Convertir array a string separado por comas
    
            // 🔹 Obtener permisos heredados a través de roles desde permisos_roles
            $SQL = "SELECT DISTINCT id_permiso FROM permisos_roles WHERE id_rol IN ($rolesString)";
            $resultado = $this->DAO->consultar($SQL);
            foreach ($resultado as $permiso) {
                $permisos[] = $permiso['id_permiso'];
            }
        }
    
        // 🔹 Agregar permiso de visitante si no está ya incluido
        $permisoVisitante = 1; // Cambia esto si el ID del permiso de visitante es diferente
        if (!in_array($permisoVisitante, $permisos)) {
            $permisos[] = $permisoVisitante; 
        }
    
        // 🔹 Devolver los permisos sin duplicados
        return array_unique($permisos);
    }
    
    

    public function obtenerRolesUsuario($id_Usuario) {
        $roles = [];
    
        // Corrección: Usamos 'id' en lugar de 'id_rol' en la tabla 'roles'
        $SQL = "SELECT ru.id_rol, r.nombre 
                FROM roles_usuarios ru
                JOIN roles r ON ru.id_rol = r.id
                WHERE ru.id_usuario = '$id_Usuario'";
    
        $resultado = $this->DAO->consultar($SQL);
    
        foreach ($resultado as $rol) {
            $roles[] = [
                'id_rol' => $rol['id_rol'], // Este viene de roles_usuarios
                'nombre' => $rol['nombre']   // Este viene de roles
            ];
        }
    
        return $roles;
    }
    
    
    

    public function buscarUsuarios($filtros=array()){
        $ftexto = '';
        $factivo = '';
        $id_Usuario = '';
        $login = '';
        extract($filtros);

        $SQL = 'SELECT * FROM usuarios WHERE 1=1 ';

        if($ftexto != '') {

            $aPalabras = explode(' ', $ftexto);  // Divide en palabras el texto introducido por el usuario cuando haya un espacio, ' '
            $SQL .= ' AND ( 1=2 ';
            foreach($aPalabras as $palabra) {
                $SQL .= ' OR nombre LIKE "%'.$palabra.'%" 
                          OR apellido_1 LIKE "%'.$palabra.'%" 
                          OR apellido_2 LIKE "%'.$palabra.'%"
                          OR mail LIKE "%'.$palabra.'%" 
                          OR login LIKE "%'.$palabra.'%" ';  // El .= es concatenar, como un +
                                                             // El % es que contenga ese texto
                                                             // Se hacen los OR para que lo que queramos buscar se encuentre en el nombre, en el primer apellido o en el segundo, o en el mail o en el login
            }
            $SQL .= ')';
        }

        if($factivo != '') {
            $SQL .= ' AND activo = "'.$factivo.'" ';
        }

        if($id_Usuario != '') {
            $SQL .= ' AND id_Usuario = "'.$id_Usuario.'" ';
        }

        if($login != ''){
            $SQL .= " AND login = '$login' ";
        }

        $SQL .= ' ORDER BY apellido_1, apellido_2, nombre, login '; // Ordenar alfabéticamente los usuarios
        
        return $this->DAO->consultar($SQL);   // Devuelve los usuarios desde base de datos
    }

    public function insertarUsuario($datos = array()) {
        $id_Usuario = '';
        $nombre = '';
        $apellido_1 = '';     
        $apellido_2 = '';
        $sexo = '';
        $fecha_alta = date('Y-m-d');
        $mail = '';
        $movil = '';
        $login = 'duiehdiew';
        $pass = 'euhuwejdod';
        $activo = 'S';
        extract($datos);

        $pass = md5($pass);

        if(!empty($id_Usuario)) {
            $SQL = "UPDATE usuarios SET
                        nombre = '$nombre',
                        apellido_1 = '$apellido_1',
                        apellido_2 = '$apellido_2',
                        sexo = '$sexo',
                        fecha_alta = '$fecha_alta',
                        mail = '$mail',
                        movil = '$movil',
                        login = '$login',
                        pass = '$pass',
                        activo = '$activo'
                    WHERE id_Usuario = '$id_Usuario'";

            return $this -> DAO -> actualizar($SQL);
        } else {
            $SQL = "INSERT INTO usuarios SET 
                        nombre = '$nombre',
                        apellido_1 = '$apellido_1',
                        apellido_2 = '$apellido_2',
                        sexo = '$sexo',
                        fecha_alta = '$fecha_alta',
                        mail = '$mail',
                        movil = '$movil',
                        login = '$login',
                        pass = '$pass',
                        activo = '$activo'";

            return $this->DAO->insertar($SQL);
        }
    }

    //Función para validar el usuario del botón de login
    public function validarUsuario($login, $pass) {
        $pass = MD5($pass); // Asegúrate de cifrar la contraseña
    
        $SQL = "SELECT * FROM usuarios WHERE login='$login' AND pass='$pass'";
        $result = $this->DAO->consultar($SQL);
    
        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }    

    public function cambiarEstado($id_Usuario) {
        $SQL = "SELECT activo FROM usuarios WHERE id_Usuario = '$id_Usuario'";
        $resultado = $this -> DAO -> consultar($SQL);

        if (!empty($resultado)) {
            $nuevoEstado = ($resultado[0]['activo'] == 'S') ? 'N' : 'S';
            $SQL = "UPDATE usuarios SET activo = '$nuevoEstado' WHERE id_Usuario = '$id_Usuario'";
            $this->DAO->actualizar($SQL);

            return $nuevoEstado;
        }
        return false;
    }

    public function isEmailInUse($email, $excludeUserId = null) {
        $SQL = "SELECT id_Usuario FROM usuarios WHERE mail = '$email'";
        
        // If we need to exclude a user from the check (useful for updates)
        if (!empty($excludeUserId)) {
            $SQL .= " AND id_Usuario != '$excludeUserId'";
        }
    
        $result = $this->DAO->consultar($SQL);
        return !empty($result); // Returns true if email is in use, false otherwise
    }
    
    public function obtenerPermisosDirectosUsuario($id_Usuario) {
        $permisos = [];
    
        // Obtener permisos directos del usuario desde permisos_usuarios
        $SQL = "SELECT p.id, p.nombre 
                FROM permisos_usuarios pu
                JOIN permisos p ON pu.id_permiso = p.id
                WHERE pu.id_usuario = '$id_Usuario'";
        
        $resultado = $this->DAO->consultar($SQL);
        
        foreach ($resultado as $permiso) {
            $permisos[$permiso['id']] = $permiso; // Guardar en un array con clave única
        }
    
        return $permisos;
    }
    
}
?>