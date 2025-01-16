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

function obtenerVista_EditarCrear(controlador, metodo, destino, id) {
    let parametros = "controlador=" + controlador + "&metodo=" + metodo + "&id=" + id; // Add '=' after id
    let opciones = { method: 'GET' };

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


function toggleOptions(menuId) {
    const options = document.getElementById(`options-${menuId}`);
    const allOptions = document.querySelectorAll('.menu-options');
    allOptions.forEach(opt => {
        if (opt !== options) opt.style.display = "none"; // Oculta otras opciones
    });
    options.style.display = options.style.display === "none" ? "block" : "none";
}

function guardarPermiso() {
    // 1. Validar campos del formulario
    let permiso        = document.getElementById('permiso').value;
    let id_menu        = document.getElementById('id_menu').value;
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

    // Si hay errores, los mostramos y detenemos la ejecución
    if (errores.length > 0) {
        alert(errores.join("\n")); // O bien, tu contenedor de errores
        return;
    }

    // 2. Construir los parámetros para la petición
    let parametros = "controlador=Permisos&metodo=guardarPermiso";
    let opciones = { method: 'GET' };

    // Obtenemos todos los datos del formulario
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById('formularioPermiso'))).toString();

    // 3. Hacemos la petición al servidor
    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => {
            if (res.ok) {
                // Se espera una respuesta en JSON (por el echo json_encode en el controlador)
                return res.json();
            }
            throw new Error(res.status);
        })
        .then(resultado => {
            if (resultado.correcto === 'S') {
                // Si se guardó correctamente, mostramos el mensaje o refrescamos la vista
                alert(resultado.msj); 
                // Por ejemplo, podrías recargar la lista de permisos o cerrar un modal
                document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
            } else {
                // Mostramos el mensaje de error que nos vino del servidor
                alert(resultado.msj);
            }
        })
        .catch(err => {
            console.error("Error al guardar el permiso:", err.message);
        });
}