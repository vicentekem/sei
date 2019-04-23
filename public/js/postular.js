var datosPostulacion = {};
datosPostulacion.sustento = null;
datosPostulacion.formacion = [];
datosPostulacion.especializaciones = [];
datosPostulacion.cursosTecnicos = [];
datosPostulacion.idiomas = [];
datosPostulacion.experiencia = [];
datosPostulacion.referencias = [];
datosPostulacion.publicaciones = [];
datosPostulacion.exposiciones = [];
datosPostulacion.anexos = [];

function validarDatosPersonales() {
    var nacionalidad = $("#cbx_nacionalidad").val().trim();
    var tipo_doc = $("#cbx_tipo_doc").val().trim();
    var estado_civil = $("#cbx_estado_civil").val().trim();
    var departamento = $("#cbx_depa").val().trim();
    var provincia = $("#cbx_prov").val().trim();
    var distrito = $("#cbx_dist").val().trim();
    var tipo_zona = $("#cbx_tipo_zona").val().trim();
    var tipo_via = $("#cbx_tipo_via").val().trim();
    var nro_doc = $("#txt_nro_doc").val().trim();
    var apellido_pat = $("#txt_apellido_pat").val().trim();
    var apellido_mat = $("#txt_apellido_mat").val().trim();
    var nombres = $("#txt_nombres").val().trim();
    var nombre_zona = $("#txt_nombre_zona").val().trim();
    var direccion = $("#txt_direccion").val().trim();
    var celular = $("#txt_cel").val().trim();
    var correo = $("#txt_correo").val().trim();
    var discapacidad = document.form_datos_personales.rbtn_dis.value.trim();
    var licenciado_ffaa = document.form_datos_personales.rbtn_ff_aa.value.trim();

    var errormessage = "";

    if (nacionalidad === "" || tipo_doc === "" || estado_civil === "" || departamento === "" || provincia === "" ||
        distrito === "" || tipo_zona === "" || tipo_via === "" || nro_doc === "" || apellido_pat === "" || apellido_mat === "" ||
        nombres === "" || nombre_zona === "" || direccion === "" || celular === "" || correo === "" || discapacidad === "" || licenciado_ffaa === "")
        errormessage = "Complete el registro de sus datos personales";

    return errormessage;

}

function loadAllData() {
    loadDataToPostulante();
    loadFormacionAcademica();
    if (document.forms.form_exp) loadExperienciaLaboral();
    if (document.forms.form_ref) loadReferencias();
    if (document.forms.form_cursos_especializaciones) loadEspecializaciones();
    if (document.forms.form_cursos_tecnicos) loadCursosTecnicos();
    if (document.forms.form_idiomas) loadIdiomas();
    if (document.forms.form_publicaciones) loadPublicaciones();
    if (document.forms.form_exposiciones) loadExposiciones();
    loadAnexos();
}

// ------------------------------------------------------------------------------------

function setRadiobuttonBooleanValue(nameForm, nameRaidoButton, Booleanvalue) {

    if (Booleanvalue !== null) {
        document[nameForm][nameRaidoButton].value = (Booleanvalue) ? 1 : 0;
    }
}


// ------------------------------------------------------------------------------------
//funciones específicas

function loadDepartamentos(id_depa, id_prov, id_dist) {
    $.ajax({
        url: "controllers/PeruAjaxController.php",
        dataType: "json",
        data: {
            action: "cbx_departamentos"
        },
        success: function (result) {

            loadDataToTemplate('template', 'cbx_depa', result.rows);
            if (id_depa) $("#cbx_depa").val(id_depa);
            loadProvincias(id_prov, id_dist);
        }
    });
}

function loadProvincias(id_prov, id_dist) {
    $.ajax({
        url: "controllers/PeruAjaxController.php",
        dataType: "json",
        data: {
            action: "cbx_provincias",
            id_depa: $("#cbx_depa").val()
        },
        success: function (result) {

            loadDataToTemplate('template', 'cbx_prov', result.rows);
            if (id_prov) $("#cbx_prov").val(id_prov);
            loadDistritos(id_dist);

        }
    });
}

