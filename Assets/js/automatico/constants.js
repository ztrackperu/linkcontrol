// ===========================
// CONSTANTES Y CONFIGURACIÓN
// ===========================
const CONFIG = {
  TIPOS_CONTROL: {
    UNICO: "unico",
    CICLICO: "ciclico",
    PERIODICO: "periodico",
  },
  MINIMO_FORMULARIOS_PERIODICO: 2,
  TIEMPO_ACTUALIZACION: 1000,
  TIEMPO_MODAL_AUTO_CLOSE: 2000,
};

const STATE = {
  contadorFormularios: 0,
  tipoControlActual: "",
  idControlActivo: null,
  nuevoControlData: null,
};
