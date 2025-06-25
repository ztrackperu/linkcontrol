<?php include "Views/templates/navbar.php"; ?>
<div class="px-2 py-2">
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-lg-12" id="contenidoPrincipal">
                <!-- CONTENIDO PRINCIPAL-->
            </div>
        </div>
    </div>
</div>
<!-- MODAL ALARMA-->
<div class='modal fade' id='modalAlarma' tabindex='-1' aria-labelledby='my-modal-title' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='title'>ALARMAS</h5>
                <button class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblAlarma" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Alarma</th>
                                <th>Equipo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL MENSAJE-->
<div class='modal fade' id='modalMensaje' tabindex='-1' aria-labelledby='my-modal-title' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='title'>MENSAJES</h5>
                <button class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblMensaje" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Mensaje</th>
                                <th>Equipo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL HORAS -->
<div class='modal fade' id='modalHoras' tabindex='-1' aria-labelledby='my-modal-title' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='title'>HORAS</h5>
                <button class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblHoras" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Desde</th>
                                <th>Hora actual</th>
                                <th>Duración</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="https://wa.me/51999999999" class="whatsapp" target="_blank">
    <i class="ri-whatsapp-fill whatsapp-icon"></i>
</a>
<?php include "Views/templates/footer.php"; ?>