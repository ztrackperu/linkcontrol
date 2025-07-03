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

<!-- Modal Eliminar Control -->
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


<!-- Modal Control No Encontrado (Versión Flexible) -->
<div class="modal fade" id="modalControlNoEncontrado" tabindex="-1" aria-labelledby="modalControlNoEncontradoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <h5 class="modal-title text-white fw-bold" id="modalControlNoEncontradoLabel">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>Control No Disponible
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-x-fill text-secondary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="text-dark mb-3 fw-bold">Proceso No Disponible</h4>
                <p class="text-muted mb-4 px-3" id="mensajeControlNoEncontrado">
                    No se pueden visualizar los detalles de un proceso eliminado.
                    <br><strong>El control ya no existe en el sistema.</strong>
                </p>
                <div class="alert alert-secondary border-0 bg-secondary bg-opacity-10 text-secondary-emphasis">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Información:</strong> Este proceso ha sido eliminado previamente
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="bi bi-check me-2"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>

