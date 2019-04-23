
function initJsGrid(){
    jsGrid.locale("es");
    $("#lista_convocatorias").jsGrid(
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
                    {name: "codigo", title: "Código", type: "text", width: 28},
                    {name: "area", title: "Area", type: "text", width: 80},
                    {name: "titulo", title: "Descripción", type: "text", width: 150},
                    {name: "cantidad", title: "Cantidad", type: "text", width: 20},
                    {name: "inicio", title: "Inicio", type: "text", width: 25},
                    {name: "fin", title: "Fin", type: "text", width: 25},
                    {
                        itemTemplate: function (_, item) {
                            var el = $("<a>").attr({
                                href: "../" + item.tdr,
                                class:"btn btn-sm btn-info",
                                target:"_blank"
                            });

                            el.text("TDR");
                            return el;
                        },
                        width: 20
                    },
                    {
                        itemTemplate: function (_, item) {
                            var el = $("<button>").attr({
                                type: "button",
                                class:"btn btn-sm btn-warning btn-edit"
                            }).on('click', function () {

                                $("#txt_id_conv").val( item.id );
                                $("#cbx_area").val( item.id_area );
                                $("#txt_desc").val( item.titulo );
                                $("#txt_cant").val( item.cantidad );
                                $("#txt_fecha_inicio").val( item.inicio );
                                $("#txt_fecha_fin").val( item.fin);

                                $("#chbx_editar_tdr").prop("checked",false);
                                document.querySelector("#file_tdr").value = "";
                                document.querySelector("#file_tdr").parentElement.querySelector(".custom-file-label").innerText = "";

                                $("#file_tdr").prop("disabled",true);

                                $("#convocatoriaModalUpd").modal("show");

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

                        filter.desc = $("#txt_buscar_convocatoria").val();
                        filter.action = "qry";

                        $.ajax(
                            {
                                url: "../controllers/ConvocatoriaAjaxController.php",
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
function loadCheckBox(){

    $("#chbx_editar_tdr").on("change",function () {

        $("#file_tdr").prop("disabled", !$("#chbx_editar_tdr").prop("checked") );

    });

}
function updateConvocatoria(e){
    e.preventDefault();

    var conv_id = $("#txt_id_conv").val() || 0;
    var area_id = $("#cbx_area").val() || 0;
    var titulo = $("#txt_desc").val().trim();
    var cantidad = $("#txt_cant").val().trim();
    var inicio = $("#txt_fecha_inicio").val();
    var fin = $("#txt_fecha_fin").val();
    var cambiar_tdr = $("#chbx_editar_tdr").prop("checked");
    var file = document.querySelector("#file_tdr").files[0];

    var errorMessage = "";

    if(!conv_id)  errorMessage = "El id de la convocatoria de requerido";
    else if(!area_id)  errorMessage = "El id de la convocatoria de requerido";
    else if(titulo === "")  errorMessage = "La descripcion de la convocatoria es requerido";
    else if(cantidad === "")  errorMessage = "La cantidad es requerida";
    else if(cantidad <= 0) errorMessage = "La cantidad debe ser mayor a cero";
    else if(!inicio)  errorMessage = "La fecha de inicio es requerida";
    else if (isNaN(inicio.split("/").join(""))) errorMessage = "Ingrese la fecha de inicio correctamente";
    else if(!fin)  errorMessage = "La fecha fin es requerida";
    else if (isNaN(fin.split("/").join(""))) errorMessage = "Ingrese fecha de finalización correctamente";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    var data = new FormData();
    data.append("action","upd_conv");
    data.append('id_usuario', $("#id_usuario").val() );
    data.append("conv_id", conv_id );
    data.append("area_id",area_id);
    data.append("titulo",titulo);
    data.append("cantidad",cantidad);
    data.append("inicio",inicio);
    data.append("fin",fin);
    data.append("cambiar_tdr",cambiar_tdr);

    if(cambiar_tdr){
        data.append("file",file);
    }

    $.ajax({
        url: "../controllers/ConvocatoriaAjaxController.php",
        dataType: "json",
        type: "post",
        data: data ,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {

            if (result.error === "") {
                buscarConvocatoria();
                $("#convocatoriaModalUpd").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });


}
function buscarConvocatoria(){
    $("#lista_convocatorias").jsGrid("search");
}
