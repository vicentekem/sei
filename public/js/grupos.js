function initJsGrid(){
    jsGrid.locale("es");
    $("#lista_grupos").jsGrid(
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
                                $("#txt_id_grupo").val( item.id );
                                $("#txt_desc").val( item.desc );

                                $("#grupoModal").modal("show");

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

                        filter.desc = $("#txt_buscar_grupo").val();
                        filter.action = "qry";
                        filter.table_id = 2;

                        $.ajax(
                            {
                                url: "controllers/TablaTipoAjaxController.php",
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

function updateGrupo(e){
    e.preventDefault();
    var action = $("#action").val();
    var id_tipo = $("#txt_id_grupo").val();
    var desc = $("#txt_desc").val();

    var errorMessage = "";

    if(!action)  errorMessage = "El action es requerido";
    else if(!desc)  errorMessage = "La descripción es requerida";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    $.ajax({
        url: "controllers/TablaTipoAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action : action,
            id_tipo:id_tipo,
            desc: desc,
            id_tabla: 2
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarGrupo();

                $("#action").val("ins");
                $("#txt_id_grupo").val("");
                $("#txt_desc").val("");

                $("#grupoModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });
}

function insertGrupo(e){
    e.preventDefault();
    var action = $("#action").val();
    var desc =  $("#txt_desc").val();

    var errorMessage = "";

    if(!action)  errorMessage = "El action es requerido";
    else if(!desc)  errorMessage = "La descripción es requerida";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    $.ajax({
        url: "controllers/TablaTipoAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action : action,
            desc: desc,
            id_tabla: 2
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarGrupo();

                $("#action").val("ins");
                $("#txt_id_grupo").val("");
                $("#txt_desc").val("");

                $("#grupoModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}
function buscarGrupo(){
    $("#lista_grupos").jsGrid("search");
}

function guardarGrupo(e){
    var action = $("#action").val();
    if(action === "ins"){
        insertGrupo(e);
    }else if(action === "upd"){
        updateGrupo(e);
    }
}

function openModal(){

    $("#action").val("ins");

    $("#txt_id_grupo").val("");
    $("#txt_desc").val("");

    $("#grupoModal").modal("show");
}











