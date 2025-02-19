document.addEventListener("change", (event) => {
  // Funciones para encontrar el rol y el usuario seleccionado del dropdown
  const selectRol = document.getElementById("frol")
  const selectUsuario = document.getElementById("fusuario")

  if (event.target && event.target.id === "frol") {
    console.log("🔹 Rol seleccionado:", event.target.value)
  }

  if (event.target && event.target.id === "fusuario") {
    console.log("🔹 Usuario seleccionado:", event.target.value)
    destacarRolesPorUsuario();
  }
})

// Funcion para cargar un script de manera dinamica
function cargarUnScript(url) {
    const script = document.createElement("script")
    script.src = url
    document.head.appendChild(script)
  }
  
  // Aqui es donde crearemos las funciones básicas
  function obtenerVista(controlador, metodo, destino) {
    const parametros = "controlador=" + controlador + "&metodo=" + metodo
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros, opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById(destino).innerHTML = vista
        cargarUnScript("js/" + controlador + ".js")
      })
      .catch((error) => {
        console.log("Error al pedir vista", error.message)
      })
}
  
// Funcion para obtener la vista formulario con la que crear o editar datos
function obtenerVista_EditarCrear(controlador, metodo, destino, id) {
  const parametros = "controlador=" + controlador + "&metodo=" + metodo + "&id=" + id // Add '=' after id
  const opciones = { method: "GET" }

  fetch("C_Frontal.php?" + parametros, opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById(destino).innerHTML = vista
    })
    .catch((error) => {
      console.log("Error al pedir vista", error.message)
    })
}
  
/* MANTENIMIENTO DE MENU */
// Función para crear un menú nuevo. 
function añadirMenu(menuId, positionType) {
  const parametros = new URLSearchParams()
  parametros.append("controlador", "Menu")
  parametros.append("metodo", "getVistaNuevoEditar")
  parametros.append("menu_id", menuId)
  parametros.append("position_type", positionType) // Parámetro para indicar que se agregará arriba

  const opciones = { method: "GET" }

  fetch("C_Frontal.php?" + parametros.toString(), opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById("capaEditarCrear").innerHTML = vista
    })
    .catch((error) => {
      console.log(`Error al añadir el menú ${positionType}`, error.message)
    })
}
  
// Funcion para añadir un hijo a un menú
function añadirHijo(menuId) {
  const parametros = new URLSearchParams()
  parametros.append("controlador", "Menu")
  parametros.append("metodo", "getVistaNuevoEditar")
  parametros.append("menu_id", menuId)
  parametros.append("position_type", "child") // Especificar que se agregará un hijo

  const opciones = { method: "GET" }

  fetch("C_Frontal.php?" + parametros.toString(), opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById("capaEditarCrear").innerHTML = vista
    })
    .catch((error) => {
      console.log("Error al añadir hijo", error.message)
    })
}

// Funcion para poder hacer un desplegable en aquellos menus que son dropdown y tienen hijos
function toggleChildren(menuId, event) {
  event.stopPropagation() // Evitar activar el clic en la fila
  const children = document.getElementById(`children-${menuId}`)
  const arrow = event.target.closest(".arrow").querySelector("i") // Busca el ícono dentro del contenedor de flecha

  if (children.style.display === "none") {
    children.style.display = "block" // Mostrar hijos
    arrow.classList.remove("fa-chevron-right")
    arrow.classList.add("fa-chevron-down")
  } else {
    children.style.display = "none" // Ocultar hijos
    arrow.classList.remove("fa-chevron-down")
    arrow.classList.add("fa-chevron-right")
  }
}

// Función para que al pulsar el boton del menú se desplieguen los btoones de mantenimiento
function toggleOptions(menuId) {
  // Obtener los selects de rol y usuario
  const selectRol = document.getElementById("frol");
  const selectUsuario = document.getElementById("fusuario");

  // Si se ha seleccionado un rol o un usuario, no se despliegan las opciones
  if ((selectRol && selectRol.value !== "") || (selectUsuario && selectUsuario.value !== "")) {
    return; // Salir sin hacer nada
  }

  // Si no se ha seleccionado rol/usuario, se continúa con la lógica de despliegue
  const options = document.getElementById(`options-${menuId}`);
  const allOptions = document.querySelectorAll(".menu-options");
  allOptions.forEach((opt) => {
    if (opt !== options) opt.style.display = "none"; // Oculta las demás opciones
  });
  options.style.display = options.style.display === "none" ? "block" : "none";
}
  
