var rol = { };

rol.id  = 1;
rol.accesos = [];

function initJsGrid(){
    jsGrid.locale("es");
    initJsGridRoles();

}

function initJsGridRoles(){
    $("#lista_roles").jsGrid(
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
                                $("#txt_id_rol").val( item.id );
                                $("#txt_desc").val( item.desc );

                                $("#rolModal").modal("show");

                            });
                            el.text("Editar");

                            return el;

                        },
                        width: 20
                    },
                    {
                        //Editar los accesos :
                        itemTemplate: function (_, item) {
                            var el = $("<button>").attr({
                                type: "button",
                                class:"btn btn-sm btn-info btn-edit"
                            }).on('click', function () {

                                rol.id = item.id;

                                $("#txt_id_rol_permiso").val( item.id );

                                loadAccesos( rol.id );

                                $("#accesosModal").modal("show");

                            });
                            el.text("Accesos");

                            return el;

                        },
                        width: 20
                    }

                ],
            controller:
                {
                    loadData: function (filter) {

                        var d = $.Deferred();

                        filter.desc = $("#txt_buscar_rol").val();
                        filter.action = "qry";
                        filter.table_id = 3;

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

function updaterol(e){
    e.preventDefault();
    var action = $("#action").val();
    var id_tipo = $("#txt_id_rol").val();
    var desc = $("#txt_desc").val().trim();

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
            id_tabla: 3
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarrol();

                $("#action").val("ins");
                $("#txt_id_rol").val("");
                $("#txt_desc").val("");

                $("#rolModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });
}

function insertrol(e){
    e.preventDefault();
    var action = $("#action").val();
    var desc =  $("#txt_desc").val().trim();

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
            id_tabla: 3
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarrol();

                $("#action").val("ins");
                $("#txt_id_rol").val("");
                $("#txt_desc").val("");

                $("#rolModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}
function buscarrol(){
    $("#lista_roles").jsGrid("search");
}

function guardarrol(e){
    var action = $("#action").val();
    if(action === "ins"){
        insertrol(e);
    }else if(action === "upd"){
        updaterol(e);
    }
}

function openModal(){

    $("#action").val("ins");

    $("#txt_id_rol").val("");
    $("#txt_desc").val("");

    $("#rolModal").modal("show");
}

function loadAccesos( id_rol ){
    $.ajax({
        url: "controllers/AccesosAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action : "qry_accesos",
            id_rol:id_rol
        },
        processData: true,
        success: function (result) {
            rol.accesos = result.rows;

            loadDataToTemplate("accesos_template","lista_accesos", result.rows );
            // if (result.error === "") {
            //     loadDataToTemplate("accesos_template","lista_accesos", result.rows );
            //     return 0;
            // }
            // showMessage(result.error, "error");
        }
    });
}

function loadSecciones(){

    $.ajax({
        url: "controllers/MenuAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action : "qry_menu"
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                return loadDataToTemplate("cbx_template","cbx_sec",result.rows);
            }

            showMessage(result.error, "error");
        }
    });

}

function loadSubmenu(){

    $.ajax({
        url: "controllers/MenuAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action : "qry_sub_menu",
            id_menu: $("#cbx_sec").val()
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {
                return loadDataToTemplate("cbx_template","cbx_acc",result.rows);
            }

            //showMessage(result.error, "error");
        }
    });

}

function addAcceso(e){

    if(e) e.preventDefault();

    var sec = $("#cbx_sec").val().trim();
    var acc = $("#cbx_acc").val().trim();

    var errorMessage = "";

    if( sec === "" || sec === "*" ) errorMessage = "Seleccione sección";
    else if( acc === "" || acc === "*" ) errorMessage = "Seleccione acceso";

    if(errorMessage !== "") return showMessage(errorMessage,"error");

    var acceso = rol.accesos.find( function (acceso) {
        return acceso.id === acc ;
    } );

    if(!acceso){

        rol.accesos.push( {
            id_rol: rol.id,
            id: $("#cbx_acc").val(),
            desc: $("#cbx_acc option:selected").text(),
            desc_pad: $("#cbx_sec option:selected").text()
        } );
        console.log( rol.accesos );

        loadDataToTemplate("accesos_template","lista_accesos", rol.accesos );

    }

}

function quitarAcceso(id,e){

    if(e) e.preventDefault();

    var index = rol.accesos.findIndex( function (acceso) {
        return acceso.id === id ;
    } );

    if(index){

        rol.accesos.splice( index , 1 );
        loadDataToTemplate("accesos_template","lista_accesos", rol.accesos );
    }

}

function guardarAcceso(e){
    if(e) e.preventDefault();

    $.ajax({
        url: "controllers/AccesosAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action : "save_accesos",
            id_rol: rol.id,
            accesos: rol.accesos
        } ,
        processData: true,
        success: function (result) {

            if (result.error === "") {

                $("#accesosModal").modal("hide");

                return showMessage("Se guardaron los datos correctamente","success");
            }

            showMessage(result.error, "error");
        }
    });

}
