function loadDistritos(id_dist) {
    $.ajax({
        url: "controllers/PeruAjaxController.php",
        dataType: "json",
        data: {
            action: "cbx_distritos",
            id_prov: $("#cbx_prov").val()
        },
        success: function (result) {

            loadDataToTemplate('template', 'cbx_dist', result.rows);
            if (id_dist) $("#cbx_dist").val(id_dist);

        }
    });
}

function loadSustento(val) {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_datos_sustentatorios",
            id_postulante: $("#id_user").val()
        },
        success: function (result) {

            if (result.error !== "") {
                if (val === 1) showMessage(result.error, "info");
                else showMessage(result.error, "error");
            }

            if (result.row) {
                datosPostulacion.sustento = result.row;
                loadDataToTemplate('datos_personales_template', 'tbl_datos_sustentatorios', [result.row]);
            }

        }
    });
}

function loadDataToPostulante() {

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        cache: false,
        data: {
            action: "get_datos_personales",
            id_postulante: $("#id_user").val()
        },
        success: function (result) {

            var row = result.row;

            //seteando los select :
            $("#cbx_nacionalidad").val(row.nacionalidad);
            $("#cbx_tipo_doc").val(row.tipo_doc);
            $("#cbx_estado_civil").val(row.estado_civil);

            loadDepartamentos(row.departamento, row.provincia, row.distrito);

            $("#cbx_tipo_zona").val(row.tipo_zona);
            $("#cbx_tipo_via").val(row.tipo_via);

            //seteando los input type text:
            $("#txt_nro_doc").val(row.nro_doc);
            $("#txt_apellido_pat").val(row.apellido_pat);
            $("#txt_apellido_mat").val(row.apellido_mat);
            $("#txt_nombres").val(row.nombres);
            $("#txt_nombre_zona").val(row.nombre_zona);
            $("#txt_direccion").val(row.direccion);
            $("#txt_tel").val(row.telefono);
            $("#txt_cel").val(row.celular);
            $("#txt_correo").val(row.correo);

            //seteando los radio buttons:
            setRadiobuttonBooleanValue("form_datos_personales", "rbtn_dis", row.discapacidad);
            setRadiobuttonBooleanValue("form_datos_personales", "rbtn_ff_aa", row.licenciado_ffaa);

            loadSustento(1);
        }
    });
}

function loadFormacionAcademica() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_formacion_academica",
            id_postulante: $("#id_user").val()
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.formacion = result.rows;
                loadDataToTemplate('formacion_academica_template', 'tbl_formacion_academica', result.rows);
            }
        }
    });
}

function loadEspecializaciones() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_conocimiento",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id"),
            tipo_conocimiento: '1'
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.especializaciones = result.rows;
                loadDataToTemplate('esp_template', 'tbl_esp', result.rows);
            }
        }
    });
}

function loadCursosTecnicos() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_conocimiento",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id"),
            tipo_conocimiento: '2'
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.cursosTecnicos = result.rows;
                loadDataToTemplate('tec_template', 'tbl_tec', result.rows);
            }
        }
    });
}

function loadIdiomas() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_conocimiento",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id"),
            tipo_conocimiento: '3'
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.idiomas = result.rows;
                loadDataToTemplate('idioma_template', 'tbl_idioma', result.rows);
            }
        }
    });
}

function loadExperienciaLaboral() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_exp",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id")
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.experiencia = result.rows;
                loadDataToTemplate('exp_template', 'tbl_exp', result.rows);
            }
        }
    });
}

function loadReferencias() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_referencias",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id")
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.referencias = result.rows;
                loadDataToTemplate('referencias_template', 'tbl_referencias', result.rows);
            }
        }
    });
}

function loadPublicaciones() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_publicaciones",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id")
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.publicaciones = result.rows;
                loadDataToTemplate('publicaciones_template', 'tbl_pub', result.rows);
            }
        }
    });
}

