<!-- FORMULARIO CREAR CONTROL -->
<div class="col-12 col-lg-6">
    <div class="card shadow border-0 h-100">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Crear Control Automático</h5>
            <button type="button" class="btn btn-light btn-sm d-none" id="btnAgregar" onclick="agregarFormulario()">
                <i class="bi bi-plus-circle me-1"></i>Agregar
            </button>
        </div>
        <div class="card-body p-0 d-flex flex-column">
            <!-- Área de formulario con altura fija y scroll -->
            <div class="flex-grow-1 overflow-auto p-3" style="height: 500px; min-height: 500px;">
                <form id="frmControlAutomatico">
                    <?php include "Views/automatico/components/form-fields.php"; ?>
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
