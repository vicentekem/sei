<div class="row">

    <form action="" class="row w-100">

        <div class="form-group col-xl-1 col-lg-2 col-md-2 col-6">
            <label for="cbx_anio_g">Año</label>
            <select onchange="loadGeneralData();" name="anio" id="cbx_anio_g" class="form-control">
                <?php
                $year = date('Y');
                for ($i = 0; $i <= 6; $i++) {
                    $val = $year + ($i - 1);
                    echo "<option value='$val'  " . ( ($val == $year)? "selected" : "" ) . " >$val</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group col-xl-4 col-lg-5 col-md-6 col-12">
            <label for="cbx_grupo_g">Grupo</label>
            <select onclick="loadGeneralData()" name="anio" id="cbx_grupo_g" class="form-control">
                <?php

                $repository = new TablaRepository();

                $result = $repository->getAllByTable("2");

                for ($i = 0; $i < count($result["rows"]); $i++) {
                    echo "<option value= {$result['rows'][$i]['id']} >{$result['rows'][$i]['descripcion']}</option>";
                }

                ?>
            </select>
        </div>

   </form>

</div>

<div class="row">

    <div id="title_general_data_loaded" class=" col-12 text-center text-bold my-3"></div>

    <div id="vgGeneral" class="table-responsive">

    </div>

</div>