var convocatorias = [];
var files = [];
var id = 0;

function clearForm() {
    $("#cbx_area").val("");
    $("#txt_desc").val("");
    $("#txt_cant").val(1);

    $("#txt_fecha_inicio").val("");
    $("#txt_fecha_fin").val("");

    document.querySelector("#file_tdr").value = "";
    document.querySelector("#file_tdr").parentElement.querySelector(".custom-file-label").innerText = "";
}

function addConvocatoria(e) {
    e.preventDefault();

    //obtener datos:
    var area_id = $("#cbx_area").val();
    var area_value = $("#cbx_area option:selected").text();
    var descripcion = $("#txt_desc").val();
    var cantidad = $("#txt_cant").val();
    var fecha_inicio = $("#txt_fecha_inicio").val();
    var fecha_fin = $("#txt_fecha_fin").val();
    var esp = $("#chbx_esp").prop("checked");
    var tec = $("#chbx_tec").prop("checked");
    var idioma = $("#chbx_idioma").prop("checked");
    var expo = $("#chbx_expo").prop("checked");
    var pub = $("#chbx_pub").prop("checked");
    var ref = $("#chbx_ref").prop("checked");

    var file = document.querySelector("#file_tdr").files[0];

    var errormessage = "";

    if (area_id === "") errormessage = "Seleccione el area";
    else if (descripcion === "") errormessage = "Ingrese la descripcion";
    else if (cantidad === "") errormessage = "Ingrese la cantidad";
    else if (isNaN(cantidad)) errormessage = "La cantidad debe de ser númerico";
    else if (cantidad <= 0) errormessage = "La cantidad debe de ser mayor a cero";
    else if (fecha_inicio === "") errormessage = "Ingrese la fecha de inicio";
    else if (isNaN(fecha_inicio.split("/").join(""))) errormessage = "Ingrese la fecha de inicio correctamente";
    else if (fecha_fin === "") errormessage = "Ingrese fecha de finalización";
    else if (isNaN(fecha_fin.split("/").join(""))) errormessage = "Ingrese fecha de finalización correctamente";
    else if (!validarRangoFecha(fecha_inicio, fecha_fin)) errormessage = "La fecha fin debe ser mayor a la fecha de inicio";
    else if (getTimeFromString(fecha_inicio) < getCurrentTime()) errormessage = "La fecha inicio debe ser mayor o igual a la fecha de actual";

    else if (!file) errormessage = "Seleccione un archivo";

    if (errormessage !== "") return showMessage(errormessage, "error");

    convocatorias.push({
        id: id++,
        area: area_value,
        area_id: area_id,
        descripcion: descripcion,
        cantidad: cantidad,
        inicio: fecha_inicio,
        fin: fecha_fin,
        esp:esp,
        tec:tec,
        idioma:idioma,
        expo:expo,
        pub:pub,
        ref:ref,
        tdr: URL.createObjectURL(file)
    })
    ;

    files.push(file);

    clearForm();

    loadDataToTemplate('template_convocatorias', 'tbl_convocatorias', convocatorias);
}

function deleteConvocatoria(e) {
    if (e) e.preventDefault();

    var idConv = e.target.dataset.id;

    var index = convocatorias.findIndex(function (convocatoria) {
        return convocatoria.id == idConv;
    });

    convocatorias.splice(index, 1);

    loadDataToTemplate('template_convocatorias', 'tbl_convocatorias', convocatorias);
}

function saveConvocatorias() {

    if (convocatorias.length === 0) {
        return showMessage("Debe agregar como mínimo una convocatoria", "error");
    }

    var data = new FormData();

    data.append('action', 'ins_array_conv');
    data.append('id_usuario', $("#id_usuario").val());

    convocatorias.forEach(function (convocatoria) {
        data.append('convocatorias[]', JSON.stringify(convocatoria));
    });

    files.forEach(function (file) {
        data.append('files[]', file);
    });

    $.ajax({
        url: "../controllers/ConvocatoriaAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {

            if (result.error === "") {
                clearForm();
                convocatorias = [];
                loadDataToTemplate('template_convocatorias', 'tbl_convocatorias', convocatorias);
                return showMessage("Se guardaron los datos correctamente", "success");
            }

            showMessage(result.error, "error");

        }
    });

}
