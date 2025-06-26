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
      const res = JSON.parse(this.responseText);
      if (res.success) {
        mostrarModalExito(res.message);
        limpiarFormulario();
        ListarControl();
      } else if (res.type === "control_activo") {
        nuevoControlData = res.nuevo_control;
        mostrarModalAdvertencia(res.html_detalles);
      } else {
        mostrarModalError(res.message);
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
