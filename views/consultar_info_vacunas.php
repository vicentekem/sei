<?php

require_once "config/Conexion.php";
require_once "models/EstablecimientoRepository.php";
require_once "models/TablaRepository.php";

if(!isset( $_SESSION["usuario"] )){
    header('location:?url=login');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "partials/head.php"; ?>
    <link rel="stylesheet" href="public/css/tabs.css">

</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php
    include "views/partials/navbar.php";
    include "views/partials/menu.php";
    ?>

    <div class="content-wrapper bg-white">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> Consultar - Vacuna General</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content w-100">
            <div class="container-fluid">


                <div class="tabset">
                    <!-- Tab 1 -->
                    <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
                    <label for="tab1">General</label>
                    <!-- Tab 2 -->
                    <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
                    <label for="tab2">Detalle</label>
                    <!-- Tab 3 -->
<!--                    <input type="radio" name="tabset" id="tab3" aria-controls="dunkles">-->
<!--                    <label for="tab3"> Bock</label>-->

                    <div class="tab-panels">
                        <section id="" class="tab-panel"> <?php include_once "partials/vacunas/vg_general.php";?> </section>
                        <section id="" class="tab-panel"> <?php include_once "partials/vacunas/vg_detalle.php";?> </section>
                    </div>

                </div>

            </div>

        </section>

    </div>

</div>

<?php include "partials/scripts.php"; ?>

<script src="public/js/consultar_info_vacunas.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        //loadLocalFile();
        initJsGrid();
        loadTitleData();
        loadTitleDataGeneral();
    });
</script>
</body>
</html>









