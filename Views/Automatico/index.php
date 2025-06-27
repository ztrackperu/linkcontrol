<?php include "Views/templates/navbar.php"; ?>
<div class="container-fluid px-2 py-2">
    <!-- TÍTULO GENERAL PROFESIONAL -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-cpu-fill fs-2 text-white"></i>
                                </div>
                                <div>
                                    <h1 class="mb-1 fw-bold">Control Automático Inteligente</h1>
                                    <p class="mb-0 opacity-75">Sistema avanzado de gestión y programación automática</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <span class="badge bg-success bg-opacity-25 text-white px-3 py-2 fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Sistema Activo
                                </span>
                                <span class="badge bg-info bg-opacity-25 text-white px-3 py-2 fs-6">
                                    <i class="bi bi-clock me-1"></i><span id="currentTime">--:--</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Indicadores de estado -->
                    <div class="row mt-4 g-3">
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold" id="totalControles">0</div>
                                <small class="opacity-75">Controles Activos</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-warning" id="controlesEjecutandose">0</div>
                                <small class="opacity-75">En Ejecución</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-info" id="controlesProgramados">0</div>
                                <small class="opacity-75">Programados</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-danger" id="alertasActivas">0</div>
                                <small class="opacity-75">Alertas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- FORMULARIO CREAR CONTROL -->
        <div class="col-12 col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Crear Control Automático</h5>
                    <!-- CAMBIO: ID correcto y función correcta -->
                    <button type="button" class="btn btn-light btn-sm d-none" id="btnAgregar" onclick="agregarFormulario()">
                        <i class="bi bi-plus-circle me-1"></i>Agregar
                    </button>
                </div>
                <div class="card-body p-0 d-flex flex-column">
    <!-- Área de formulario con altura fija y scroll -->
    <div class="flex-grow-1 overflow-auto p-3" style="height: 500px; min-height: 500px;">
        <form id="frmControlAutomatico">
            <!-- SELECT OPCIÓN -->
            <div class="mb-3">
                <label for="tipoControl" class="form-label fw-bold">Opción</label>
                <select class="form-select" id="tipoControl" name="tipoControl" required onchange="mostrarFormulario()">
                    <option value="">Seleccionar...</option>
                    <option value="unico">Único</option>
                    <option value="ciclico">Cíclico</option>
                </select>
            </div>

            <!-- CAMPO FECHA FIN DEL PROCESO (Aparece para ambos tipos) -->
            <div class="mb-3 d-none" id="campoFechaFin">
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h6 class="mb-0 text-warning fw-bold">
                            <i class="bi bi-calendar-check me-2"></i>Fecha y Hora de Finalización del Proceso
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nombre Proceso</label>
                        <!-- CAMBIO: Array para múltiples formularios -->
                        <input type="text" class="form-control" name="nombrep[]" placeholder="Nombre del proceso" required>
                    </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">Fecha y Hora (FIN DEL PROCESO COMPLETO)</label>
                                <input type="datetime-local" class="form-control" name="fechaHoraFin" id="fechaHoraFin" required>
                                <small class="text-muted">Esta será la fecha de finalización de todo el proceso de control</small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENEDOR FORMULARIOS CON SCROLL INTERNO -->
            <div id="contenedorFormularios" class="mb-3"></div>
        </form>
    </div>
    
    <!-- Botones fijos en la parte inferior -->
    <div class="border-top p-3 bg-light">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end d-none" id="botonesFormulario">
            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
            </button>
            <button type="submit" class="btn btn-success" form="frmControlAutomatico">
                <i class="bi bi-check-circle me-1"></i>Crear Control
            </button>
        </div>
    </div>
</div>

            </div>
        </div>

        <!-- TABLA CONTROLES CREADOS -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Controles Automáticos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="height: 580px; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0" id="tablaControles">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>Nombre Proceso</th>
                                    <th>Tipo</th>
                                    <th>IMEI</th>
                                    <th>Etapas</th>
                                    <th>Estado</th>
                                    <th>Condicion</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="contenidoTabla"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TEMPLATES OCULTOS -->
