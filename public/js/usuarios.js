function initJsGrid() {
    jsGrid.locale("es");
    $("#lista_usuarios").jsGrid(
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
                    {name: "id_usuario", title: "ID", type: "text", width: 40},
                    {name: "nombre_completo", title: "Nombre Completo", type: "text", width: 200},
                    {name: "nombre_usuario", title: "Nombre de Usuario", type: "text", width: 100},
                    {name: "email", title: "Email", type: "text", width: 200},
                    {name: "dni", title: "DNI", type: "text", width: 130},
                    {
                        itemTemplate: function (_, item) {
                            return $("<button>").attr({
                                type: "button",
                                class: "btn btn-sm btn-warning fa fa-edit btn-edit"
                            }).on('click', function () {

                                $("#action").val("upd");
                                $("#modalUserTitle").text("Editar Usuario");
                                $("#txt_id_usuario").val(item.id_usuario);
                                $("#content_roles").css({display: 'none'});

                                $("#txt_dni").val(item.dni);
                                $("#txt_nombres").val(item.nombres);
                                $("#txt_apellido_pat").val(item.apellido_pat);
                                $("#txt_apellido_mat").val(item.apellido_mat);
                                $("#txt_username").val(item.nombre_usuario);
                                $("#txt_email").val(item.email);

                                $("#usuarioModal").modal("show");

                            });
                        },
                        width: 50
                    }
                ],
            controller:
                {
                    loadData: function (filter) {

                        var d = $.Deferred();

                        filter.desc = $("#txt_buscar_usuario").val();
                        filter.action = "qry_usuario";

                        $.ajax(
                            {
                                url: "controllers/UsuarioController.php",
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

function updateUsuario(e) {

    e.preventDefault();

    var action = $("#action").val();
    var id_usuario = $("#txt_id_usuario").val().trim();
    var dni = $("#txt_dni").val().trim();
    var nombres = $("#txt_nombres").val().trim();
    var apellido_pat = $("#txt_apellido_pat").val().trim();
    var apellido_mat = $("#txt_apellido_mat").val().trim();
    var nombre_usuario = $("#txt_username").val().trim();
    var email = $("#txt_email").val().trim();

    var errorMessage = "";

    if (!action) errorMessage = "El action es requerido";
    else if (!dni) errorMessage = "El DNI es requerido";
    else if (!isValidNumberAndFixedLength(dni, 8)) errorMessage = "DNI no válido";
    else if (!nombres) errorMessage = "El nombre es requerido";
    else if (!apellido_pat) errorMessage = "El apellido paterno es requerido";
    else if (!apellido_mat) errorMessage = "El apellido materno es requerido";
    else if (!nombre_usuario) errorMessage = "El usuario es requerido";
    else if (!email) errorMessage = "El email es requerido";

    if (errorMessage !== "") return showMessage(errorMessage, "error");

    $.ajax({
        url: "controllers/UsuarioController.php",
        dataType: "json",
        type: "post",
        data: {
            action: action,
            id_usuario:id_usuario,
            dni: dni,
            nombres: nombres,
            apellido_pat: apellido_pat,
            apellido_mat: apellido_mat,
            nombre_usuario: nombre_usuario,
            email: email
        },
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarUsuario();

                $("#action").val("ins");
                $("#txt_id_usuario").val("");

                $("#usuarioModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}

function insertUsuario(e) {
    e.preventDefault();

    var action = $("#action").val();
    var dni = $("#txt_dni").val().trim();
    var nombres = $("#txt_nombres").val().trim();
    var apellido_pat = $("#txt_apellido_pat").val().trim();
    var apellido_mat = $("#txt_apellido_mat").val().trim();
    var nombre_usuario = $("#txt_username").val().trim();
    var email = $("#txt_email").val().trim();
    var id_rol = $("#cbx_roles").val().trim();

    var errorMessage = "";

    if (!action) errorMessage = "El action es requerido";
    else if (!dni) errorMessage = "El DNI es requerido";
    else if (!isValidNumberAndFixedLength(dni, 8)) errorMessage = "DNI no válido";
    else if (!nombres) errorMessage = "El nombre es requerido";
    else if (!apellido_pat) errorMessage = "El apellido paterno es requerido";
    else if (!apellido_mat) errorMessage = "El apellido materno es requerido";
    else if (!nombre_usuario) errorMessage = "El usuario es requerido";
    else if (!email) errorMessage = "El email es requerido";
    else if (!id_rol) errorMessage = "Seleccione el rol de usuario";

    if (errorMessage !== "") return showMessage(errorMessage, "error");

    $.ajax({
        url: "controllers/UsuarioController.php",
        dataType: "json",
        type: "post",
        data: {
            action: action,
            dni: dni,
            nombres: nombres,
            apellido_pat: apellido_pat,
            apellido_mat: apellido_mat,
            nombre_usuario: nombre_usuario,
            email: email,
            id_rol : id_rol
        },
        processData: true,
        success: function (result) {

            if (result.error === "") {
                buscarUsuario();
                $("#action").val("ins");
                $("#txt_id_usuario").val("");

                $("#usuarioModal").modal("hide");
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}

function buscarUsuario() {
    $("#lista_usuarios").jsGrid("search");
}

function guardarUsuario(e) {
    var action = $("#action").val();
    if (action === "ins") {
        insertUsuario(e);
    } else if (action === "upd") {
        updateUsuario(e);
    }
}

function openModal() {

    $("#action").val("ins");
    $("#modalUserTitle").text("Nuevo Usuario");
    $("#txt_id_usuario").val("");
    $("#content_roles").css({display: 'block'});

    $("#txt_dni").val("");
    $("#txt_nombres").val("");
    $("#txt_apellido_pat").val("");
    $("#txt_apellido_mat").val("");
    $("#txt_username").val("");
    $("#txt_email").val("");

    $("#usuarioModal").modal("show");
}











