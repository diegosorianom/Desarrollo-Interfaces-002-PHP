function cargarUnScript(url) {
    let script = document.createElement('script');
    script.src = url;
    document.head.appendChild(script);
}

// Aqui es donde crearemos las funciones básicas
function obtenerVista(controlador, metodo, destino) {
    let parametros = "controlador=" + controlador + "&metodo=" + metodo
    let opciones = {method: 'GET',}

    fetch("C_Frontal.php?" + parametros, opciones) 
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById(destino).innerHTML = vista;
            cargarUnScript('js/' + controlador + '.js');
        })
        .catch(error => {
            console.log("Error al pedir vista", error.message); 
        });
}

function obtenerVista_EditarCrear(controlador, metodo, destino, id = null, id_menu = null) {
    let parametros = new URLSearchParams();
    parametros.append('controlador', controlador);
    parametros.append('metodo', metodo);

    if (id) {
        parametros.append('id', id);
    }
    if (id_menu) {
        parametros.append('id_menu', id_menu);
    }

    fetch("C_Frontal.php?" + parametros.toString(), { method: 'GET' })
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById(destino).innerHTML = vista;
        })
        .catch(error => {
            console.log("Error loading view", error.message);
        });
}



function añadirMenu(menuId, positionType) {
    let parametros = new URLSearchParams();
    parametros.append('controlador', 'Menu');
    parametros.append('metodo', 'getVistaNuevoEditar');
    parametros.append('menu_id', menuId);
    parametros.append('position_type', positionType);  // Parámetro para indicar que se agregará arriba
    
    let opciones = { method: 'GET' };

    fetch("C_Frontal.php?" + parametros.toString(), opciones)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById('capaEditarCrear').innerHTML = vista;
        })
        .catch(error => {
            console.log(`Error al añadir el menú ${positionType}`, error.message);
        });
}

function añadirHijo(menuId) {
    let parametros = new URLSearchParams();
    parametros.append('controlador', 'Menu');
    parametros.append('metodo', 'getVistaNuevoEditar');
    parametros.append('menu_id', menuId);
    parametros.append('position_type', 'child'); // Especificar que se agregará un hijo

    let opciones = { method: 'GET' };

    fetch("C_Frontal.php?" + parametros.toString(), opciones)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById('capaEditarCrear').innerHTML = vista;
        })
        .catch(error => {
            console.log("Error al añadir hijo", error.message);
        });
}


// Aqui es donde crearemos las funciones básicas
function buscar(controlador, metodo, formulario, destino) {    
    let parametros = "controlador=" + controlador + "&metodo=" + metodo; // Fixed the parameter formatting
    let opciones = { method: 'GET' };
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById(formulario))).toString();

    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById(destino).innerHTML = vista;
        })
        .catch(error => {
            console.log("Error al pedir vista", error.message); // Fixed 'err' to 'error'
        });
}

// Mantén un registro de los permisos seleccionados
const permisosSeleccionados = new Map();

function mostrarPermiso(checkbox) {
    const permisoId = checkbox.getAttribute("data-id");
    const permisoNombre = checkbox.value;
    const permisoCodigo = checkbox.getAttribute("data-codigo");

    if (checkbox.checked) {
        // Agregar permiso al registro
        permisosSeleccionados.set(permisoId, {
            id: permisoId,
            nombre: permisoNombre,
            codigo: permisoCodigo,
        });
    } else {
        // Eliminar permiso del registro
        permisosSeleccionados.delete(permisoId);
    }

    // Mostrar el estado actualizado en la consola
    console.log("Permisos seleccionados:", Array.from(permisosSeleccionados.values()));
}



function buscarConsola(controlador, metodo, formulario, destino) {
    // 1. Obtenemos el valor de los dropdown
    const rolSeleccionado = document.getElementById('frol').value;
    const usuarioSeleccionado = document.getElementById('fusuario').value;

    // 2. Mostramos los valores por consola
    console.log("Rol seleccionado: ", rolSeleccionado);
    console.log("Usuario seleccionado: ", usuarioSeleccionado);

    // 3. Repetimos la misma lógica de tu función buscar original
    let parametros = "controlador=" + controlador + "&metodo=" + metodo;
    let opciones = { method: 'GET' };
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById(formulario))).toString();

    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById(destino).innerHTML = vista;
        })
        .catch(error => {
            console.log("Error al pedir vista", error.message);
        });
}

