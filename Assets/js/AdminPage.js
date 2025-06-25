let contenidoPrincipal = document.getElementById('contenidoPrincipal');
let modalComando = 1;
//$('.dropdown-toggle').dropdown()

function mostrarMasContenido(){
    $('.view-more').click(function(){   
        $('.hide-content').attr('hidden', false);
        let btn = "<i class='ri-arrow-up-circle-line view-less text-danger fs-2'></i>";
        $('#change-button').html(btn);
    });
}

function mostrarMenosContenido(){
    $('.view-less').click(function(){   
        $('.hide-content').attr('hidden', true);
        let btn = "<button type='button' class='btn btn-primary btn-sm view-more'>View More</button>";
        $('#change-button').html(btn);
    });
}

function viewMoreG(){
    $('#nvlComb').attr('hidden', false);
    $('#cmp1').attr('hidden', false);
    $('#cmp2').attr('hidden', false);
    $('#cmp3').attr('hidden', false);
    $('#cmp4').attr('hidden', false);
    $('#cmp5').attr('hidden', false);
    $('.view-more-genset1').attr('hidden', true);
    //html button view less
    $('#viewG').html("<button type='button' class='btn btn-danger view-less-genset1 px-2 py-1' onclick='viewLessG()'><i class='ri-arrow-up-circle-line'></i></button>");
}

function viewLessG(){
    $('#nvlComb').attr('hidden', true);
    $('#cmp1').attr('hidden', true);
    $('#cmp2').attr('hidden', true);
    $('#cmp3').attr('hidden', true);
    $('#cmp4').attr('hidden', true);
    $('#cmp5').attr('hidden', true);
    $('.view-more-genset1').attr('hidden', false);
    //html button view more
    $('#viewG').html("<button type='button' class='btn btn-primary view-more-genset1 px-2 py-1' onclick='viewMoreG()'>View More</button>");
}
/*
function mostrarContenido(){
    let btnVMore = $('.view-more');
    let classP = btnVMore[0];

    let comparar = classP.className;

    if(comparar.includes('btn btn-primary view-more')){
        $('.hide-content').attr('hidden', false);

        btnVMore.removeClass('btn btn-primary view-more');
        btnVMore.addClass('btn btn-primary view-less');

    }else{
        btnVMore.removeClass('btn btn-primary view-less');
        btnVMore.addClass('btn btn-primary view-more');
        $('.hide-content').attr('hidden', true);
    }
}*/


document.addEventListener("DOMContentLoaded", async function(){
    
    try{
        const response = await fetch(base_url + "AdminPage/ListaDispositivoEmpresa",{method: 'GET'});
        const data = await response.json();
        console.log(data);
        contenidoPrincipal.innerHTML  =data.text;
        $('#nvlComb').attr('hidden', true);
        $('#cmp1').attr('hidden', true);
        $('#cmp2').attr('hidden', true);
        $('#cmp3').attr('hidden', true);
        $('#cmp4').attr('hidden', true);
        $('#cmp5').attr('hidden', true);
        console.log("base next");
        MapLeaflet();
        alarmaModal();
        mensajeModal();
        horasModal();

        //mostrarMenosContenido();

        mostrarMasContenido();
        estadoEquipo();
        
    
    }catch(err){alert(err);}
    
    //cada 10 segundos ejecutar 
    setInterval( async function(){ okey =  await obtenerCambio();}, 30000);
    setInterval( async function(){ tst =  await mostrarMenosContenido();}, 1000);
    setInterval( async function(){ tst2 =  await mostrarMasContenido();}, 1000);

})

