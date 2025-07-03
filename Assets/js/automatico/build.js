const fs = require("fs");
const path = require("path");

console.log("🔨 Construyendo Automatico.js...");

// Archivos a concatenar en orden
const archivos = [
  "Assets/js/automatico/constants.js",
  "Assets/js/automatico/datetime.js",
  "Assets/js/automatico/validation.js",
  "Assets/js/automatico/modals.js",
  "Assets/js/automatico/tables.js",
  "Assets/js/automatico/api.js",
  "Assets/js/automatico/forms.js",
];

let contenidoFinal = `// ===========================
// AUTOMATICO.JS - GENERADO AUTOMÁTICAMENTE
// Fecha: ${new Date().toLocaleString()}
// ===========================

`;

// Leer y concatenar archivos
archivos.forEach((archivo) => {
  if (fs.existsSync(archivo)) {
    const contenido = fs.readFileSync(archivo, "utf8");
    contenidoFinal += `\n// ===========================\n// ${path
      .basename(archivo)
      .toUpperCase()}\n// ===========================\n`;
    contenidoFinal += contenido + "\n";
    console.log(`✅ ${archivo} agregado`);
  } else {
    console.log(`⚠️  ${archivo} no encontrado`);
  }
});

// Agregar aplicación principal
contenidoFinal += `
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
        window.mostrarFormulario = () => this.forms.mostrarFormulario();
        window.agregarFormulario = () => this.forms.agregarFormulario();
        window.eliminarFormulario = (boton) => this.forms.eliminarFormulario(boton);
        window.limpiarFormulario = () => this.forms.limpiarFormulario();
        window.verControl = (id) => this.api.verControl(id);
        window.eliminarControl = (id) => this.api.eliminarControl(id);
        window.confirmarEliminacion = () => this.api.confirmarEliminacion();
        window.eliminarControlActivoYContinuar = () => this.api.eliminarControlActivoYContinuar();
        window.editarControl = (id) => this.api.editarControl(id);
        window.duplicarControl = (id) => this.api.duplicarControl(id);
        window.pausarControl = (id) => this.api.pausarControl(id);
        window.reanudarControl = (id) => this.api.reanudarControl(id);
        window.guardarEdicion = () => this.api.guardarEdicion();
    }
}

// ===========================
// INICIALIZACIÓN
// ===========================
let App;

document.addEventListener("DOMContentLoaded", function () {
    App = new AutomaticoApp();
    window.App = App;
    window.CONFIG = CONFIG;
    window.STATE = STATE;
    window.DateUtils = DateUtils;
});
`;

// Escribir archivo final
fs.writeFileSync("Assets/js/Automatico.js", contenidoFinal);

console.log("🎉 Automatico.js generado exitosamente!");
console.log(`📦 Tamaño: ${(contenidoFinal.length / 1024).toFixed(2)} KB`);
