// ===========================
// VARIABLES GLOBALES
// ===========================
let contadorFormularios = 0;
let tipoControlActual = "";

// ===========================
// INICIALIZACIÓN
// ===========================
document.addEventListener("DOMContentLoaded", function () {
  initializeDateTime();
  initializeFormSubmit();
  ListarControl();
});

// ===========================
// FUNCIONES DE FECHA Y HORA
// ===========================
function initializeDateTime() {
  updateCurrentTime();
  setInterval(updateCurrentTime, 1000);
}

function updateCurrentTime() {
  const now = new Date();
  const dateTimeString = now.toLocaleString("es-PE", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour12: true,
  });

  const timeElement = document.getElementById("currentTime");
  if (timeElement) {
    timeElement.textContent = dateTimeString;
  }
}

// ===========================
// GESTIÓN DE FORMULARIOS DINÁMICOS
// ===========================
function mostrarFormulario() {
  const tipoControl = document.getElementById("tipoControl").value;
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const contenedor = document.getElementById("contenedorFormularios");
  const campoFechaFin = document.getElementById("campoFechaFin");

  // Limpiar contenedor y resetear contador
  contenedor.innerHTML = "";
  contadorFormularios = 0;
  tipoControlActual = tipoControl;

  if (tipoControl) {
    // Mostrar elementos del formulario
    botonesFormulario.classList.remove("d-none");
    btnAgregar.classList.remove("d-none");

    // Mostrar campo fecha fin para ambos tipos
    if (campoFechaFin) {
      campoFechaFin.classList.remove("d-none");
    }

    // Agregar primer formulario
    agregarFormulario();
  } else {
    resetearFormularios();
  }
}

function agregarFormulario() {
  if (tipoControlActual === "unico") {
    agregarFormularioUnico();
  } else if (tipoControlActual === "ciclico") {
    agregarFormularioCiclico();
  }
}

// ===========================
// CREACIÓN DE FORMULARIOS
// ===========================
function agregarFormularioUnico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioUnico");
  if (!template) return;

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-unico-${contadorFormularios}`;
  actualizarTituloFormulario(clone, "Único");
  mostrarBotonEliminarSiEsNecesario(clone);
  document.getElementById("contenedorFormularios").appendChild(clone);
}

function agregarFormularioCiclico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioCiclico");
  if (!template) return;

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-ciclico-${contadorFormularios}`;
  actualizarTituloFormulario(clone, "Cíclico");
  mostrarBotonEliminarSiEsNecesario(clone);
  document.getElementById("contenedorFormularios").appendChild(clone);
}

function actualizarTituloFormulario(clone, tipo) {
  const titulo = clone.querySelector(".titulo-control");
  if (titulo) {
    titulo.textContent = `Control ${tipo} #${contadorFormularios}`;
  }
}

function mostrarBotonEliminarSiEsNecesario(clone) {
  if (contadorFormularios > 1) {
    const btnEliminar = clone.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.remove("d-none");
    }
  }
}

// ===========================
// ELIMINACIÓN Y ACTUALIZACIÓN
// ===========================
function eliminarFormulario(boton) {
  const formulario = boton.closest(".card");
  if (formulario) {
    formulario.remove();
    actualizarNumeracion();
  }
}

function actualizarNumeracion() {
  const formularios = document.querySelectorAll(
    "#contenedorFormularios .titulo-control"
  );

  const tipoTexto = tipoControlActual === "unico" ? "Único" : "Cíclico";

  formularios.forEach((titulo, index) => {
    titulo.textContent = `Control ${tipoTexto} #${index + 1}`;
  });

  contadorFormularios = formularios.length;

  // Ocultar botón eliminar si solo hay un formulario
  if (contadorFormularios === 1) {
    const btnEliminar = document.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.add("d-none");
    }
  }
}

// ===========================
// LIMPIEZA Y RESET
// ===========================
function limpiarFormulario() {
  const form = document.getElementById("frmControlAutomatico");
  if (form) {
    form.reset();
  }
  resetearFormularios();
}

function resetearFormularios() {
  const contenedor = document.getElementById("contenedorFormularios");
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const campoFechaFin = document.getElementById("campoFechaFin");

  if (contenedor) contenedor.innerHTML = "";
  if (btnAgregar) btnAgregar.classList.add("d-none");
  if (botonesFormulario) botonesFormulario.classList.add("d-none");
  if (campoFechaFin) campoFechaFin.classList.add("d-none");

  contadorFormularios = 0;
  tipoControlActual = "";
}