function estadoEquipo(){
    let estado = document.getElementById('estadoEquipo');
    console.log(estado);
    //aqui pusisite estatico el id_telemetria obligando a se tenga que modificar 
    //let ultima_fecha = document.getElementById('fechita_4638');
    let ultima_fecha = document.getElementById('fechita_15681');

    console.log(ultima_fecha);
    //sweetAlert
    if(estado.textContent == '(WAIT)'){
        w = Swal.fire({
            title: 'Estado del Equipo',
            text: 'El equipo se encuentra en estado de espera desde: '+ultima_fecha.textContent,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }else if(estado.textContent == '(OFFLINE)'){
        w = Swal.fire({
            title: 'Estado del Equipo',
            text: 'El equipo se encuentra fuera de linea desde: '+ultima_fecha.textContent,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }else{
        //no pasa nada
        w = '';
    }

    return w;
}
function alarmaModal(){
    let alarma = document.querySelector('#alarmaModal');
    alarma.addEventListener('click', function(){
        //$('#modalAlarma').modal('show');
        const http = new XMLHttpRequest();
        const url = base_url + "AdminPage/listaAlarmas";
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $('#modalAlarma').modal('show');
                dataTableAlarma(res);
            }
        }
    });
}

function dataTableAlarma(data){
    //reinicializar
    if ($.fn.DataTable.isDataTable('#tblAlarma')) {
        $('#tblAlarma').DataTable().destroy();
    }
    tblAlarma = $('#tblAlarma').DataTable({
        data: data,
        columns: [
            {data: "id"},
            {data: "fecha"},
            {data: "codigo"},
            {data: "alarma"},
            {data: "equipo"},
            {data: "acciones"}
        ],
        responsive: true
    });
}

function eliminarAlarma(){
    $('#tblAlarma tbody').on('click', 'button.eliminar', function(){
        tblAlarma.row($(this).parents('tr')).remove().draw();
    });
}

function mensajeModal(){
    let mensaje = document.querySelector('#mensajeModal');
    mensaje.addEventListener('click', function(){
        //$('#modalMensaje').modal('show');
        const http = new XMLHttpRequest();
        const url = base_url + "AdminPage/listaMensajes";
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $('#modalMensaje').modal('show');
                dataTableMensaje(res);
            }
        }
    });
}

function dataTableMensaje(data){
    //reinicializar
    if ($.fn.DataTable.isDataTable('#tblMensaje')) {
        $('#tblMensaje').DataTable().destroy();
    }
    tblMensaje = $('#tblMensaje').DataTable({
        data: data,
        columns: [
            {data: "id"},
            {data: "fecha"},
            {data: "codigo"},
            {data: "mensaje"},
            {data: "equipo"},
            {data: "acciones"}
        ],
        responsive: true
    });
}
function eliminarMensaje(){
    $('#tblMensaje tbody').on('click', 'button.eliminar', function(){
        tblMensaje.row($(this).parents('tr')).remove().draw();
    });
}

function horasModal(){
    let horas = document.querySelector('#horasModal');
    horas.addEventListener('click', function(){
        //$('#modalHoras').modal('show');
        const http = new XMLHttpRequest();
        const url = base_url + "AdminPage/listaHoras";
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $('#modalHoras').modal('show');
                dataTableHoras(res);
            }
        }
    });
}

function dataTableHoras(data){
    //reinicializar
    if ($.fn.DataTable.isDataTable('#tblHoras')) {
        $('#tblHoras').DataTable().destroy();
    }
    tblHoras = $('#tblHoras').DataTable({
        data: data,
        columns: [
            {data: "id"},
            {data: "desde"},
            {data: "h_actual"},
            {data: "duracion"},
            {data: "acciones"},
        ],
        responsive: true
    });
}

function eliminarHoras(){
    $('#tblHoras tbody').on('click', 'button.eliminar', function(){
        tblHoras.row($(this).parents('tr')).remove().draw();
    });
}

