function guardarMenu() {
    console.log('Guardando...');

    const form = document.getElementById('formularioNuevoEditar');
    const formData = new FormData(form);

    // Validación básica
    if (!formData.get('label')) {
        document.getElementById('msjError').innerHTML = 'El campo Label es obligatorio.';
        return;
    }

    // Configuración de la solicitud
    let opciones = {
        method: "POST",
        body: formData, // Enviar directamente el FormData
    };

    // Endpoint al que se enviará la solicitud
    let url = "C_Frontal.php?controlador=Menu&metodo=guardarMenu";

    fetch(url, opciones)
        .then(res => res.text()) // Procesa la respuesta como texto
        .then(texto => {
            console.log("Respuesta del servidor:", texto); // Muestra la respuesta en la consola

            // Manejo de la respuesta
            if (texto.includes("Error")) {
                document.getElementById('msjError').innerHTML = texto; // Muestra el error
            } else {
                // Redirigir o actualizar la UI según el resultado
                window.location.reload(); // O realizar alguna actualización en la UI
            }
        });
}
