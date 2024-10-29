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

function obtenerVista_EditarCrear(controlador, metodo, destino, id) {

    let opciones = {method: "GET",};
    let parametros = "controlador=" + controlador + "&metodo=" + metodo + "&id=" + id;

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
        // cargarUnScript('js/'+controlador+'.js');
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
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