function MapLeaflet(){
    //creacion de mapa indicando su latitud y longitud, ZOOM
    var mymap = L.map('map').setView([-11.9802124, -77.1226204], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(mymap);
    var greenIcon = L.icon({
        iconUrl: base_url + 'Assets/img/marcador.png',
        iconSize: [30, 30],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    //agregando marcador
    var marker = L.marker([-11.9802124, -77.1226204], {icon: greenIcon}).addTo(mymap);
    //agregando popup
    marker.bindPopup("<b>Ubicación</b>").openPopup();


}

async function obtenerCambio() {
    //$(".loader").show();
    const response = await fetch(base_url + "AdminPage/LiveData", {method: "GET", });
    const result = await response.json();
    if(result.length!=0){
        result.forEach(function(res){
            tarjeta(res);
            //$('#fechita_'+res.telemetria_id).text(res.ultima_fecha);
            console.log(res.telemetria_id);
        })
    }
    console.log(result);
    //setInterval(  function(){ $(".loader").fadeOut("fast"); }, 1000);

    return result;
}

let ethyValues = {};
let co2Values = {};
let supplyValues = {};
let returnValues = {};
let humidityValues = {};
let iHoursValues = {};
let avlValues = {};
let compressorValues = {};
let evaporatorValues = {};
let ambientValues = {};
let pwdValues = {};
let procesoValues = {};
let cmodeValues = {};
let usda1Values = {};
let usda2Values = {};
function tarjeta(res){
    let iconSuccess = "<i class='bi bi-arrow-up-short me-2 align-items-center mb-1 text-success value-icon'></i>";
    let iconDown = "<i class='bi bi-arrow-down-short me-2 align-items-center mb-1 text-danger value-icon'></i>";

    if(ethyValues[res.telemetria_id] === undefined){
        ethyValues[res.telemetria_id] = res.ethylene;
    }
    let evaluacionEti;
    if(res.ethylene > ethyValues[res.telemetria_id]){
        evaluacionEti = iconSuccess;
    }else if(res.ethylene < ethyValues[res.telemetria_id]){
        evaluacionEti = iconDown;
    }

    $('#eti_icon_'+res.telemetria_id).html(evaluacionEti);

    if(co2Values[res.telemetria_id] === undefined){
        co2Values[res.telemetria_id] = res.co2_reading;
    }
    let evaluacionCO2;
    if(res.co2_reading > co2Values[res.telemetria_id]){
        evaluacionCO2 = iconSuccess;
    }else if(res.co2_reading < co2Values[res.telemetria_id]){
        evaluacionCO2 = iconDown;
    }

    $('#co2_icon_'+res.telemetria_id).html(evaluacionCO2);

    if(supplyValues[res.telemetria_id] === undefined){
        supplyValues[res.telemetria_id] = res.temp_supply
    }

    let evaluacionSupply;

    if(res.temp_supply > supplyValues[res.telemetria_id]){
        evaluacionSupply = iconSuccess;
    }else if(res.temp_supply < supplyValues[res.telemetria_id]){
        evaluacionSupply = iconDown;
    }
    $('#supply_icon_'+res.telemetria_id).html(evaluacionSupply);

    if(returnValues[res.telemetria_id] === undefined){
        returnValues[res.telemetria_id] = res.return_air
    }

    let evaluacionReturn;
    if(res.return_air > returnValues[res.telemetria_id]){
        evaluacionReturn = iconSuccess;
    }else if(res.return_air < returnValues[res.telemetria_id]){
        evaluacionReturn = iconDown;
    }
    $('#return_icon_'+res.telemetria_id).html(evaluacionReturn);

    if(humidityValues[res.telemetria_id] === undefined){
        humidityValues[res.telemetria_id] = res.relative_humidity
    }
    let evaluacionHumidity;
    if(res.relative_humidity > humidityValues[res.telemetria]){
        evaluacionHumidity = iconSuccess;
    }else if(res.relative_humidity < humidityValues[res.telemetria_id]){
        evaluacionHumidity = iconDown;
    }
    $('#humidity_icon_'+res.telemetria_id).html(evaluacionHumidity);

    if(iHoursValues[res.telemetria_id] === undefined){
        iHoursValues[res.telemetria_id] = res.ripener_prueba
    }
    let evaluacionIHours;
    if(res.ripener_prueba > iHoursValues[res.telemetria_id]){
        evaluacionIHours = iconSuccess;
    }else if(res.ripener_prueba < iHoursValues[res.telemetria_id]){
        evaluacionIHours = iconDown;
    }
    $('#i_hours_icon_'+res.telemetria_id).html(evaluacionIHours);

    if(avlValues[res.telemetria_id] === undefined){
        avlValues[res.telemetria_id] = res.avl
    }
    let evaluacionAvl;
    if(res.avl > avlValues[res.telemetria_id]){
        evaluacionAvl = iconSuccess;
    }else if(res.avl < avlValues[res.telemetria_id]){
        evaluacionAvl = iconDown;
    }
    $('#avl_icon_'+res.telemetria_id).html(evaluacionAvl);

    if(compressorValues[res.telemetria_id] === undefined){
        compressorValues[res.telemetria_id] = res.compress_coil_1
    }
    let evaluacionCompressor;
    if(res.compress_coil_1 > compressorValues[res.telemetria_id]){
        evaluacionCompressor = iconSuccess;
    }else if(res.compress_coil_1 < compressorValues[res.telemetria_id]){
        evaluacionCompressor = iconDown;
    }
    $('#compressor_icon_'+res.telemetria_id).html(evaluacionCompressor);

    if(evaporatorValues[res.telemetria_id] === undefined){
        evaporatorValues[res.telemetria_id] = res.evaporation_coil
    }
    let evaluacionEvaporator;
    if(res.evaporation_coil > evaporatorValues[res.telemetria_id]){
        evaluacionEvaporator = iconSuccess;
    }else if(res.evaporation_coil < evaporatorValues[res.telemetria_id]){
        evaluacionEvaporator = iconDown;
    }
    $('#evaporator_icon_'+res.telemetria_id).html(evaluacionEvaporator);

    if(ambientValues[res.telemetria_id] === undefined){
        ambientValues[res.telemetria_id] = res.ambient_air
    }
    let evaluacionAmbient;
    if(res.ambient_air > ambientValues[res.telemetria_id]){
        evaluacionAmbient = iconSuccess;
    }else if(res.ambient_air < ambientValues[res.telemetria_id]){
        evaluacionAmbient = iconDown;
    }
    $('#ambient_air_icon_'+res.telemetria_id).html(evaluacionAmbient);

    if(pwdValues[res.telemetria_id] === undefined){
        pwdValues[res.telemetria_id] = res.defrost_prueba
    }
    let evaluacionPwd;
    if(res.defrost_prueba > pwdValues[res.telemetria_id]){
        evaluacionPwd = iconSuccess;
    }else if(res.defrost_prueba < pwdValues[res.telemetria_id]){
        evaluacionPwd = iconDown;
    }
    $('#pwd_icon_'+res.telemetria_id).html(evaluacionPwd);

    if(procesoValues[res.telemetria_id] === undefined){
        procesoValues[res.telemetria_id] = res.stateProcess
    }
    let evaluacionProceso;
    if(res.stateProcess > procesoValues[res.telemetria_id]){
        evaluacionProceso = iconSuccess;
    }else if(res.stateProcess < procesoValues[res.telemetria_id]){
        evaluacionProceso = iconDown;
    }
    $('#proceso_icon_'+res.telemetria_id).html(evaluacionProceso);

    if(cmodeValues[res.telemetria_id] === undefined){
        cmodeValues[res.telemetria_id] = res.controlling_mode
    }
    let evaluacionCmode;    
    if(res.controlling_mode > cmodeValues[res.telemetria_id]){
        evaluacionCmode = iconSuccess;
    }else if(res.controlling_mode < cmodeValues[res.telemetria_id]){
        evaluacionCmode = iconDown;
    }
    $('#c_mode_icon_'+res.telemetria_id).html(evaluacionCmode);

    if(usda1Values[res.telemetria_id] === undefined){
        usda1Values[res.telemetria_id] = res.cargo_1_temp
    }
    let evaluacionUsda1;
    if(res.cargo_1_temp > usda1Values[res.telemetria_id]){
        evaluacionUsda1 = iconSuccess;
    }else if(res.cargo_1_temp < usda1Values[res.telemetria_id]){
        evaluacionUsda1 = iconDown;
    }
    $('#usda_1_icon_'+res.telemetria_id).html(evaluacionUsda1);
    
    if(usda2Values[res.telemetria_id] === undefined){
        usda2Values[res.telemetria_id] = res.cargo_2_temp
    }
    let evaluacionUsda2;
    if(res.cargo_2_temp > usda2Values[res.telemetria_id]){
        evaluacionUsda2 = iconSuccess;
    }else if(res.cargo_2_temp < usda2Values[res.telemetria_id]){
        evaluacionUsda2 = iconDown;
    }
    $('#usda_2_icon_'+res.telemetria_id).html(evaluacionUsda2);
    

    $('#fechita_'+res.telemetria_id).text(res.ultima_fecha);
    $('#ethyleno_'+res.telemetria_id).text(res.ethylene +"ppm");
    let co2V = res.co2_reading;
    if(co2V>=0  && co2V<=30){
        $('#co2_'+res.telemetria_id).text(co2V + "%");
    }else{
        $('#co2_'+res.telemetria_id).text('NA %');
    }
    $('#supply_'+res.telemetria_id).text(res.temp_supply_1+"F°");
    $('#return_'+res.telemetria_id).text(res.return_air+"F°");
    $('#humidity_'+res.telemetria_id).text(res.relative_humidity+"%");
    $('#i_hours_'+res.telemetria_id).text(res.ripener_prueba);
    $('#avl_'+res.telemetria_id).text(res.avl+"CFM");
    $('#compressor_'+res.telemetria_id).text(res.compress_coil_1+"F°");
    $('#evaporator_'+res.telemetria_id).text(res.evaporation_coil+"F°");
    $('#ambient_air_'+res.telemetria_id).text(res.ambient_air+"F°");
    $('#pwd_'+res.telemetria_id).text(res.defrost_prueba);
    $('#proceso_'+res.telemetria_id).text(res.stateProcess);
    $('#c_mode_'+res.telemetria_id).text(res.controlling_mode);
    $('#usda_1_'+res.telemetria_id).text(res.cargo_1_temp+"F°");
    $('#usda_2_'+res.telemetria_id).text(res.cargo_2_temp+"F°");
    
    /*
    $('#temp1_'+res.telemetria_id).text(res.temp_supply_1);
    $('#return_'+res.telemetria_id).text(res.return_air);
    $('#s_temp_'+res.telemetria_id).val(res.set_point);
    $('#humd_'+res.telemetria_id).text(res.relative_humidity);
    $('#evap_'+res.telemetria_id).text(res.evaporation_coil);
    $('#s_humd_'+res.telemetria_id).val(res.humidity_set_point);
    $('#cargo1_'+res.telemetria_id).text(res.cargo_1_temp);
    $('#cargo2_'+res.telemetria_id).text(res.cargo_2_temp);
    $('#cargo3_'+res.telemetria_id).text(res.cargo_3_temp);
    $('#cargo4_'+res.telemetria_id).text(res.cargo_4_temp);
    $('#etileno_'+res.telemetria_id).text(res.ethylene);
    $('#sp_etileno_'+res.telemetria_id).val(res.sp_ethyleno);
    $('#co2_'+res.telemetria_id).text(res.co2_reading);
    $('#sp_co2_'+res.telemetria_id).val(res.set_point_co2);
    $('#h_inyeccion_'+res.telemetria_id).text(res.ripener_prueba);
    $('#n_apertura_'+res.telemetria_id).text(res.avl);
    $('#compresor_'+res.telemetria_id).text(res.compress_coil_1);
    $('#defrost_prueba_'+res.telemetria_id).text(res.defrost_prueba);*/
}

function registrarRespuesta(e) {
    e.preventDefault();
    const url = base_url + "AdminPage/registrar";
    const frm = document.getElementById("frmRegistrar");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                frm.reset();
                tblFormulario.ajax.reload();
                alertas(res.msg, res.icono);
        }
    }
}