function loadExposiciones() {
    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_exposiciones",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id")
        },
        success: function (result) {
            if (result.rows) {
                datosPostulacion.exposiciones = result.rows;
                loadDataToTemplate('exposiciones_template', 'tbl_expo', result.rows);
            }
        }
    });
}

function loadAnexos() {

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "get",
        data: {
            action: "get_anexos",
            id_postulante: $("#id_user").val(),
            id_convocatoria: getParameterByName("id")
        },
        success: function (result) {

            if (result.rows) {

                datosPostulacion.anexos = result.rows;

                result.rows.forEach(function (anexo) {

                    document.forms.form_anexos["rd_p" + anexo.id].value = anexo.respuesta ? 1 : 0;

                });
            }
        }
    });

}

function saveSustento() {

    var fileSustent = document.querySelector("#file_doc").files[0];

    if (fileSustent) {

        var data = new FormData();

        data.append('sustento_doc', fileSustent);
        data.append('action', 'set_datos_sustentatorios');
        data.append('tipo_doc', $("#cbx_tipo_doc_sus").val());
        data.append('id_postulante', $("#id_user").val());

        $.ajax({
            url: "controllers/PostulanteAjaxController.php",
            dataType: "json",
            type: "post",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                if (result.error === "") {

                    document.querySelector("#file_doc").value = "";
                    document.querySelector("#file_doc").parentElement.children[1].innerHTML = "";

                    showMessage("El archivo se guardo correctamente", "success");
                    loadSustento();
                } else {
                    showMessage(result.error, "error");
                }
            }
        });

    } else {
        showMessage("Seleccione un archivo", "error");
    }
}

