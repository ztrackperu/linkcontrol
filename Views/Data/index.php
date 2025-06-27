<?php include "Views/templates/navbar.php"; ?>
<div class="px-2 py-2">
    <h1 align="center" id="tituloData">TEST123456-7</h1>

    <div class="row justify-content-center " style="padding: 5px; margin-top:1px;">
        <div class="col-8 col-lg-3 align-self-end" style="margin-top:5px;" >
            <h5 >Search by Date :</h5>
        </div>
        <div class="col-4 col-lg-2 align-self-end" style="padding-right: 15px; margin-top:5px;">
            <select class="form-select" aria-label="Default select example" id="temp_c_f_d">
                <option value=0 selected>C°</option>
                <option value=1 >F°</option>
            </select>
        </div>
        <div class="col-6 col-lg-2" style="padding-left: 15px; margin-top:10px;">
            <h5 ><strong>From :</strong></h5>
            <input class='form-control'  id="fechaInicial_d" type="datetime-local">	
        </div>
        <div class="col-6 col-lg-2" style="padding-right: 15px;margin-top:10px;">
            <h5 ><strong>To :</strong></h5>
            <input class='form-control' id="fechaFin_d" type="datetime-local">
        </div>
        <div class="col-12 col-lg-2 align-self-center d-grid" style="margin-top:5px;">
            <button type="button"  id="fechaPer" onclick="procesarFecha_2()" class="btn btn-primary btn-lg">Search </button>
        </div>
    </div>



    <div class="col-12">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!--CONTENEDOR -->
                        <h1 class="fw-bold fs-4" id="titleData">General Data</h1>
                        <div class="table-responsive mt-5">
                            <table class="table table-bordered table-hover" style="width:100%" id="tblDatos">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Meter</th>
                                        <th>State</th>
                                        <th>Set Point</th>
                                        <th>Supply</th>
                                        <th>Return</th>
                                        <th>Compress</th>
                                        <th>Set H</th>
                                        <th>Humidity</th>
                                        <th>Ambient </th>
                                        <th>Evaporation</th>
                                        <th>Phase 1</th>
                                        <th>Phase 2</th>
                                        <th>Phase 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí irán tus filas de datos -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "Views/templates/footer.php"; ?>