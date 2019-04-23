<?php

if(!isset( $_SESSION["usuario"] )){
    header('location:?url=login');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "partials/head.php"; ?>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php
    include "partials/navbar.php";
    include "partials/menu.php";
    ?>

    <div class="content-wrapper bg-white">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> Cargar datos </h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content w-100">
            <div class="container-fluid">

                <div class="row">

                    <form action="" class="row w-100">

                        <div class="form-group col-xl-1 col-lg-2 col-md-2 col-6">
                            <label for="cbx_anio">Año</label>
                            <select name="anio" id="cbx_anio" class="form-control">
                                <?php
                                $year = date('Y');
                                for ($i = 0; $i <= 6; $i++) {
                                    $val = $year + $i;
                                    echo "<option value='$val' >$val</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-6">
                            <label for="cbx_mes">Mes</label>
                            <select name="anio" id="cbx_mes" class="form-control">
                                <?php

                                $meses = [
                                    "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO",
                                    "AGOSTO", "SETIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
                                ];

                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<option value='$i' >{$meses[$i - 1]}</option>";
                                }

                                ?>
                            </select>
                        </div>

                        <div class="form-group col-xl-3 col-lg-3 col-md-4 col-9">
                            <label for="fl_excel">seleccione archivo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="fl_excel">
                                    <label class="custom-file-label" for="fl_excel"></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-3">
                            <label for="btn_load" class="d-block" style="opacity: 0">Cargar</label>
                            <input type="submit" class="btn btn-default" onclick="loadData(event)" id="btn_load"
                                   value="Cargar">
                        </div>
                    </form>

                </div>

                <div class="row">

                    <div id="datosExcel" class="table-responsive">

                    </div>

                </div>

            </div>
        </section>
    </div>
</div>

<script id="datos_excel_template" type="x-tmpl-mustache">

    <div class="form-group">
        <button class="btn btn-success" onclick="saveData();" >Guardar Datos</button>
        <button class="btn btn-danger" onclick="clearData();" >Cancelar</button>
    </div>

    <table class="table table-bordered text-sm w-75">
        <thead>
            <tr>

               <th style="text-align:center"> ESTABLECIMIENTOS DE SALUD </th>
               <th rowspan="2"> META ANUAL </th>
               <th rowspan="2"> META MENSUAL</th>
               <th colspan="5" class="text-center" >EJECUCION</th>
               <th rowspan="2"> AVANCE DEL MES %</th>
            </tr>
            <tr>
                <th></th>
                <th> PENTA </th>
                <th> ROTA </th>
                <th> NEUMO </th>
                <th> APO </th>
                <th> SPR </th>
            </tr>
        </thead>

        <tbody>

            {{#data}}
                <tr>
                    <td >{{establecimiento}}</td>
                    <td style="width:21px;">{{meta_anual}}</td>
                    <td style="width:21px;">{{meta_mensual_int}}</td>
                    <td style="width:21px;">{{penta}}</td>
                    <td style="width:21px;">{{rota}}</td>
                    <td style="width:21px;">{{neumo}}</td>
                    <td style="width:21px;">{{apo}}</td>
                    <td style="width:21px;">{{spr}}</td>
                    <td style="width:150px;text-align:center" class="{{bg_class}}" >{{avance}} % </td>
                </tr>
            {{/data}}

        </tbody>
    </table>


</script>

<?php include "partials/scripts.php"; ?>

<script src="public/js/vacunas.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadLocalFile();
    });
</script>

</body>
</html>