function saveDataPersonal(e) {
    e.preventDefault();
    //obtencion de todos los datos

    var id = $("#id_user").val().trim();
    var nacionalidad = $("#cbx_nacionalidad").val().trim();
    var tipo_doc = $("#cbx_tipo_doc").val().trim();
    var estado_civil = $("#cbx_estado_civil").val().trim();
    var departamento = $("#cbx_depa").val().trim();
    var provincia = $("#cbx_prov").val().trim();
    var distrito = $("#cbx_dist").val().trim();
    var tipo_zona = $("#cbx_tipo_zona").val().trim();
    var tipo_via = $("#cbx_tipo_via").val().trim();
    var nro_doc = $("#txt_nro_doc").val().trim();
    var apellido_pat = $("#txt_apellido_pat").val().trim();
    var apellido_mat = $("#txt_apellido_mat").val().trim();
    var nombres = $("#txt_nombres").val().trim();
    var nombre_zona = $("#txt_nombre_zona").val().trim();
    var direccion = $("#txt_direccion").val().trim();
    var telefono = $("#txt_tel").val().trim();
    var celular = $("#txt_cel").val().trim();
    var correo = $("#txt_correo").val().trim();
    var discapacidad = document.form_datos_personales.rbtn_dis.value.trim();
    var licenciado_ffaa = document.form_datos_personales.rbtn_ff_aa.value.trim();

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (nacionalidad === "") errormessage = "Seleccione la nacionalidad";
    else if (tipo_doc === "") errormessage = "Seleccione el tipo de documemnto";
    else if (estado_civil === "") errormessage = "Seleccione su estado civil";
    else if (departamento === "") errormessage = "Seleccione departamento";
    else if (provincia === "") errormessage = "Seleccione provincia";
    else if (distrito === "") errormessage = "Seleccione distrito";
    else if (tipo_zona === "") errormessage = "Seleccione tipo de zona";
    else if (tipo_via === "") errormessage = "Seleccione tipo de vía";

    else if (nro_doc === "") errormessage = "Ingrese su número de documento";
    else if (!isValidNumberAndFixedLength(nro_doc,8)) errormessage = "Ingrese su número de documento correctamente";
    else if (apellido_pat === "") errormessage = "Ingrese su apellido paterno";
    else if (apellido_pat.length < 3) errormessage = "Ingrese apellido paterno";
    else if (apellido_mat === "") errormessage = "Ingrese su apellido materno";
    else if (apellido_mat.length < 3) errormessage = "Ingrese apellido materno";
    else if (nombres === "") errormessage = "Ingrese su nombre completo";
    else if (nombres.length < 3) errormessage = "Ingrese su nombre completo";
    else if (nombre_zona === "") errormessage = "Ingrese nombre de zona";
    else if (direccion === "") errormessage = "Ingrese dirección";
    else if (direccion.length < 8) errormessage = "Ingrese dirección (como mínimo 8 caracteres)";
    else if (telefono !== "" && !isValidNumberAndFixedLength(telefono,7)) errormessage = "Ingrese su número de teléfono correctamente";
    else if (celular === "") errormessage = "Ingrese su número celular";
    else if (!isValidNumberAndFixedLength(celular,9)) errormessage = "Ingrese su número celular correctamente";
    else if (correo === "") errormessage = "Ingrese su correo";
    else if (!isValidEmail( correo )) errormessage = "Ingrese su correo correctamente";
    else if (discapacidad === "") errormessage = "Debes consignar si cuentas con alguna discapacidad";
    else if (licenciado_ffaa === "") errormessage = "Debes consignar si perteneces a las FF.AA ";

    if (errormessage !== "") return showMessage(errormessage, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action: "set_datos_personales",
            id: id,
            nacionalidad: nacionalidad,
            tipo_doc: tipo_doc,
            estado_civil: estado_civil,
            departamento: departamento,
            provincia: provincia,
            distrito: distrito,
            tipo_zona: tipo_zona,
            tipo_via: tipo_via,
            nro_doc: nro_doc,
            apellido_pat: apellido_pat,
            apellido_mat: apellido_mat,
            nombres: nombres,
            nombre_zona: nombre_zona,
            direccion: direccion,
            telefono: telefono,
            celular: celular,
            correo: correo,
            discapacidad: discapacidad,
            licenciado_ffaa: licenciado_ffaa
        },
        success: function (result) {
            if (result.error === "") {
                loadDataToPostulante();
                return showMessage("Se guardaron los datos correctamente", "success")
            }

            showMessage(result.error, "error")

        }
    });

    //validacion de datos

    //console.log( e.target.form.querySelector(".custom-file-input").files );


}

function saveFormacionAcademica(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var nivel = $("#cbx_nivel_formacion").val().trim();
    var grado = $("#cbx_grado_formacion").val().trim();
    var especialidad = $("#txt_especialidad").val().trim();
    var centro_estudios = $("#txt_centro_estudios").val().trim();
    var inicio_year = $("#txt_inicio").val().trim();
    var fin_year = $("#txt_fin").val().trim();
    var fecha_extension_grado = $("#txt_fecha_extension").val().trim();

    var fileSustento = document.querySelector("#file_formacion").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (nivel === "") errormessage = "Seleccione el nivel";
    else if (grado === "") errormessage = "Seleccione el grado";
    else if (especialidad === "") errormessage = "Ingrese la especialidad";
    else if (centro_estudios === "") errormessage = "Ingrese el centro de estudios";
    else if (inicio_year === "") errormessage = "Ingrese el año de inicio";
    else if (!isValidNumberAndFixedLength(inicio_year,4) ) errormessage = "Ingrese el año de inicio correctamente";
    else if (fin_year === "") errormessage = "Ingrese el año final";
    else if (!isValidNumberAndFixedLength(fin_year,4) ) errormessage = "Ingrese el año final correctamente";
    else if (inicio_year > fin_year) errormessage = "El año inicio no debe ser mayor al año de finalización";
    else if (fecha_extension_grado !== "" && !isValidNumber( fecha_extension_grado.split("/").join("") ) ) errormessage = "Ingrese la fecha de extensión de grado correctamente";
    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('file_sustento', fileSustento);
    data.append('action', 'set_formacion_academica');
    data.append('id', id);
    data.append('nivel', nivel);
    data.append('grado', grado);
    data.append('especialidad', especialidad);
    data.append('centro_estudios', centro_estudios);
    data.append('inicio_year', inicio_year);
    data.append('fin_year', fin_year);
    data.append('fecha_extension_grado', fecha_extension_grado);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {

                loadFormacionAcademica();

                document.querySelector("#file_formacion").value = "";
                document.querySelector("#file_formacion").parentElement.children[1].innerHTML = "";

                document.forms.form_formacion_academica.reset();
                return showMessage("Se guardaron los datos correctamente", "success");

            }

            showMessage(result.error, "error");

        }
    });
}