// ===========================
// VALIDACIÓN DE FECHA FIN
// ===========================
function validarFechaFin() {
  const fechaFin = document.getElementById("fechaHoraFin").value;

  if (!fechaFin) {
    mostrarModalError("La fecha de finalización del proceso es obligatoria");
    return false;
  }

  const fechaFinDate = new Date(fechaFin);
  const ahora = new Date();

  if (fechaFinDate <= ahora) {
    mostrarModalError(
      "La fecha de finalización debe ser posterior a la fecha actual"
    );
    return false;
  }

  // Validar que la fecha fin sea posterior a todas las fechas de inicio
  const fechasInicio = document.querySelectorAll(
    'input[name="fechaHoraInicio[]"]'
  );
  for (let i = 0; i < fechasInicio.length; i++) {
    const fechaInicio = new Date(fechasInicio[i].value);
    if (fechaFinDate <= fechaInicio) {
      mostrarModalError(
        `La fecha de finalización debe ser posterior a la fecha de inicio de la etapa ${
          i + 1
        }`
      );
      return false;
    }
  }

  return true;
}

// ===========================
// MANEJO DEL FORMULARIO
// ===========================
function initializeFormSubmit() {
  const form = document.getElementById("frmControlAutomatico");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Validar fecha fin antes de procesar
      if (validarFechaFin()) {
        procesarFormulario();
      }
    });
  }
}

function ListarControl() {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/listar";
  http.open("POST", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      mostrarControlesEnTabla(res);
    }
  };
}

function mostrarControlesEnTabla(data) {
  const tbody = document.getElementById("contenidoTabla");
  tbody.innerHTML = data;
}

function procesarFormulario() {
  const formData = new FormData(
    document.getElementById("frmControlAutomatico")
  );

  // Agregar información adicional
  formData.append("tipoControlActual", tipoControlActual);
  formData.append("totalFormularios", contadorFormularios);

  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/crear";

  http.open("POST", url, true);
  http.send(formData);

  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta recibida:", this.responseText);

      try {
        const res = JSON.parse(this.responseText);
        console.log("JSON parseado:", res);

        if (res.success) {
          console.log(
            "Condición de éxito cumplida, llamando mostrarModalExito"
          );
          mostrarModalExito(res.message);
          limpiarFormulario();
          ListarControl();
        } else if (res.type === "control_activo") {
          console.log("Control activo detectado");

          // GUARDAR LAS VARIABLES GLOBALES CORRECTAMENTE
          window.nuevoControlData = res.nuevo_control;
          window.idControlActivo = res.id_control_activo;

          console.log("Variables guardadas:");
          console.log("idControlActivo:", window.idControlActivo);
          console.log("nuevoControlData:", window.nuevoControlData);

          mostrarModalAdvertencia(res.html_detalles);
        } else {
          console.log("Error detectado");
          mostrarModalError(res.message);
        }
      } catch (error) {
        console.error("Error al parsear JSON:", error);
      }
    }
  };
}

function mostrarModalExito(mensaje) {
  document.getElementById("mensajeExito").textContent = mensaje;
  const modal = new bootstrap.Modal(document.getElementById("modalExito"));
  modal.show();
}

function mostrarModalError(mensaje) {
  document.getElementById("mensajeError").textContent = mensaje;
  const modal = new bootstrap.Modal(document.getElementById("modalError"));
  modal.show();
}

function mostrarModalAdvertencia(htmlDetalles) {
  // Solo insertar el HTML construido en el controlador
  document.getElementById("contenidoWarning").innerHTML = htmlDetalles;
  // Mostrar modal
  const modal = new bootstrap.Modal(document.getElementById("modalWarning"));
  modal.show();
}

function verControl(idControl) {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/verControl";

  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  const params = "id_control=" + idControl;
  http.send(params);

  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      if (res.success) {
        // Insertar el HTML en el modal
        document.getElementById("contenidoVerControl").innerHTML = res.html;

        // Mostrar el modal
        const modal = new bootstrap.Modal(
          document.getElementById("modalVerControl")
        );
        modal.show();
      } else {
        alert("Error: " + res.message);
      }
    }
  };
}

function eliminarControl(idControl) {
  // Guardar el ID en el campo oculto
  document.getElementById("idControlEliminar").value = idControl;

  // Mostrar el modal usando Bootstrap
  const modal = new bootstrap.Modal(
    document.getElementById("modalEliminarControl")
  );
  modal.show();
}

function confirmarEliminacion() {
  const idControl = document.getElementById("idControlEliminar").value;

  if (!idControl) {
    alert("Error: ID de control no válido");
    return;
  }

  // Cerrar el modal de confirmación
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalEliminarControl")
  );
  modal.hide();

  // Realizar la eliminación
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/eliminarControl";

  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  const params = "id_control=" + idControl;
  http.send(params);

  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      if (res.success) {
        // Mostrar modal de éxito
        const modalExito = new bootstrap.Modal(
          document.getElementById("modalExitoEliminacion")
        );
        modalExito.show();

        // Cerrar automáticamente después de 2 segundos y recargar
        setTimeout(function () {
          modalExito.hide();
          location.reload();
        }, 2000);
      } else {
        alert("Error: " + res.message);
      }
    }
  };
}

