function initJsGrid(){
    jsGrid.locale("es");
    $("#lista_areas").jsGrid(
        {
            width: "100%",
            //height: "500px",
            paging: true,
            pageSize: 30,
            pageIndex: 1,
            autoload: true,
            pageLoading: true,
            fields:
                [
                    {name: "id", title: "ID", type: "text", width: 15},
                    {name: "desc", title: "Descripcion", type: "text", width: 80},
                    {
                        itemTemplate: function (_, item) {
                            var el = $("<button>").attr({
                                type: "button",
                                class:"btn btn-sm btn-warning btn-edit"
                            }).on('click', function () {

                                $("#action").val("upd");
                                $("#txt_id_area").val( item.id );
                                $("#txt_desc").val( item.desc );

                                $("#areaModal").modal("show");

                            });
                            el.text("Editar");
                            return el;

                        },
                        width: 20
                    }
                ],
            controller:
                {
                    loadData: function (filter) {

                        var d = $.Deferred();

                        filter.desc = $("#txt_buscar_area").val();
                        filter.action = "qry";
                        filter.table_id = 16;

                        $.ajax(
                            {
                                url: "../controllers/TablaTipoAjaxController.php",
                                data: filter,
                                dataType: "json"
                            }
                        ).done(
                            function (response) {

                                var data = {
                                    data: response.rows,
                                    itemsCount: response.total_filas
                                };

                                d.resolve(data);
                            }
                        );

                        //Returna el resultado diferido
                        return d.promise();
                    }
                }
        }
    );
}

function updateArea(e){
    e.preventDefault();
    var action = $("#action").val();
    var id_tipo = $("#txt_id_area").val();
    var desc = $("#txt_desc").val();

    var errorMessage = "";

    if(!action)  errorMessage = "El action es requerido";
    else if(!desc)  errorMessage = "La descripción es requerida";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    $.ajax({
        url: "../controllers/TablaTipoAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action : action,
            id_tipo:id_tipo,
            desc: desc,
            id_tabla: 16
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarArea();

                $("#action").val("ins");
                $("#txt_id_area").val("");
                $("#txt_desc").val("");

                $("#areaModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });
}

function insertArea(e){
    e.preventDefault();
    var action = $("#action").val();
    var desc =  $("#txt_desc").val();

    var errorMessage = "";

    if(!action)  errorMessage = "El action es requerido";
    else if(!desc)  errorMessage = "La descripción es requerida";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    $.ajax({
        url: "../controllers/TablaTipoAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action : action,
            desc: desc,
            id_tabla: 16
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarArea();

                $("#action").val("ins");
                $("#txt_id_area").val("");
                $("#txt_desc").val("");

                $("#areaModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}
function buscarArea(){
    $("#lista_areas").jsGrid("search");
}
function guardarArea(e){
    var action = $("#action").val();
    if(action === "ins"){
        insertArea(e);
    }else if(action === "upd"){
        updateArea(e);
    }
}

function openModal(){
    $("#action").val("ins");
    $("#txt_id_area").val("");
    $("#txt_desc").val("");

    $("#areaModal").modal("show");
}