function saveEspecializaciones(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var tema = $("#txt_tema_esp").val().trim();
    var centro_estudios = $("#txt_centro_estudios_esp").val().trim();
    var inicio = $("#txt_fecha_inicio_esp").val().trim();
    var fin = $("#txt_fecha_fin_esp").val().trim();
    var duracion = $("#txt_duracion_esp").val().trim();
    var tipo_sustento = $("#cbx_tipo_sustento_esp").val().trim();

    var fileSustento = document.querySelector("#file_esp").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (tema === "") errormessage = "Seleccione el tema";
    else if (centro_estudios === "") errormessage = "Ingrese el centro de estudios";
    else if (inicio === "") errormessage = "Ingrese la fecha de inicio";
    else if (!isValidNumber( inicio.split("/").join("")) ) errormessage = "Ingrese la fecha de inicio correctamente";
    else if (fin === "") errormessage = "Ingrese la fecha de finalización";
    else if (!isValidNumber( fin.split("/").join("")) ) errormessage = "Ingrese la fecha de finalización correctamente";
    else if (!validarRangoFecha( fin , fin ) ) errormessage = "La fecha fin debe ser mayor a la fecha de inicio";
    else if (duracion === "") errormessage = "Ingrese la duración";
    else if (!isValidNumber(duracion)) errormessage = "Ingrese la duración";
    else if (duracion <= 0 ) errormessage = "La duración debe ser mayor a cero";
    else if (tipo_sustento === "") errormessage = "Seleccione el tipo de sustento";
    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('action', 'set_conocimento');

    data.append('id', id);
    data.append('id_convocatoria', id_convocatoria);
    data.append('tipo_conocimiento', '1');

    data.append('tema', tema);
    data.append('centro_estudios', centro_estudios);
    data.append('inicio', inicio);
    data.append('fin', fin);
    data.append('duracion', duracion);
    data.append('tipo_sustento', tipo_sustento);

    data.append('file_sustento', fileSustento);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadEspecializaciones();

                document.querySelector("#file_esp").value = "";
                document.querySelector("#file_esp").parentElement.children[1].innerHTML = "";

                document.forms.form_cursos_especializaciones.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });
}

function saveCursosTecnicos(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var tema = $("#txt_tema_tec").val().trim();
    var centro_estudios = $("#txt_centro_estudios_tec").val().trim();
    var inicio = $("#txt_fecha_inicio_tec").val().trim();
    var fin = $("#txt_fecha_fin_tec").val().trim();
    var duracion = $("#txt_duracion_tec").val().trim();
    var tipo_sustento = $("#cbx_tipo_sustento_tec").val().trim();

    var fileSustento = document.querySelector("#file_tec").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (tema === "") errormessage = "Seleccione el tema";
    else if (centro_estudios === "") errormessage = "Ingrese el centro de estudios";
    else if (inicio === "") errormessage = "Seleccione la fecha de inicio";
    else if (!isValidNumber( inicio.split("/").join("")) ) errormessage = "Ingrese la fecha de inicio correctamente";
    else if (fin === "") errormessage = "Ingrese la fecha de finalización";
    else if (!isValidNumber( fin.split("/").join("")) ) errormessage = "Ingrese la fecha de finalización correctamente";
    else if ( !validarRangoFecha( inicio , fin ) ) errormessage = "La fecha fin debe ser mayor a la fecha de inicio";
    else if (duracion === "") errormessage = "Ingrese la duración";
    else if ( !isValidNumber(duracion)) errormessage = "la duración debe ser númerico";
    else if (duracion <= 0) errormessage = "La duracion debe ser mayor a cero";
    else if (tipo_sustento === "") errormessage = "Seleccione el tipo de sustento";
    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('action', 'set_conocimento');

    data.append('id', id);
    data.append('id_convocatoria', id_convocatoria);
    data.append('tipo_conocimiento', '2');

    data.append('tema', tema);
    data.append('centro_estudios', centro_estudios);
    data.append('inicio', inicio);
    data.append('fin', fin);
    data.append('duracion', duracion);
    data.append('tipo_sustento', tipo_sustento);

    data.append('file_sustento', fileSustento);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadCursosTecnicos();

                document.querySelector("#file_tec").value = "";
                document.querySelector("#file_tec").parentElement.children[1].innerHTML = "";

                document.forms.form_cursos_tecnicos.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });

}

