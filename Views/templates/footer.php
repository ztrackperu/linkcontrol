    </div>
</div>

<script src="<?php echo base_url; ?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<!-- BORRE EL DATATABLE DE ESTA PARTE PORQUE GENERABA CONFLICO -->
 <!-- JQUERY UI-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- DataTables JS CDN -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script  src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<!-- Botones de DataTables JS CDN -->
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<!-- genera el pdf -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>

<script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/select2.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/axios.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/hammer.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/chartjs-plugin-zoom.min.js"></script>

<script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/4.3.0/jquery.bootstrap-touchspin.min.js"></script>

<!--leaflet -->
<script src="<?php echo base_url; ?>Assets/js/leaflet.js"></script> 
<script src ="<?php echo base_url; ?>Assets/js/leaflet.markercluster.js" > </script>
<script src="<?php echo base_url; ?>Assets/js/leaflet.awesome-markers.js"></script>

<!-- <script src="../Assets/js/main.js"></script>-->
<script>
        const base_url = '<?php echo base_url; ?>';
        const empresa_id = '<?php echo  $empresa_id= isset( $_SESSION['empresa_id'] ) ?  $_SESSION['empresa_id'] : 0 ; ?>';
        const id_ztrack = '<?php echo  $empresa_id= isset( $_SESSION['id_ztrack']) ?  $_SESSION['id_ztrack']: 0 ; ?>';
        //console.log(base_url);
        
        // SIDEBAR TOGGLE 
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("expand");
        });

        const closeSidebar = document.querySelector(".toggle-btn2");

        closeSidebar.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.remove("expand");
        });

        // FULLSCREEN
        document.getElementById('fScreen').addEventListener('click', function (event) {
            event.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                document.querySelector("#sidebar").classList.toggle("expand");
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });    

        //Mostrar GIF
        document.querySelectorAll('.sidebar-item').forEach(link => {
            link.addEventListener('click', function() {
                $("#loading").show();
            });
        });
</script>
<?php
if (!empty($_GET['url'])) {
    //echo "jaja".$_GET['url'] ;
    $cadena = explode("/",$_GET['url']);
    $script = $cadena[0] . '.js';
    //echo $script;
    if (file_exists('Assets/js/' . $script)) {
        echo '<script src="'. base_url . 'Assets/js/' . $script .'"></script>';
    }else{
        echo '<script src="'. base_url . 'Assets/js/funciones.js"></script>';
    }
}else{
    echo '<script src="'. base_url . 'Assets/js/funciones.js"></script>';
} 
?>
<script src="<?php echo base_url;?>Assets/js/generales.js"></script>
</body>
</html>