function toggleChildren(menuId, event) {
    event.stopPropagation(); // Evitar activar el clic en la fila
    const children = document.getElementById(`children-${menuId}`);
    const arrow = event.target.closest('.arrow').querySelector('i'); // Busca el ícono dentro del contenedor de flecha

    if (children.style.display === "none") {
        children.style.display = "block"; // Mostrar hijos
        arrow.classList.remove('fa-chevron-right');
        arrow.classList.add('fa-chevron-down');
    } else {
        children.style.display = "none"; // Ocultar hijos
        arrow.classList.remove('fa-chevron-down');
        arrow.classList.add('fa-chevron-right');
    }
}

function mostrarSeleccion() {
    const seleccion = document.getElementById('frol').value;
    console.log('Rol seleccionado:', seleccion);
}

function mostrarSeleccionUsuario() {
    const usuario = document.getElementById('fusuario').value;
    console.log('Usuario seleccionado:', usuario);
}

function toggleOptions(menuId) {
    const options = document.getElementById(`options-${menuId}`);
    const allOptions = document.querySelectorAll('.menu-options');
    allOptions.forEach(opt => {
        if (opt !== options) opt.style.display = "none"; // Oculta otras opciones
    });
    options.style.display = options.style.display === "none" ? "block" : "none";
}

function guardarPermiso() {
    // 1. Validate form fields
    let permiso = document.getElementById('permiso').value;
    let id_menu = document.getElementById('id_menu').value;
    let codigo_permiso = document.getElementById('codigo_permiso').value;
    let errores = [];

    if (!permiso) {
        errores.push("El campo 'Permiso' es obligatorio.");
    }
    if (!id_menu) {
        errores.push("El campo 'Menú ID' es obligatorio.");
    }
    if (!codigo_permiso) {
        errores.push("El campo 'Código' es obligatorio.");
    }

    if (errores.length > 0) {
        alert(errores.join("\n"));
        return;
    }

    // 2. Build the parameters for the request
    let parametros = new URLSearchParams(new FormData(document.getElementById('formularioPermiso'))).toString();
    let opciones = { method: 'GET' };

    // 3. Send the request to the server
    fetch("C_Frontal.php?controlador=Permisos&metodo=guardarPermiso&" + parametros, opciones)
        .then(res => {
            if (res.ok) {
                return res.json(); // Parse the response as JSON
            }
            throw new Error("Error al procesar la respuesta del servidor."); // Handle non-2xx HTTP responses
        })
        .then(resultado => {
            if (resultado.correcto === 'S') {
                alert(resultado.msj); // Show success message
                
                // Clear the editing/creating view
                document.getElementById('capaEditarCrear').innerHTML = '';

                // Reload the list view
                obtenerVista('Permisos', 'getVistaListado', 'capaListado');
            } else {
                alert(resultado.msj); // Show the error message from the backend
            }
        })
        .catch(err => {
            console.error("Error al guardar el permiso:", err.message);
            alert("Error inesperado al guardar el permiso. Por favor, intenta nuevamente.");
        });
}

function eliminarPermiso(id) {
    if (!confirm("¿Estás seguro de que deseas eliminar este permiso?")) {
        return; // Exit if the user cancels the confirmation dialog
    }

    let parametros = new URLSearchParams();
    parametros.append('controlador', 'Permisos');
    parametros.append('metodo', 'eliminarPermiso');
    parametros.append('id', id);

    fetch("C_Frontal.php?" + parametros.toString(), { method: 'GET' })
        .then(res => {
            if (res.ok) {
                return res.json();
            }
            throw new Error("Error al procesar la respuesta del servidor.");
        })
        .then(resultado => {
            if (resultado.correcto === 'S') {
                alert(resultado.msj);
                // Reload the list view
                obtenerVista('Permisos', 'getVistaListado', 'capaListado');
            } else {
                alert(resultado.msj);
            }
        })
        .catch(err => {
            console.error("Error al eliminar el permiso:", err.message);
            alert("Error inesperado al eliminar el permiso. Por favor, intenta nuevamente.");
        });
}