function saveIdiomas(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var idioma = $("#cbx_idioma").val().trim();
    var nivel = $("#cbx_nivel_idioma").val().trim();

    var fileSustento = document.querySelector("#file_idioma").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (idioma === "") errormessage = "Seleccione el idioma";
    else if (nivel === "") errormessage = "Seleccione elnivel";
    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('action', 'set_conocimento');

    data.append('id', id);
    data.append('id_convocatoria', id_convocatoria);
    data.append('tipo_conocimiento', '3');

    data.append('idioma', idioma);
    data.append('nivel', nivel);

    data.append('file_sustento', fileSustento);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {

            if (result.error === "") {
                loadIdiomas();

                document.querySelector("#file_idioma").value = "";
                document.querySelector("#file_idioma").parentElement.children[1].innerHTML = "";

                document.forms.form_idiomas.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });
}

function saveExperienciaLaboral(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var tipo_experiencia = $("#cbx_tipo_exp").val().trim();
    var cargo = $("#txt_cargo").val().trim();
    var tipo_entidad = $("#cbx_tipo_ent").val().trim();
    var nombre_entidad = $("#txt_nombre_ent").val().trim();
    var fecha_inicio = $("#txt_fecha_ini").val().trim();
    var fecha_fin = $("#txt_fecha_fin").val().trim();


    var fileSustento = document.querySelector("#file_exp").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (tipo_experiencia === "") errormessage = "Seleccione el tipo de experiencia";
    else if (cargo === "") errormessage = "Ingrese el cargo";
    else if (tipo_entidad === "") errormessage = "Seleccione el tipo de entidad";
    else if (nombre_entidad === "") errormessage = "Ingrese el nombre de la entidad";
    else if (fecha_inicio === "") errormessage = "Ingrese la fecha de inicio";
    else if (!isValidNumber( fecha_inicio.split("/").join("") )) errormessage = "Ingrese la fecha de inicio correctamente";
    else if (fecha_fin === "") errormessage = "Ingrese la fecha fin";
    else if (!isValidNumber( fecha_fin.split("/").join("") )) errormessage = "Ingrese la fecha fin correctamente";
    else if ( !validarRangoFecha( fecha_inicio , fecha_fin ) ) errormessage = "La fecha fin debe ser mayor a la fecha de inicio";
    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('file_sustento', fileSustento);
    data.append('action', 'set_exp');
    data.append('id', id);
    data.append('tipo_experiencia', tipo_experiencia);
    data.append('cargo', cargo);
    data.append('tipo_entidad', tipo_entidad);
    data.append('nombre_entidad', nombre_entidad);
    data.append('fecha_inicio', fecha_inicio);
    data.append('fecha_fin', fecha_fin);
    data.append('id_convocatoria', id_convocatoria);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadExperienciaLaboral();

                document.querySelector("#file_exp").value = "";
                document.querySelector("#file_exp").parentElement.children[1].innerHTML = "";

                document.forms.form_exp.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });
}

