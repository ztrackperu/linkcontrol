// ===========================
// AUTOMATICO.JS - ARCHIVO PRINCIPAL (SIN ES6 MODULES)
// ===========================

// Función para cargar scripts dinámicamente
function loadScript(src) {
  return new Promise((resolve, reject) => {
    const script = document.createElement("script");
    script.src = src;
    script.onload = resolve;
    script.onerror = reject;
    document.head.appendChild(script);
  });
}

// Cargar todos los módulos en orden
async function loadModules() {
  try {
    console.log("🔄 Cargando módulos...");

    await loadScript(base_url + "Assets/js/automatico/constants.js");
    await loadScript(base_url + "Assets/js/automatico/datetime.js");
    await loadScript(base_url + "Assets/js/automatico/validation.js");
    await loadScript(base_url + "Assets/js/automatico/modals.js");
    await loadScript(base_url + "Assets/js/automatico/tables.js");
    await loadScript(base_url + "Assets/js/automatico/api.js");
    await loadScript(base_url + "Assets/js/automatico/forms.js");

    console.log("✅ Todos los módulos cargados");

    // Una vez cargados todos los módulos, inicializar la app
    initializeApp();
  } catch (error) {
    console.error("❌ Error cargando módulos:", error);
  }
}

// ===========================
// APLICACIÓN PRINCIPAL
// ===========================
class AutomaticoApp {
  constructor() {
    this.datetime = new DateTimeManager();
    this.forms = new FormManager();
    this.validation = new ValidationManager();
    this.api = new ApiManager();
    this.modals = new ModalManager();
    this.tables = new TableManager();

    this.init();
  }

  init() {
    this.datetime.initialize();
    this.loadInitialData();
    this.exposeGlobalFunctions();
  }

  loadInitialData() {
    this.api.listarControl();
    this.api.listarHistorico();
    this.api.cargarContadores();
  }

  exposeGlobalFunctions() {
    // Exponer funciones globalmente para compatibilidad con HTML onclick
    window.mostrarFormulario = () => this.forms.mostrarFormulario();
    window.agregarFormulario = () => this.forms.agregarFormulario();
    window.eliminarFormulario = (boton) => this.forms.eliminarFormulario(boton);
    window.limpiarFormulario = () => this.forms.limpiarFormulario();
    window.verControl = (id) => this.api.verControl(id);
    window.eliminarControl = (id) => this.api.eliminarControl(id);
    window.confirmarEliminacion = () => this.api.confirmarEliminacion();
    window.eliminarControlActivoYContinuar = () =>
      this.api.eliminarControlActivoYContinuar();
    window.editarControl = (id) => this.api.editarControl(id);
    window.duplicarControl = (id) => this.api.duplicarControl(id);
    window.pausarControl = (id) => this.api.pausarControl(id);
    window.reanudarControl = (id) => this.api.reanudarControl(id);
    window.guardarEdicion = () => this.api.guardarEdicion();

    // Variables globales para compatibilidad
    window.contadorFormularios = STATE.contadorFormularios;
    window.tipoControlActual = STATE.tipoControlActual;
    window.idControlActivo = STATE.idControlActivo;
    window.nuevoControlData = STATE.nuevoControlData;
  }
}

// ===========================
// INICIALIZACIÓN
// ===========================
function initializeApp() {
  console.log("🚀 Inicializando aplicación...");
  window.App = new AutomaticoApp();
  window.CONFIG = CONFIG;
  window.STATE = STATE;
  window.DateUtils = DateUtils;
  console.log("✅ Aplicación inicializada");
}

// Cargar módulos cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  loadModules();
});
