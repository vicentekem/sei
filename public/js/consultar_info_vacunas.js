function loadTitleData() {

    var mes = $("#cbx_mes option:selected").text();
    var grupo = $("#cbx_grupo option:selected").text();
    var anio = $("#cbx_anio option:selected").text();

    $("#title_data_loaded").text(grupo + " - " + mes + " " + anio);
    $("#th_mes").text(mes);
}

function loadTitleDataGeneral() {

    var grupo = $("#cbx_grupo_g option:selected").text();
    var anio = $("#cbx_anio_g option:selected").text();

    $("#title_general_data_loaded").text(grupo + " - " + " " + anio);
}

function initJsGrid() {
    jsGrid.locale("es");
    initJsGridDetalle();
    initJsGridGeneral();

}

function initJsGridDetalle(){
    $("#vgDetalle").jsGrid(
        {
            width: "100%",

            //height: "500px",
            paging: true,
            pageSize: 10,
            pageIndex: 1,
            autoload: true,
            pageLoading: true,
            fields:
                [
                    {name: "ris", title: "RIS", type: "text", width: 100},
                    {name: "establecimiento", title: "ESTABLECIMIENTO", type: "text", width: 500},
                    {name: "meta_anual", title: "META ANUAL", type: "text", width: 100},
                    {name: "penta", title: "PENTA", type: "text", width: 80},
                    {name: "rota", title: "ROTA", type: "text", width: 80},
                    {name: "neumo", title: "NEUMO", type: "text", width: 80},
                    {name: "rota", title: "ROTA", type: "text", width: 80},
                    {name: "apo", title: "APO", type: "text", width: 80},
                    {name: "spr", title: "SPR", type: "text", width: 80},
                    {name: "avance_mensual", title: "AVANCE MENSUAL", type: "text", width: 100},
                    {
                        name: "avance_acumulado",
                        title: "AVANCE HASTA <span id='th_mes'>" + $("#cbx_mes option:selected").text() + "</span>",
                        type: "text",
                        width: 100
                    },
                    {name: "avance_acumulado_total", title: "AVANCE TOTAL", type: "text", width: 100}
                ],
            controller:
                {
                    loadData: function (filter) {

                        var d = $.Deferred();

                        filter.anio = $("#cbx_anio").val();
                        filter.mes = $("#cbx_mes").val();
                        filter.grupo = $("#cbx_grupo").val();
                        filter.action = "get_vg_detalle";

                        $.ajax(
                            {
                                url: "controllers/VacunasAjaxController.php",
                                data: filter,
                                dataType: "json"
                            }
                        ).done(
                            function (response) {

                                var data = {
                                    data: response.rows,
                                    itemsCount: response.total_rows
                                };

                                d.resolve(data);
                            }
                        );

                        return d.promise();
                    }
                }
        }
    );
}

function initJsGridGeneral(){
    $("#vgGeneral").jsGrid(
        {
            width: "100%",
            //height: "500px",
            paging: true,
            pageSize: 100,
            pageIndex: 1,
            autoload: true,
            pageLoading: true,
            fields:
                [
                    {name: "ris", title: "RIS", type: "text", width: 60},
                    {name: "establecimiento", title: "ESTABLECIMIENTO", type: "text", width: 400},
                    {name: "meta_1", title: "ENE", type: "text", width:  60},
                    {name: "meta_2", title: "FEB", type: "text", width:  60},
                    {name: "meta_3", title: "MAR", type: "text", width:  60},
                    {name: "meta_4", title: "ABR", type: "text", width:  60},
                    {name: "meta_5", title: "MAY", type: "text", width:  60},
                    {name: "meta_6", title: "JUN", type: "text", width:  60},
                    {name: "meta_7", title: "JUL", type: "text", width:  60},
                    {name: "meta_8", title: "AGO", type: "text", width:  60},
                    {name: "meta_9", title: "SET", type: "text", width:  60},
                    {name: "meta_10", title: "OCT", type: "text", width: 60},
                    {name: "meta_11", title: "NOV", type: "text", width: 60},
                    {name: "meta_12", title: "DIC", type: "text", width: 60},
                    {name: "avance_total", title: "TOTAL", type: "text", width: 80}
                ],
            controller:
                {
                    loadData: function (filter) {

                        var d = $.Deferred();

                        filter.anio = $("#cbx_anio_g").val();
                        filter.grupo = $("#cbx_grupo_g").val();
                        filter.action = "get_vg_general";

                        $.ajax(
                            {
                                url: "controllers/VacunasAjaxController.php",
                                data: filter,
                                dataType: "json"
                            }
                        ).done(
                            function (response) {

                                var data = {
                                    data: response.rows,
                                    itemsCount: response.total_rows
                                };

                                d.resolve(data);
                            }
                        );

                        return d.promise();
                    }
                }
        }
    );
}

function loadData(e) {
    if (e) e.preventDefault();

    loadTitleData();

    $("#vgDetalle").jsGrid("search");
}


function loadGeneralData(e) {
    if (e) e.preventDefault();

    loadTitleDataGeneral();

    $("#vgGeneral").jsGrid("search");
}