function saveReferencias(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var nombre_referencia = $("#txt_nombre_referencia").val().trim();
    var cargo = $("#txt_cargo_referencia").val().trim();
    var nombre_entidad = $("#txt_nombre_entidad_referencia").val().trim();
    var tel_entidad = $("#txt_telefono_entidad").val().trim();

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (nombre_referencia === "") errormessage = "Ingrese el nombre de la referencia";
    else if (cargo === "") errormessage = "Ingrese el cargo";
    else if (nombre_entidad === "") errormessage = "Ingrese el nombre de la entidad";
    else if (tel_entidad === "") errormessage = "Ingrese el telefono de la entidad";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('action', 'set_referencias');

    data.append('id', id);
    data.append('id_convocatoria', id_convocatoria);

    data.append('nombre_referencia', nombre_referencia);
    data.append('cargo', cargo);
    data.append('nombre_entidad', nombre_entidad);
    data.append('tel_entidad', tel_entidad);


    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadReferencias();
                document.forms.form_exp.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}

function savePublicaciones(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var editorial = $("#txt_editorial_pub").val().trim();
    var titulo = $("#txt_titulo_pub").val().trim();
    var grado_participacion = $("#txt_grado_participacion_pub").val().trim();
    var fecha_publicacion = $("#txt_fecha_pub").val().trim();

    var fileSustento = document.querySelector("#file_pub").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (editorial === "") errormessage = "Ingrese la editorial";
    else if (titulo === "") errormessage = "Ingrese el titulo";
    else if (grado_participacion === "") errormessage = "Ingrese el grado de participación";
    else if (fecha_publicacion === "") errormessage = "Ingrese la fecha de publicación";
    else if ( !isValidNumber( fecha_publicacion.split("/").join("") ) ) errormessage = "Ingrese la fecha de publicación correctamente";

    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('file_sustento', fileSustento);
    data.append('action', 'set_publicaciones');
    data.append('id', id);
    data.append('editorial', editorial);
    data.append('titulo', titulo);
    data.append('grado_participacion', grado_participacion);
    data.append('fecha_publicacion', fecha_publicacion);
    data.append('id_convocatoria', id_convocatoria);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadPublicaciones();

                document.querySelector("#file_pub").value = "";
                document.querySelector("#file_pub").parentElement.children[1].innerHTML = "";

                document.forms.form_publicaciones.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });
}

