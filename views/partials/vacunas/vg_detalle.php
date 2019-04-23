<div class="row">

    <form action="" class="row w-100">

        <div class="form-group col-xl-1 col-lg-2 col-md-2 col-6">
            <label for="cbx_anio">Año</label>
            <select onchange="loadData();" name="anio" id="cbx_anio" class="form-control">
                <?php
                $year = date('Y');
                for ($i = 0; $i <= 6; $i++) {
                    $val = $year + ($i - 1);
                    echo "<option value='$val'  " . ( ($val == $year)? "selected" : "" ) . " >$val</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-6">
            <label for="cbx_mes">Mes</label>
            <select onclick="loadData()" name="anio" id="cbx_mes" class="form-control">
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

<!--        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-6">-->
<!--            <label for="cbx_mes">Establecimiento</label>-->
<!--            <select name="anio" id="cbx_mes" class="form-control">-->
<!--                --><?php
//
//                $establecimientosRepository = new EstablecimientoRepository();
//
//                $establecimientos = $establecimientosRepository->getAllEstablecimientos();
//
//                for ($i = 0; $i < count($establecimientos["rows"]); $i++) {
//                    echo "<option value= {$establecimientos['rows'][$i]['id']} >{$establecimientos['rows'][$i]['descripcion']}</option>";
//                }
//
//                ?>
<!--            </select>-->
<!--        </div>-->

        <div class="form-group col-xl-4 col-lg-5 col-md-6 col-12">
            <label for="cbx_grupo">Grupo</label>
            <select onclick="loadData()" name="anio" id="cbx_grupo" class="form-control">
                <?php

                $repository = new TablaRepository();

                $result = $repository->getAllByTable("2");

                for ($i = 0; $i < count($result["rows"]); $i++) {
                    echo "<option value= {$result['rows'][$i]['id']} >{$result['rows'][$i]['descripcion']}</option>";
                }

                ?>
            </select>
        </div>

<!--        <div class="form-group col-3">-->
<!--            <label for="btn_load" class="d-block" style="opacity: 0">Buscar</label>-->
<!--            <input type="submit" class="btn btn-info" onclick="loadData(event)" id="btn_load"-->
<!--                   value="Ver">-->
<!--        </div>-->

    </form>

</div>

<div class="row">

    <div id="title_data_loaded" class=" col-12 text-center text-bold my-3"></div>

    <div id="vgDetalle" class="table-responsive">

    </div>

</div>