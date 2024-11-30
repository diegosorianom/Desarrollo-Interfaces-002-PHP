function guardarMenu() {
    console.log('Guardando...');

    const formData = new FormData(document.getElementById('formularioNuevoEditar'));
    if (!formData.get('label')) {
        document.getElementById('msjError').innerHTML = 'El campo Label es obligatorio.';
        return;
    }

    let opciones = { method: "GET" };
    let parametros = "controlador=Menu&metodo=guardarMenu";
    parametros += '&' + new URLSearchParams(formData).toString();

    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => res.text()) // Procesa como texto plano
        .then(texto => {
            console.log("Respuesta del servidor:", texto); // Muestra la respuesta en la consola
            if (texto.includes("Error")) {
                document.getElementById('msjError').innerHTML = texto; // Muestra el error
            } else {
                document.getElementById('capaEditarCrear').innerHTML = ''; // Limpia el formulario
                obtenerVista('Menu', 'getVistaListadoMenu', 'capaContenido');
                document.getElementById('msjError').innerHTML = texto; // Muestra el mensaje de éxito
            }
        })
        .catch(err => {
            console.error("Error al guardar menú:", err.message);
            document.getElementById('msjError').innerHTML = `Error inesperado: ${err.message}`;
        });
}

