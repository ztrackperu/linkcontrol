// ===========================
// VARIABLES GLOBALES
// ===========================
let contadorFormularios = 0;

// ===========================
// INICIALIZACIÓN
// ===========================
document.addEventListener("DOMContentLoaded", function () {
  initializeDateTime();
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
  const btnAgregarUnico = document.getElementById("btnAgregarUnico");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const contenedor = document.getElementById("contenedorFormularios");

  // Limpiar contenedor y resetear contador siempre
  contenedor.innerHTML = "";
  contadorFormularios = 0;

  if (tipoControl) {
    botonesFormulario.classList.remove("d-none");

    if (tipoControl === "unico") {
      btnAgregarUnico.classList.remove("d-none");
      agregarFormularioUnico();
    } else if (tipoControl === "ciclico") {
      btnAgregarUnico.classList.add("d-none");
      agregarFormularioCiclico();
    }
  } else {
    resetearFormularios();
  }
}

// ===========================
// CREACIÓN DE FORMULARIOS
// ===========================
function agregarFormularioUnico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioUnico");

  if (!template) {
    console.error("Template no encontrado");
    return;
  }

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-${contadorFormularios}`;

  const titulo = clone.querySelector(".titulo-control");
  if (titulo) {
    titulo.textContent = `Control Único #${contadorFormularios}`;
  }

  if (contadorFormularios > 1) {
    const btnEliminar = clone.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.remove("d-none");
    }
  }

  const contenedor = document.getElementById("contenedorFormularios");
  if (contenedor) {
    contenedor.appendChild(clone);
  }
}

function agregarFormularioCiclico() {
  const template = document.getElementById("templateFormularioCiclico");

  if (!template) {
    console.error("Template no encontrado");
    return;
  }

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = "formulario-ciclico";

  const contenedor = document.getElementById("contenedorFormularios");
  if (contenedor) {
    contenedor.appendChild(clone);
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

  formularios.forEach((titulo, index) => {
    titulo.textContent = `Control Único #${index + 1}`;
  });

  contadorFormularios = formularios.length;
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
  const btnAgregarUnico = document.getElementById("btnAgregarUnico");
  const botonesFormulario = document.getElementById("botonesFormulario");

  if (contenedor) contenedor.innerHTML = "";
  if (btnAgregarUnico) btnAgregarUnico.classList.add("d-none");
  if (botonesFormulario) botonesFormulario.classList.add("d-none");

  contadorFormularios = 0;
}

// ===========================
// MANEJO DEL FORMULARIO
// ===========================
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("frmControlAutomatico");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      procesarFormulario();
    });
  }
});

function procesarFormulario() {
  const tipoControl = document.getElementById("tipoControl").value;
  const formData = new FormData(
    document.getElementById("frmControlAutomatico")
  );

  console.log("Procesando formulario:", tipoControl);

  if (tipoControl === "unico") {
    const etapas = formData.getAll("etapa[]");
    const fechasHoras = formData.getAll("fechaHora[]");
    const horas = formData.getAll("horas[]");
    const temperaturas = formData.getAll("temperatura[]");

    console.log("Controles únicos:", {
      etapas,
      fechasHoras,
      horas,
      temperaturas,
    });
  } else if (tipoControl === "ciclico") {
    const etapa = formData.get("etapa");
    const hora = formData.get("hora");
    const horas = formData.get("horas");
    const temperatura = formData.get("temperatura");

    console.log("Control cíclico:", {
      etapa,
      hora,
      horas,
      temperatura,
    });
  }
}
