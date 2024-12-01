function cargarUnScript(url) {
    let script = document.createElement('script');
    script.src = url;
    document.head.appendChild(script);
}

// Aqui es donde crearemos las funciones básicas
function obtenerVista(controlador, metodo, destino) {

    let opciones = {method: "GET",};
    let parametros = "controlador=" + controlador + "&metodo=" + metodo;

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
    .then(res => {
        // Nos devuelve si esta OK o no
        if (res.ok) {
            return res.text();
        }
        throw new Error(res.status);
    }) 
    .then(vista => {
        document.getElementById(destino).innerHTML = vista;
        cargarUnScript('js/'+controlador+'.js');
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
}

function obtenerVista_EditarCrear(controlador, metodo, destino, id = '', copy_from_id = '') {
    let opciones = { method: "GET" };
    let parametros = "controlador=" + controlador + "&metodo=" + metodo;

    if (id) {
        parametros += "&id=" + id;
    }

    if (copy_from_id) {
        parametros += "&copy_from_id=" + copy_from_id; // Enviar ID del menú base para "Añadir arriba"
    }

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
        .then(res => {
            // Nos devuelve si esta OK o no
            if (res.ok) {
                return res.text();
            }
            throw new Error(res.status);
        })
        .then(vista => {
            document.getElementById(destino).innerHTML = vista;
        })
        .catch(err => {
            console.log("Error al pedir vista", err.message);
        });
}




// Aqui es donde crearemos las funciones básicas
function buscar(controlador, metodo, formulario, destino) {

    let opciones = {method: "GET",};
    let parametros = "controlador=" + controlador + "&metodo=" + metodo;
    parametros += '&' + new URLSearchParams(new FormData(document.getElementById(formulario))).toString();

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
    .then(res => {
        // Nos devuelve si esta OK o no
        if (res.ok) {
            return res.text();
        }
        throw new Error(res.status);
    }) 
    .then(vista => {
        document.getElementById(destino).innerHTML = vista;
        
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
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