// Agregar esta función al archivo Automatico.js
function eliminarControlActivoYContinuar() {
  console.log("=== DEBUGGING eliminarControlActivoYContinuar ===");
  console.log("idControlActivo:", window.idControlActivo);
  console.log("nuevoControlData:", window.nuevoControlData);

  if (!window.idControlActivo || !window.nuevoControlData) {
    console.error("❌ Datos no disponibles");
    console.log("idControlActivo existe:", !!window.idControlActivo);
    console.log("nuevoControlData existe:", !!window.nuevoControlData);
    mostrarModalError("Error: Datos del control no disponibles");
    return;
  }

  console.log("✅ Datos disponibles, procediendo con eliminación");

  // Cerrar el modal de advertencia primero
  const modalWarning = bootstrap.Modal.getInstance(
    document.getElementById("modalWarning")
  );
  if (modalWarning) {
    modalWarning.hide();
  }

  // Eliminar el control activo
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/eliminarControl";

  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  const params = "id_control=" + window.idControlActivo;
  console.log("Enviando parámetros:", params);
  http.send(params);

  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta eliminación:", this.responseText);
      try {
        const res = JSON.parse(this.responseText);

        if (res.success) {
          console.log("✅ Control eliminado, creando nuevo");
          // Control eliminado exitosamente, ahora crear el nuevo
          crearNuevoControlDespuesDeEliminar();
        } else {
          console.error("❌ Error al eliminar:", res.message);
          mostrarModalError(
            "Error al eliminar el control activo: " + res.message
          );
        }
      } catch (error) {
        console.error("Error al parsear respuesta de eliminación:", error);
        mostrarModalError("Error en la respuesta del servidor");
      }
    }
  };
}

function crearNuevoControlDespuesDeEliminar() {
  console.log("=== DEBUGGING crearNuevoControlDespuesDeEliminar ===");
  console.log("nuevoControlData:", window.nuevoControlData);

  if (!window.nuevoControlData) {
    mostrarModalError("Error: Datos del nuevo control no disponibles");
    return;
  }

  // Recrear el FormData con los datos del nuevo control
  const formData = new FormData();

  // Agregar el tipo de control
  const tipoControl =
    window.nuevoControlData.tipo_control_temperatura === 0
      ? "unico"
      : "ciclico";
  formData.append("tipoControl", tipoControl);

  // Agregar nombre del proceso
  formData.append(
    "nombrep",
    window.nuevoControlData.proceso_control_temperatura
  );

  // Agregar fecha fin
  formData.append(
    "fechaHoraFin",
    convertirFechaParaFormulario(
      window.nuevoControlData.hora_fin_control_temperatura
    )
  );

  // Agregar las etapas
  window.nuevoControlData.lista_control_temperatura.forEach((etapa, index) => {
    formData.append("etapa[]", etapa.nombre_etapa);
    formData.append("temperatura[]", etapa.temperatura_etapa);

    if (window.nuevoControlData.tipo_control_temperatura === 0) {
      // Para único, usar fechaHoraInicio
      formData.append(
        "fechaHoraInicio[]",
        convertirFechaParaFormulario(etapa.hora_inicio_etapa)
      );
    } else {
      // Para cíclico, usar solo hora
      const horaExtraida = extraerHoraDeFecha(etapa.hora_inicio_etapa);
      formData.append("hora[]", horaExtraida);
    }
  });

  console.log("FormData creado, enviando petición");

  // Enviar la petición para crear el nuevo control
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/crear";

  http.open("POST", url, true);
  http.send(formData);

  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta creación nuevo control:", this.responseText);
      try {
        const res = JSON.parse(this.responseText);

        if (res.success) {
          mostrarModalExito(
            "Control anterior eliminado y nuevo control creado exitosamente"
          );
          limpiarFormulario();
          ListarControl();

          // Limpiar variables globales
          window.idControlActivo = null;
          window.nuevoControlData = null;
        } else {
          mostrarModalError(
            "Control eliminado pero error al crear el nuevo: " + res.message
          );
        }
      } catch (error) {
        console.error("Error al parsear respuesta de creación:", error);
        mostrarModalError("Error en la respuesta del servidor");
      }
    }
  };
}

// Funciones auxiliares para convertir fechas
function convertirFechaParaFormulario(fechaCustom) {
  // Convertir de "25-06-2025_17-42" a "2025-06-25T17:42"
  if (!fechaCustom || fechaCustom === "No definida") {
    return "";
  }

  const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
  const match = fechaCustom.match(regex);

  if (match) {
    const [, dia, mes, año, hora, minuto] = match;
    return `${año}-${mes}-${dia}T${hora}:${minuto}`;
  }

  return fechaCustom;
}

function extraerHoraDeFecha(fechaCustom) {
  // Extraer solo la hora de "25-06-2025_17-42" -> "17:42"
  if (!fechaCustom) {
    return "";
  }

  const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
  const match = fechaCustom.match(regex);

  if (match) {
    const [, , , , hora, minuto] = match;
    return `${hora}:${minuto}`;
  }

  return "";
}
