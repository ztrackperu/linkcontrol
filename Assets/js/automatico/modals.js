// ===========================
// MÓDULO DE GESTIÓN DE MODALES
// ===========================
class ModalManager {
  constructor() {
    this.initializeModals();
  }

  initializeModals() {
    // Auto-cerrar modales de éxito después de un tiempo
    this.setupAutoCloseModals();

    // Configurar eventos de modales
    this.setupModalEvents();
  }

  setupAutoCloseModals() {
    const modalExito = document.getElementById("modalExito");
    if (modalExito) {
      modalExito.addEventListener("shown.bs.modal", () => {
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(modalExito);
          if (modal) {
            modal.hide();
          }
        }, CONFIG.TIEMPO_MODAL_AUTO_CLOSE);
      });
    }
  }

  setupModalEvents() {
    // Limpiar formularios al cerrar modales
    const modales = ["modalEditarControl", "modalVerControl"];

    modales.forEach((modalId) => {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.addEventListener("hidden.bs.modal", () => {
          this.limpiarFormularioModal(modalId);
        });
      }
    });
  }

  limpiarFormularioModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      const formularios = modal.querySelectorAll("form");
      formularios.forEach((form) => form.reset());

      const contenidoDinamico = modal.querySelectorAll(".contenido-dinamico");
      contenidoDinamico.forEach((contenido) => {
        contenido.innerHTML = "";
      });
    }
  }

  mostrarModalExito(mensaje) {
    const mensajeElement = document.getElementById("mensajeExito");
    if (mensajeElement) {
      mensajeElement.textContent = mensaje;
    }

    const modal = new bootstrap.Modal(document.getElementById("modalExito"));
    modal.show();
  }

  mostrarModalError(mensaje) {
    const mensajeElement = document.getElementById("mensajeError");
    if (mensajeElement) {
      mensajeElement.textContent = mensaje;
    }

    const modal = new bootstrap.Modal(document.getElementById("modalError"));
    modal.show();
  }

  mostrarModalConfirmacion(titulo, mensaje, callback) {
    const modalElement = document.getElementById("modalConfirmacionGenerico");
    if (!modalElement) {
      console.error("Modal de confirmación genérico no encontrado");
      return;
    }

    const tituloElement = modalElement.querySelector(".modal-title");
    const mensajeElement = modalElement.querySelector(".mensaje-confirmacion");
    const btnConfirmar = modalElement.querySelector(".btn-confirmar");

    if (tituloElement) tituloElement.textContent = titulo;
    if (mensajeElement) mensajeElement.textContent = mensaje;

    // Limpiar eventos anteriores
    const nuevoBtn = btnConfirmar.cloneNode(true);
    btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

    // Agregar nuevo evento
    nuevoBtn.addEventListener("click", () => {
      callback();
      const modal = bootstrap.Modal.getInstance(modalElement);
      if (modal) {
        modal.hide();
      }
    });

    const modal = new bootstrap.Modal(modalElement);
    modal.show();
  }

  mostrarModalCarga(mensaje = "Procesando...") {
    const modalElement = document.getElementById("modalCarga");
    if (modalElement) {
      const mensajeElement = modalElement.querySelector(".mensaje-carga");
      if (mensajeElement) {
        mensajeElement.textContent = mensaje;
      }

      const modal = new bootstrap.Modal(modalElement, {
        backdrop: "static",
        keyboard: false,
      });
      modal.show();
    }
  }

  ocultarModalCarga() {
    const modalElement = document.getElementById("modalCarga");
    if (modalElement) {
      const modal = bootstrap.Modal.getInstance(modalElement);
      if (modal) {
        modal.hide();
      }
    }
  }

  mostrarModalInfo(titulo, mensaje) {
    const modalElement = document.getElementById("modalInfo");
    if (!modalElement) {
      console.error("Modal de información no encontrado");
      return;
    }

    const tituloElement = modalElement.querySelector(".modal-title");
    const mensajeElement = modalElement.querySelector(".mensaje-info");

    if (tituloElement) tituloElement.textContent = titulo;
    if (mensajeElement) mensajeElement.innerHTML = mensaje;

    const modal = new bootstrap.Modal(modalElement);
    modal.show();
  }

  cerrarTodosLosModales() {
    const modalesAbiertos = document.querySelectorAll(".modal.show");
    modalesAbiertos.forEach((modalElement) => {
      const modal = bootstrap.Modal.getInstance(modalElement);
      if (modal) {
        modal.hide();
      }
    });
  }

  confirmarAccion(mensaje, callback) {
    this.mostrarModalConfirmacion("Confirmar Acción", mensaje, callback);
  }
}
