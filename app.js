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

function buscarRol() {
    let selectRol = document.getElementById("frol");
    let selectedRoleId = selectRol ? selectRol.value : ""; 

    let selectUsuario = document.getElementById("fusuario");
    let selectedUserId = selectUsuario ? selectUsuario.value : ""; 

    console.log("🔍 ID del Rol seleccionado:", selectedRoleId);
    console.log("🔍 ID del Usuario seleccionado:", selectedUserId);

    let parametros = new URLSearchParams();
    parametros.append('controlador', 'Menu');
    parametros.append('metodo', 'getVistaListadoMenu');
    parametros.append('frol', selectedRoleId);
    parametros.append('fusuario', selectedUserId); 

    let opciones = { method: 'GET' };

    fetch("C_Frontal.php?" + parametros.toString(), opciones)
        .then(res => res.text())
        .then(vista => {
            document.getElementById("capaResultadoBusqueda").innerHTML = vista;
        })
        .catch(error => console.log("❌ Error al buscar menús y permisos:", error.message));
}

function togglePermiso(permisoId) {
    let checkbox = document.querySelector(`input[data-permiso-id="${permisoId}"]`);
    let seleccionado = checkbox.checked;

    let selectRol = document.getElementById("frol");
    let selectedRoleId = selectRol ? selectRol.value : ""; 

    let selectUsuario = document.getElementById("fusuario");
    let selectedUserId = selectUsuario ? selectUsuario.value : ""; 

    if (!selectedRoleId && !selectedUserId) {
        console.log("⚠️ No se seleccionó un rol ni un usuario.");
        return;
    }

    let parametros = new URLSearchParams();
    parametros.append('controlador', 'Permisos'); // Usamos el nuevo controlador
    parametros.append('metodo', 'actualizarPermiso');
    parametros.append('id_permiso', permisoId);
    parametros.append('asignado', seleccionado ? "1" : "0");

    if (selectedRoleId) {
        parametros.append('frol', selectedRoleId);
    }
    if (selectedUserId) {
        parametros.append('fusuario', selectedUserId);
    }

    console.log("🔄 Enviando solicitud de actualización:", parametros.toString());

    fetch("C_Frontal.php?" + parametros.toString(), { method: 'POST' })
        .then(res => res.text())
        .then(respuesta => console.log("🔄 Respuesta del servidor:", respuesta))
        .catch(error => console.log("❌ Error al actualizar permiso:", error.message));
}



function mostrarPermisoSeleccionado(permisoId) {
    console.log("🔹 Permiso seleccionado:", permisoId);
}
