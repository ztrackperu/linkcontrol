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
                    <button type="button" class="btn btn-light btn-sm d-none" id="btnAgregarUnico" onclick="agregarFormularioUnico()">
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
                                    <th>Etapa</th>
                                    <th>Tipo</th>
                                    <th>Programación</th>
                                    <th>Temp</th>
                                    <th>Estado</th>
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
                        <label class="form-label fw-semibold">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" name="fechaHora[]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Horas</label>
                        <input type="number" class="form-control" name="horas[]" min="1" max="24" placeholder="Duración" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
                <input type="hidden" name="humedad[]" value="50">
            </div>
        </div>
    </div>

    <!-- Template Formulario Cíclico -->
    <div id="templateFormularioCiclico" class="template-formulario">
        <div class="card mb-3 border-info">
            <div class="card-header bg-info bg-opacity-10">
                <h6 class="mb-0 text-info fw-bold">
                    <i class="bi bi-arrow-repeat me-2"></i>Control Cíclico
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input type="text" class="form-control" name="etapa" placeholder="Nombre de la etapa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hora</label>
                        <input type="time" class="form-control" name="hora" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Horas</label>
                        <input type="number" class="form-control" name="horas" min="1" max="24" placeholder="Duración" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
                <input type="hidden" name="humedad" value="50">
            </div>
        </div>
    </div>
</div>

<?php include "Views/templates/footer.php"; ?>