/* Funciones para todas las pantallas */ 
// Función para cargar la vista listado tras pulsar el boton buscar.
function buscar(controlador, metodo, formulario, destino) {
  let parametros = "controlador=" + controlador + "&metodo=" + metodo // Fixed the parameter formatting
  const opciones = { method: "GET" }
  parametros += "&" + new URLSearchParams(new FormData(document.getElementById(formulario))).toString()

  fetch("C_Frontal.php?" + parametros, opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById(destino).innerHTML = vista
    })
    .catch((error) => {
      console.log("Error al pedir vista", error.message) // Fixed 'err' to 'error'
    })
}
  
/* MANTENIMIENTO DE ROLES */

// Funcion para activar la vista listado de los menus con los parametros adecuados (roles o usuarios seleccionados)
function buscarRol() {
  const selectRol = document.getElementById("frol");
  const selectUsuario = document.getElementById("fusuario");
  const selectedRoleId = selectRol ? selectRol.value : "";
  const selectedUserId = selectUsuario ? selectUsuario.value : "";

  console.log("🔍 ID del Rol seleccionado:", selectedRoleId);
  console.log("🔍 ID del Usuario seleccionado:", selectedUserId);

  const parametros = new URLSearchParams();
  parametros.append("controlador", "Menu");
  parametros.append("metodo", "getVistaListadoMenu");
  parametros.append("frol", selectedRoleId);
  parametros.append("fusuario", selectedUserId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
      .then((res) => res.text())
      .then((vista) => {
          document.getElementById("capaResultadoBusqueda").innerHTML = vista;

          // 🚀 Si se seleccionó un usuario, obtener los permisos heredados por su rol
          if (selectedUserId) {
              obtenerPermisosHeredados(selectedUserId);
          }
      })
  .catch((error) => console.log("❌ Error al buscar menús y permisos:", error.message));
}

// Funcion para guardar un rol nuevo tras crear o editar
function guardarRol() {
  const formData = new FormData(document.getElementById("formularioNuevoEditar"));

  const opciones = {
      method: "POST",
      body: formData,
  };

  fetch("C_Frontal.php?controlador=Roles&metodo=guardarRol", opciones)
      .then((res) => res.json())
      .then((respuesta) => {
          if (respuesta.correcto === "S") {
              //alert(respuesta.msj);
              
              // Actualizar la lista de roles después de guardar
              obtenerVista("Menu", "getVistaFiltros", "capaContenido");

              // Cerrar el formulario de edición/creación
              document.getElementById("capaEditarCrear").innerHTML = "";
          } else {
              document.getElementById("msjError").innerText = respuesta.msj;
          }
      })
      .catch((error) => {
          console.error("Error al guardar rol:", error);
          document.getElementById("msjError").innerText = "Error al procesar la solicitud.";
      });
}  

// Funcion para desbloquear el boton desplegable de mantenimiento de roles
function habilitarBotonesRol() {
  const selectRol = document.getElementById("frol")
  const btnEditarRol = document.getElementById("btnEditarRol")
  const btnEliminarRol = document.getElementById("btnEliminarRol")
  const rolSeleccionado = selectRol.value !== ""

  btnEditarRol.disabled = !rolSeleccionado
  btnEliminarRol.disabled = !rolSeleccionado
}

// Funcion para eliminar un rol
function eliminarRolSeleccionado() {
  const selectRol = document.getElementById("frol");
  const rolId = selectRol.value;

  if (rolId) {
    fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${rolId}`, { method: "POST" })
        .then((res) => res.json())
        .then((respuesta) => {
          if (respuesta.correcto === "S") {
            //alert(respuesta.msj);

            // Actualizar la vista de filtros después de eliminar el rol
            obtenerVista("Menu", "getVistaFiltros", "capaContenido");

            // Cerrar el formulario de edición/creación
            document.getElementById("capaEditarCrear").innerHTML = "";

            // Opcional: Resetear el select de roles después de eliminar
            selectRol.value = "";
          } else {
            //alert(respuesta.msj);
          }
        })
        .catch((error) => {
          console.error("❌ Error al eliminar rol:", error);
          //alert("Error al procesar la solicitud.");
        });
  } else {
    //alert("Por favor, seleccione un rol para eliminar.");
  }
}

// Funcion que mediante un boton llama al formulario para crear or editar un rol
function editarRolSeleccionado() {
  const selectRol = document.getElementById("frol")
  const rolId = selectRol.value
  if (rolId) {
    obtenerVista_EditarCrear("Roles", "getVistaNuevoEditar", "capaEditarCrear", rolId)
  } else {
    //alert("Por favor, seleccione un rol para editar.")
  }
}

// Funcion para asignar un rol al usuario
function asignarRolAUsuario() {
  const selectRol = document.getElementById("frol");
  const selectUsuario = document.getElementById("fusuario");
  const mensajeError = document.getElementById("mensajeError"); // Obtener el elemento de error

  const rolId = selectRol.value;
  const usuarioId = selectUsuario.value;

  if (!rolId || !usuarioId) {
      // alert("Por favor, seleccione un rol y un usuario.");
      mensajeError.innerText = "Por favor seleccione un rol y un usuario";
      mensajeError.classList.remove("d-none"); // Mostrar el mensaje
      return;
  }

  const parametros = new URLSearchParams();
  parametros.append("controlador", "Roles");
  parametros.append("metodo", "asignarRolAUsuario");
  parametros.append("rol_id", rolId);
  parametros.append("usuario_id", usuarioId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then((res) => res.json()) // Convertimos la respuesta a JSON
      .then((respuesta) => {
          console.log("🔍 Respuesta del servidor:", respuesta);
          if (respuesta.correcto === "S") {
            // Ocultar el mensaje de error si la operación es exitosa
            mensajeError.classList.add("d-none");
            mensajeError.innerText = "";
        } else {
            // Mostrar el mensaje de error si hay un problema
            mensajeError.innerText = "⚠️ " + respuesta.msj;
            mensajeError.classList.remove("d-none");
        }
        
      })
      .catch((error) => {
          console.error("❌ Error en la solicitud:", error);
          //alert("Error al procesar la solicitud.");
      });
}

// Funcion para desasignar un rol al usuario
function desasignarRolAUsuario() {
  const selectRol = document.getElementById("frol");
  const selectUsuario = document.getElementById("fusuario");

  const rolId = selectRol.value;
  const usuarioId = selectUsuario.value;

  if (!rolId || !usuarioId) {
      //alert("Por favor, seleccione un rol y un usuario.");
      return;
  }

  const parametros = new URLSearchParams();
  parametros.append("controlador", "Roles");
  parametros.append("metodo", "desasignarRolAUsuario");
  parametros.append("rol_id", rolId);
  parametros.append("usuario_id", usuarioId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then((res) => res.json())
      .then((respuesta) => {
          console.log("🔍 Respuesta del servidor:", respuesta);
          if (respuesta.correcto === "S") {
              //alert("✅ " + respuesta.msj);
              mensajeError.classList.add("d-none");
              mensajeError.innerText = "";
          } else {
              //alert("⚠️ " + respuesta.msj);
              mensajeError.innerText = "⚠️ " + respuesta.msj;
              mensajeError.classList.remove("d-none");
          }
      })
      .catch((error) => {
          console.error("❌ Error en la solicitud:", error);
          //alert("Error al procesar la solicitud.");
      });
}

// Funcion para destacar aquellos roles a los que pertenece un usuario
function destacarRolesPorUsuario() {
  const selectUsuario = document.getElementById("fusuario");
  const usuarioId = selectUsuario.value;
  const selectRol = document.getElementById("frol");

  // Primero removemos cualquier clase previamente aplicada en las opciones
  for (let option of selectRol.options) {
    option.classList.remove("linked-role");
  }

  // Si no se seleccionó usuario, no hacemos nada
  if (!usuarioId) return;

  // Configurar parámetros para la petición AJAX
  const parametros = new URLSearchParams();
  parametros.append("controlador", "Roles");
  parametros.append("metodo", "obtenerRolesDeUsuario");
  parametros.append("usuario_id", usuarioId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
    .then(response => response.json())
    .then(data => {
      if (data.correcto === "S") {
        const rolesVinculados = data.roles; // Array con IDs de roles asignados
        // Recorrer todas las opciones del select de roles
        for (let option of selectRol.options) {
          // Si el valor de la opción está en la lista, se añade la clase
          if (rolesVinculados.includes(option.value)) {
            option.classList.add("linked-role");
          }
        }
      } else {
        console.error("Error:", data.msj);
      }
    })
    .catch(error => console.error("Error obteniendo roles del usuario:", error));
}

/* MANTENIMIENTO DE PERMISOS */

// Funcion para asignar o desasignar un rol al pulsar el checkbox del permiso
function togglePermiso(permisoId) {
  const checkbox = document.querySelector(`input[data-permiso-id="${permisoId}"]`)
  const seleccionado = checkbox.checked

  const selectRol = document.getElementById("frol")
  const selectedRoleId = selectRol ? selectRol.value : ""

  const selectUsuario = document.getElementById("fusuario")
  const selectedUserId = selectUsuario ? selectUsuario.value : ""

  if (!selectedRoleId && !selectedUserId) {
    console.log("⚠️ No se seleccionó un rol ni un usuario.")
    return
  }

  const parametros = new URLSearchParams()
  parametros.append("controlador", "Permisos") // Usamos el nuevo controlador
  parametros.append("metodo", "actualizarPermiso")
  parametros.append("id_permiso", permisoId)
  parametros.append("asignado", seleccionado ? "1" : "0")

  if (selectedRoleId) {
    parametros.append("frol", selectedRoleId)
  }
  if (selectedUserId) {
    parametros.append("fusuario", selectedUserId)
  }

  console.log("🔄 Enviando solicitud de actualización:", parametros.toString())

  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
    .then((res) => res.text())
    .then((respuesta) => console.log("🔄 Respuesta del servidor:", respuesta))
    .catch((error) => console.log("❌ Error al actualizar permiso:", error.message))
}
  
// Funcion para mostrar el permiso seleccionado en consola
function mostrarPermisoSeleccionado(permisoId) {
  console.log("🔹 Permiso seleccionado:", permisoId)
}

// Funcion para obtener aquellos permisos que un usuario hereda por pertenecer a un rol
function obtenerPermisosHeredados(usuarioId) {
  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "getPermisosHeredadosUsuario");
  parametros.append("id_usuario", usuarioId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
      .then((res) => res.json())
      .then((data) => {
          if (data.correcto === "S") {
              console.log("🔹 Permisos heredados del usuario por sus roles:", data.permisos_heredados);
              // Ejemplo de recorrido: cada elemento contiene 'id_permiso' y 'id_rol'
              data.permisos_heredados.forEach(item => {
                  console.log(`Permiso: ${item.id_permiso} - Rol de origen: ${item.id_rol}`);
              });
          } else {
              console.log("⚠️ Error obteniendo permisos heredados:", data.error);
          }
      })
      .catch((error) => console.log("❌ Error en la solicitud de permisos heredados:", error.message));
}

// Cargar la vista para editar un permiso (todo el rato presente)
function mostrarEditarPermiso(permisoId) {
  const input = document.getElementById("permiso-edit-" + permisoId);
  if (input) {
    input.style.display = "inline-block";
    input.focus();
  }
}

// Cambiar el nombre del permiso tras actualizarlo
function actualizarNombrePermiso(permisoId) {
  const input = document.getElementById("permiso-edit-" + permisoId);
  const nuevoNombre = input.value;

  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "actualizarNombrePermiso");
  parametros.append("id_permiso", permisoId);
  parametros.append("nuevo_nombre", nuevoNombre);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
    .then(response => response.text())
    .then(responseText => {
      console.log("Nombre actualizado:", responseText);
      if(responseText.trim() === "OK") {
        const span = document.getElementById("permiso-nombre-" + permisoId);
        if (span) {
          span.innerText = nuevoNombre;
        }
      }
      input.style.display = "none";
    })
    .catch(error => console.log("Error al actualizar nombre de permiso:", error));
}

function eliminarPermiso(permisoId) {
  const parametros = new URLSearchParams();
    parametros.append("controlador", "Permisos");
    parametros.append("metodo", "eliminarPermiso");
    parametros.append("id_permiso", permisoId);

    fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then(response => response.text())
      .then(responseText => {
        console.log("Permiso eliminado:", responseText);
        if(responseText.trim() === "OK") {
          const permisoItem = document.getElementById("permiso-item-" + permisoId);
          if (permisoItem) {
            permisoItem.remove();
          }
        }
      })
      .catch(error => console.log("Error al eliminar permiso:", error));
}

function crearPermiso(menuId) {
  const input = document.getElementById("permiso-new-" + menuId);
  const nuevoNombre = input.value;
  
  if (!nuevoNombre.trim()) {
      //alert("Por favor, ingrese un nombre para el permiso.");
      return;
  }
  
  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "crearPermiso");
  parametros.append("nombre", nuevoNombre);
  parametros.append("id_menu", menuId);
  
  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then(response => response.text())
      .then(responseText => {
          if (responseText.trim() === "OK") {
              //alert("Permiso creado correctamente.");
              
              // Limpiar el input
              input.value = "";

              // Obtener la lista de permisos de este menú (solo si no la vamos a actualizar con la función)
              // const listaPermisos = document.getElementById("permiso-list-" + menuId);
              buscarRol();
              // if (listaPermisos) {
              //     // Crear un nuevo elemento de lista para el permiso
              //     const nuevoElemento = document.createElement("li");
              //     nuevoElemento.textContent = nuevoNombre; // Usamos el nombre del permiso recién creado
              //     listaPermisos.appendChild(nuevoElemento);
              // }
          } else {
              //alert("Error al crear permiso: " + responseText);
          }
      })
      .catch(error => console.log("Error al crear permiso:", error));
}

/* MANTENIMIENTO DE USUARIOS */
// Guardar un nuevo usuario
function guardarUsuario() {
  // Validación de los campos antes de enviar los datos
  let nombre = document.getElementById('nombre').value;
  let login = document.getElementById('login').value;
  let email = document.getElementById('mail').value;
  let errores = [];

  if (!nombre) {
      errores.push("El campo nombre es obligatorio.");
  } else if (nombre.length < 3) {
      errores.push("El nombre debe tener al menos 3 caracteres.");
  }

  if (!login) {
      errores.push("El campo login es obligatorio.");
  } else if (login.length < 3) {
      errores.push("El login debe tener al menos 3 caracteres.");
  }

  if (!email) {
      errores.push("El campo email es obligatorio.");
  } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
      errores.push("El formato del email es incorrecto.");
  }

  if (errores.length > 0) {
      document.getElementById('msjError').innerHTML = errores.join("<br>");
      return; // Detenemos la ejecución de guardarUsuario si hay errores
  }

  // Si no hay errores, limpiamos el mensaje de error y procedemos con la llamada al servidor
  document.getElementById('msjError').innerHTML = "";

  console.log("Guardando usuario...");

  // Parámetros y opciones para el fetch
  let parametros = "controlador=Usuarios&metodo=guardarUsuario";
  let opciones = {method: 'GET',}
  parametros += "&" + new URLSearchParams(new FormData(document.getElementById('formularioUsuario'))).toString(); // Obtenemos los datos del formulario

  // Llamamos al servidor
  fetch("C_Frontal.php?" + parametros, opciones)
      .then(res => {
          if (res.ok) {                
              return res.json();       
          } 
          throw new Error(res.status); // Si no, capturamos el error
      })
      .then(resultado=> {
          if(resultado.correcto == 'S') {
              document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
          } else {
              document.getElementById('msjError').innerHTML = resultado.msj;
          }
      })
      .catch(err => {                // Si hay un error lo mostramos 
          console.error("Error al guardar", err.message);
      });
}

// Cambiar el color del usuario cuando esta activo o inactivo al pulsar el alternar estado
function actualizarEstadoVisual(usuarioId) {
  // Cambia el innerHTML del estado inmediatamente
  const estadoElemento = document.getElementById(`estado-${usuarioId}`);
  const filaElemento = estadoElemento.closest('tr'); // Encuentra la fila (tr) que contiene este elemento

  // Alterna entre "Activo" e "Inactivo"
  if (estadoElemento.innerHTML === 'Activo') {
      estadoElemento.innerHTML = 'Inactivo';
      filaElemento.classList.add('table-danger');  // Cambia la fila a rojo (inactivo)
      filaElemento.classList.remove('table-success'); // Remueve la clase activa (si la tiene)
  } else {
      estadoElemento.innerHTML = 'Activo';
      filaElemento.classList.add('table-success');  // Cambia la fila a verde (activo)
      filaElemento.classList.remove('table-danger'); // Remueve la clase inactiva (si la tiene)
  }
}

// Cambiar el estado (activo / inactivo) al pulsar el boton de alternar
function toggleEstado(usuarioId) {
  // Llama a actualizarEstadoVisual para cambiar el estado de inmediato en la interfaz
  actualizarEstadoVisual(usuarioId);

  // Luego envía la solicitud AJAX para actualizar el estado en la base de datos
  fetch("C_Frontal.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `controlador=Usuarios&metodo=cambiarEstado&id_Usuario=${usuarioId}`
  })
  .then(response => response.json())
  .then(data => {
      if (!data.success) {
          console.error("Error:", data.error);
      }
  })
  .catch(error => console.error("Error en la solicitud:", error));
}