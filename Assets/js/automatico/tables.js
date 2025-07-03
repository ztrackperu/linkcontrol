// ===========================
// MÓDULO DE GESTIÓN DE TABLAS
// ===========================
class TableManager {
  constructor() {
    this.initializeTables();
  }

  initializeTables() {
    this.setupTableEvents();
  }

  setupTableEvents() {
    // Configurar eventos de ordenamiento si es necesario
    this.setupSortableHeaders();

    // Configurar filtros de tabla
    this.setupTableFilters();
  }

  setupSortableHeaders() {
    const headers = document.querySelectorAll(".table-sortable th[data-sort]");
    headers.forEach((header) => {
      header.style.cursor = "pointer";
      header.addEventListener("click", (e) => {
        this.sortTable(e.target);
      });
    });
  }

  setupTableFilters() {
    const filtros = document.querySelectorAll(".table-filter");
    filtros.forEach((filtro) => {
      filtro.addEventListener("input", (e) => {
        this.filtrarTabla(e.target);
      });
    });
  }

  mostrarControlesEnTabla(data) {
    const tbody = document.getElementById("contenidoTabla");
    if (tbody) {
      tbody.innerHTML = data;
      this.actualizarContadorFilas("contenidoTabla", "contadorControles");
    }
  }

  mostrarHistoricoEnTabla(data) {
    const tbody = document.getElementById("contenidoTablaHistorico");
    if (tbody) {
      tbody.innerHTML = data;
      this.actualizarContadorFilas(
        "contenidoTablaHistorico",
        "contadorHistorico"
      );
    }
  }

  actualizarContadorFilas(tablaId, contadorId) {
    const tabla = document.getElementById(tablaId);
    const contador = document.getElementById(contadorId);

    if (tabla && contador) {
      const filas = tabla.querySelectorAll("tr").length;
      contador.textContent = `Total: ${filas} registros`;
    }
  }

  sortTable(header) {
    const table = header.closest("table");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const columnIndex = Array.from(header.parentNode.children).indexOf(header);
    const sortType = header.dataset.sort;
    const currentOrder = header.dataset.order || "asc";
    const newOrder = currentOrder === "asc" ? "desc" : "asc";

    // Limpiar indicadores de ordenamiento anteriores
    header.parentNode.querySelectorAll("th").forEach((th) => {
      th.classList.remove("sort-asc", "sort-desc");
      delete th.dataset.order;
    });

    // Ordenar filas
    rows.sort((a, b) => {
      const aValue = a.cells[columnIndex].textContent.trim();
      const bValue = b.cells[columnIndex].textContent.trim();

      let comparison = 0;

      if (sortType === "number") {
        comparison = parseFloat(aValue) - parseFloat(bValue);
      } else if (sortType === "date") {
        comparison = new Date(aValue) - new Date(bValue);
      } else {
        comparison = aValue.localeCompare(bValue);
      }

      return newOrder === "asc" ? comparison : -comparison;
    });

    // Aplicar ordenamiento
    rows.forEach((row) => tbody.appendChild(row));

    // Actualizar indicadores visuales
    header.classList.add(`sort-${newOrder}`);
    header.dataset.order = newOrder;
  }

  filtrarTabla(input) {
    const filtro = input.value.toLowerCase();
    const tabla = input.closest(".table-container").querySelector("table");
    const filas = tabla.querySelectorAll("tbody tr");

    let filasVisibles = 0;

    filas.forEach((fila) => {
      const texto = fila.textContent.toLowerCase();
      const visible = texto.includes(filtro);

      fila.style.display = visible ? "" : "none";
      if (visible) filasVisibles++;
    });

    // Actualizar contador de resultados
    const contador = input
      .closest(".table-container")
      .querySelector(".contador-resultados");
    if (contador) {
      contador.textContent = `Mostrando ${filasVisibles} de ${filas.length} registros`;
    }
  }

  exportarTablaCSV(tablaId, nombreArchivo = "datos") {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;

    let csv = [];
    const filas = tabla.querySelectorAll("tr");

    filas.forEach((fila) => {
      const celdas = fila.querySelectorAll("th, td");
      const filaData = [];

      celdas.forEach((celda) => {
        // Limpiar el texto y escapar comillas
        let texto = celda.textContent.trim();
        texto = texto.replace(/"/g, '""');
        filaData.push(`"${texto}"`);
      });

      csv.push(filaData.join(","));
    });

    // Crear y descargar archivo
    const csvContent = csv.join("\n");
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");

    if (link.download !== undefined) {
      const url = URL.createObjectURL(blob);
      link.setAttribute("href", url);
      link.setAttribute("download", `${nombreArchivo}.csv`);
      link.style.visibility = "hidden";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  }

  resaltarFila(elemento) {
    // Remover resaltado anterior
    const filasResaltadas = elemento
      .closest("table")
      .querySelectorAll("tr.fila-resaltada");
    filasResaltadas.forEach((fila) => fila.classList.remove("fila-resaltada"));

    // Resaltar fila actual
    const fila = elemento.closest("tr");
    if (fila) {
      fila.classList.add("fila-resaltada");
    }
  }

  actualizarEstadoFila(idControl, nuevoEstado) {
    const filas = document.querySelectorAll(`tr[data-id="${idControl}"]`);
    filas.forEach((fila) => {
      const celdaEstado = fila.querySelector(".estado-control");
      if (celdaEstado) {
        celdaEstado.textContent = nuevoEstado;
        celdaEstado.className = `estado-control badge bg-${this.getColorEstado(
          nuevoEstado
        )}`;
      }
    });
  }

  getColorEstado(estado) {
    const colores = {
      Activo: "success",
      Pausado: "warning",
      Completado: "info",
      Eliminado: "danger",
      Pendiente: "secondary",
      "En Proceso": "primary",
      Error: "danger",
    };
    return colores[estado] || "secondary";
  }

  mostrarDetalleEnFila(idControl) {
    const fila = document.querySelector(`tr[data-id="${idControl}"]`);
    if (!fila) return;

    const filaDetalle = fila.nextElementSibling;

    if (filaDetalle && filaDetalle.classList.contains("fila-detalle")) {
      // Si ya existe, alternar visibilidad
      filaDetalle.style.display =
        filaDetalle.style.display === "none" ? "" : "none";
    } else {
      // Crear nueva fila de detalle
      this.crearFilaDetalle(fila, idControl);
    }
  }

  crearFilaDetalle(filaOriginal, idControl) {
    const nuevaFila = document.createElement("tr");
    nuevaFila.classList.add("fila-detalle");
    nuevaFila.innerHTML = `
            <td colspan="100%" class="p-0">
                <div class="collapse show">
                    <div class="card card-body m-2">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        `;

    filaOriginal.parentNode.insertBefore(nuevaFila, filaOriginal.nextSibling);

    // Cargar datos del detalle
    this.cargarDetalleControl(idControl, nuevaFila);
  }

  cargarDetalleControl(idControl, filaDetalle) {
    fetch(base_url + "Automatico/obtenerDetalle", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id_control=" + idControl,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          const contenido = filaDetalle.querySelector(".card-body");
          contenido.innerHTML = res.html;
        } else {
          const contenido = filaDetalle.querySelector(".card-body");
          contenido.innerHTML =
            '<p class="text-danger">Error al cargar los detalles</p>';
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        const contenido = filaDetalle.querySelector(".card-body");
        contenido.innerHTML = '<p class="text-danger">Error de conexión</p>';
      });
  }
}
