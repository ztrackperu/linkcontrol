<?php include "Views/templates/navbar.php"; ?>
<div class="px-2 py-2">
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

    <div class="col-12">
        <div class="row">
            <!-- FORMULARIO CREAR CONTROL -->
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Crear Control Automático</h5>
                        <button type="button" class="btn btn-light btn-sm" id="btnAgregarUnico" style="display: none;" onclick="agregarFormularioUnico()">
                            <i class="bi bi-plus-circle me-1"></i>Agregar
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="frmControlAutomatico">
                            <!-- SELECT OPCIÓN -->
                            <div class="mb-3">
                                <label for="tipoControl" class="form-label">Opción</label>
                                <select class="form-select" id="tipoControl" name="tipoControl" required onchange="mostrarFormulario()">
                                    <option value="">Seleccionar...</option>
                                    <option value="unico">Único</option>
                                    <option value="ciclico">Cíclico</option>
                                </select>
                            </div>

                            <!-- CONTENEDOR FORMULARIOS -->
                            <div id="contenedorFormularios"></div>

                            <!-- BOTONES -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="botonesFormulario" style="display: none;">
                                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Limpiar</button>
                                <button type="submit" class="btn btn-success">Crear Control</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABLA CONTROLES CREADOS -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Controles Automáticos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaControles">
                                <thead>
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
</div>

<script>
let contadorFormularios = 0;

function mostrarFormulario() {
    const tipoControl = document.getElementById('tipoControl').value;
    const btnAgregarUnico = document.getElementById('btnAgregarUnico');
    const botonesFormulario = document.getElementById('botonesFormulario');
    const contenedor = document.getElementById('contenedorFormularios');
    
    if (tipoControl) {
        botonesFormulario.style.display = 'block';
        
        if (tipoControl === 'unico') {
            btnAgregarUnico.style.display = 'block';
            if (contadorFormularios === 0) {
                agregarFormularioUnico();
            }
        } else {
            btnAgregarUnico.style.display = 'none';
            contenedor.innerHTML = crearFormularioCiclico();
        }
    } else {
        btnAgregarUnico.style.display = 'none';
        botonesFormulario.style.display = 'none';
        contenedor.innerHTML = '';
        contadorFormularios = 0;
    }
}

function crearFormularioUnico(numero) {
    return `
        <div class="border rounded p-3 mb-3 bg-light" id="formulario-${numero}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-gear-fill me-2"></i>Control Único #${numero}
                </h6>
                ${numero > 1 ? `<button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarFormulario(${numero})">
                    <i class="bi bi-trash"></i>
                </button>` : ''}
            </div>
            
            <div class="mb-3">
                <label class="form-label">Etapa</label>
                <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha y Hora</label>
                <input type="datetime-local" class="form-control" name="fechaHora[]" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Horas</label>
                <input type="number" class="form-control" name="horas[]" min="1" max="24" placeholder="Duración en horas" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Temperatura (°C)</label>
                <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temperatura objetivo" required>
            </div>

            <input type="hidden" name="humedad[]" value="50">
        </div>
    `;
}

function crearFormularioCiclico() {
    return `
        <div class="border rounded p-3 mb-3 bg-light">
            <h6 class="mb-3 text-primary">
                <i class="bi bi-arrow-repeat me-2"></i>Control Cíclico
            </h6>
            
            <div class="mb-3">
                <label class="form-label">Etapa</label>
                <input type="text" class="form-control" name="etapa" placeholder="Nombre de la etapa" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hora</label>
                <input type="time" class="form-control" name="hora" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Horas</label>
                <input type="number" class="form-control" name="horas" min="1" max="24" placeholder="Duración en horas" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Temperatura (°C)</label>
                <input type="number" class="form-control" name="temperatura" step="0.1" placeholder="Temperatura objetivo" required>
            </div>

            <input type="hidden" name="humedad" value="50">
        </div>
    `;
}

function agregarFormularioUnico() {
    contadorFormularios++;
    const contenedor = document.getElementById('contenedorFormularios');
    contenedor.insertAdjacentHTML('beforeend', crearFormularioUnico(contadorFormularios));
}

function eliminarFormulario(numero) {
    document.getElementById(`formulario-${numero}`).remove();
}

function limpiarFormulario() {
    document.getElementById('frmControlAutomatico').reset();
    document.getElementById('contenedorFormularios').innerHTML = '';
    document.getElementById('btnAgregarUnico').style.display = 'none';
    document.getElementById('botonesFormulario').style.display = 'none';
    contadorFormularios = 0;
}
</script>

<?php include "Views/templates/footer.php"; ?>