function saveExposiciones(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var institucion = $("#txt_institucion_exp").val().trim();
    var tema = $("#txt_tema_exp").val().trim();
    var ciudad = $("#txt_ciudad_exp").val().trim();
    var fecha_evento = $("#txt_fecha_evento_exp").val().trim();

    var fileSustento = document.querySelector("#file_expo").files[0];

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (institucion === "") errormessage = "Ingrese la intitución";
    else if (tema === "") errormessage = "Ingrese el tema";
    else if (ciudad === "") errormessage = "Ingrese la ciudad";
    else if (fecha_evento === "") errormessage = "Ingrese fecha del evento";
    else if (!isValidNumber( fecha_evento.split("/").join("") )) errormessage = "Ingrese fecha del evento correctamente";

    else if (!fileSustento) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();

    data.append('file_sustento', fileSustento);
    data.append('action', 'set_publicaciones');
    data.append('id', id);
    data.append('institucion', institucion);
    data.append('tema', tema);
    data.append('ciudad', ciudad);
    data.append('fecha_evento', fecha_evento);
    data.append('id_convocatoria', id_convocatoria);

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.error === "") {
                loadPublicaciones();

                document.querySelector("#file_expo").value = "";
                document.querySelector("#file_expo").parentElement.children[1].innerHTML = "";

                document.forms.form_exposiciones.reset();
                return showMessage("Se guardaron los datos correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });

}

function saveAnexos(e) {
    e.preventDefault();

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var arrayElements = document.querySelectorAll(".anexo");
    var error = "";
    var respuestas = [];

    arrayElements.forEach(function (anexo_element) {
        var value = document.forms.form_anexos["rd_p" + anexo_element.dataset.anexo_id].value;
        if (value === "") error = "responda todas las preguntas";
        else respuestas.push({id_anexo: anexo_element.dataset.anexo_id, value: value == 1})
    });

    if (error !== "") return showMessage(error, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action: "set_anexos",
            id: id,
            id_convocatoria: id_convocatoria,
            anexos: respuestas
        },
        success: function (result) {
            if (result.error === "") {
                loadAnexos();
                return showMessage("datos guardados correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });
}

function enviarPostulacion() {

    var id = $("#id_user").val().trim();
    var id_convocatoria = getParameterByName("id");

    var errormessage = "";

    if (id === "") errormessage = "El id del postulante es requerido";
    else if (id_convocatoria === "") errormessage = "El id de la convocatoria es requerido";
    else if (listadoMensajes.length !== 0) errormessage = "Debe consignar todos los datos obligatorios";

    if (errormessage !== "") return showMessage(errormessage, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        type: "post",
        dataType: "json",
        data: {
            action: "pos_complete",
            id: id,
            id_convocatoria: id_convocatoria
        },
        success: function (result) {
            if (result.error === "") {
                showMessage("Datos guardados correctamente", "success");
                setTimeout(function () {
                    location.href = "?url=listado_convocatorias";
                }, 1000);
            } else {
                showMessage(result.error, "error");
            }

        }
    });

}

function deleteFormacion(id_postulante, nro_detalle) {

    var errorMessage = "";

    if (!id_postulante) errorMessage = "El id del postulante es requerido";
    else if (!nro_detalle) errorMessage = "El detalle es requerido";

    if (errorMessage) return showMessage(errorMessage, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        type: "post",
        dataType: "json",
        data: {
            action: "del_formacion_academica",
            id_postulante: id_postulante,
            nro_detalle: nro_detalle
        },
        success: function (result) {

            if (result.error === "") {
                loadFormacionAcademica();
                return showMessage("datos eliminados correctamente", "success");
            }
            showMessage(result.error, "error");
        }
    });

}

function deleteDetalle(desc,id_postulacion, nro_detalle) {

    var errorMessage = "";

    if (!id_postulacion) errorMessage = "El id de la postulacion es requerido";
    else if (!nro_detalle) errorMessage = "El detalle es requerido";

    if (errorMessage) return showMessage(errorMessage, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        type: "post",
        dataType: "json",
        data: {
            action: "del_" + desc,
            id_postulacion: id_postulacion,
            nro_detalle: nro_detalle
        },
        success: function (result) {

            if (result.error === "") {
                switch (desc){
                    case "exp": loadExperienciaLaboral() ;break;
                    case "ref": loadReferencias() ;break;
                    case "pub": loadPublicaciones() ;break;
                    case "expo": loadExposiciones() ;break;

                }
                return showMessage("datos eliminados correctamente", "success");
            }

            showMessage(result.error, "error");
        }
    });
}

function deleteConocimiento(tipo,id_postulacion, nro_detalle) {

    var errorMessage = "";

    if (!id_postulacion) errorMessage = "El id de la postulacion es requerido";
    else if (!nro_detalle) errorMessage = "El detalle es requerido";

    if (errorMessage) return showMessage(errorMessage, "error");

    $.ajax({
        url: "controllers/PostulanteAjaxController.php",
        type: "post",
        dataType: "json",
        data: {
            action: "del_conocimiento",
            tipo : tipo,
            id_postulacion: id_postulacion,
            nro_detalle: nro_detalle
        },
        success: function (result) {

            if (result.error === "") {
                switch (tipo){
                    case 1: loadEspecializaciones() ;break;
                    case 2: loadCursosTecnicos() ;break;
                    case 3: loadIdiomas() ;break;

                }
                return showMessage("datos eliminados correctamente", "success");
            }

            showMessage(result.error, "error");
        }
    });
}




