<div class="d-none">
    <!-- Template Formulario Único -->
    <div id="templateFormularioUnico" class="template-formulario">
        <div class="card mb-3 border-primary">
            <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary fw-bold">
                    <i class="bi bi-gear-fill me-2"></i>
                    <span class="titulo-control">Control Único #1</span>
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar d-none" onclick="eliminarFormulario(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Fecha y Hora (INICIO)</label>
                        <input type="datetime-local" class="form-control" name="fechaHoraInicio[]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
                <!-- <input type="hidden" name="humedad[]" value=""> -->
            </div>
        </div>
    </div>

    <!-- Template Formulario Cíclico -->
    <div id="templateFormularioCiclico" class="template-formulario">
        <div class="card mb-3 border-info">
            <!-- CAMBIO: Agregado botón eliminar y título dinámico -->
            <div class="card-header bg-info bg-opacity-10 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-info fw-bold">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    <span class="titulo-control">Control Cíclico #1</span>
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar d-none" onclick="eliminarFormulario(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <!-- CAMBIO: Array para múltiples formularios -->
                        <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hora</label>
                        <!-- CAMBIO: Array para múltiples formularios -->
                        <input type="time" class="form-control" name="hora[]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <!-- CAMBIO: Array para múltiples formularios -->
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
                <!-- CAMBIO: Array para múltiples formularios -->
                <!-- <input type="hidden" name="humedad[]" value=""> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Warning -->
<div class="modal fade" id="modalWarning" tabindex="-1" aria-labelledby="modalWarningLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title" id="modalWarningLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Control Activo en Proceso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoWarning">
                <!-- El contenido se insertará dinámicamente desde el controlador -->
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger px-4" onclick="eliminarControlActivoYContinuar()">
    <i class="bi bi-trash me-1"></i>Eliminar y Continuar
</button>

            </div>
        </div>
    </div>
</div>



<!-- Modal de Éxito -->
<div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="modalExitoLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>¡Éxito!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h6 class="fw-bold text-success mb-2">Operación Exitosa</h6>
                <p class="mb-0 text-muted" id="mensajeExito"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                    <i class="bi bi-check me-1"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>





<!-- Modal de Error -->
<div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="modalErrorLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Error
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h6 class="fw-bold text-danger mb-2">Ha ocurrido un error</h6>
                <p class="mb-0 text-muted" id="mensajeError"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Control -->
<div class="modal fade" id="modalVerControl" tabindex="-1" aria-labelledby="modalVerControlLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="modalVerControlLabel">
                    <i class="bi bi-eye-fill me-2"></i>Detalles del Control
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoVerControl">
                <!-- El HTML del controlador se insertará aquí -->
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Control - Versión Profesional -->
<div class="modal fade" id="modalEliminarControl" tabindex="-1" aria-labelledby="modalEliminarControlLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <h5 class="modal-title text-white fw-bold" id="modalEliminarControlLabel">
                    <i class="bi bi-shield-exclamation me-2"></i>Confirmación Requerida
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-trash3-fill text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="text-dark mb-3 fw-bold">¿Eliminar Control?</h4>
                <p class="text-muted mb-4 px-3">
                    Esta acción eliminará permanentemente el control seleccionado junto con todas sus etapas programadas. 
                    <strong>Esta operación no se puede deshacer.</strong>
                </p>
                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning-emphasis">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Advertencia:</strong> Se perderán todos los datos asociados
                </div>
                <input type="hidden" id="idControlEliminar" value="">
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light btn-lg px-4 me-2" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-lg px-4" onclick="confirmarEliminacion()">
                    <i class="bi bi-trash3 me-2"></i>Sí, Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Éxito Eliminación -->
<div class="modal fade" id="modalExitoEliminacion" tabindex="-1" aria-labelledby="modalExitoEliminacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <h5 class="modal-title text-white fw-bold" id="modalExitoEliminacionLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>Operación Exitosa
                </h5>
            </div>
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="text-dark mb-3 fw-bold">¡Control Eliminado!</h4>
                <p class="text-muted mb-0">
                    El control ha sido eliminado exitosamente del sistema.
                </p>
            </div>
        </div>
    </div>
</div>






<?php include "Views/templates/footer.php"; ?>
