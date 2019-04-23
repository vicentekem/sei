function showMessage(message, type) {

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr[type](message);

}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function validarRangoFecha(f1, f2) {

    var arrFecha1 = f1.split("/");
    var fecha1 = new Date();
    var d1 = arrFecha1[0];
    var m1 = parseInt(arrFecha1[1]) + 1;
    var y1 = arrFecha1[2];

    fecha1.setDate(d1);
    fecha1.setMonth(m1);
    fecha1.setFullYear(y1);

    var arrFecha2 = f2.split("/");
    var fecha2 = new Date();
    var d2 = arrFecha2[0];
    var m2 = parseInt(arrFecha2[1]) + 1;
    var y2 = arrFecha2[2];

    fecha2.setDate(d2);
    fecha2.setMonth(m2);
    fecha2.setFullYear(y2);

    return fecha1.getTime() < fecha2.getTime();

}

function loadLocalFile() {

    $(".custom-file-input").change(function (e) {

        var fileSelected = e.target.files[0];
        if (fileSelected) {
            //validacion del tipo de archivo(solo PDF) y el tamaño del archivo (4MB maximo)
            var sizeFile = (fileSelected.size / (1024 * 1024));
            var typeFile = fileSelected.type;

            if (sizeFile > 4 || (typeFile !== "application/vnd.ms-excel" &&  typeFile !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ) {
                e.target.value = "";
                showMessage("El archivo seleccionado no es válido", "error");
            } else {
                e.target.parentElement.querySelector(".custom-file-label").innerText = fileSelected.name;
            }
        } else {
            e.target.parentElement.querySelector(".custom-file-label").innerText = "";
        }
    });
}

//funciones generales:
function loadDataToTemplate(idTemplate, idContentData, data) {
    var template = $("#" + idTemplate).html();

    Mustache.parse(template);

    var renderedHtml = Mustache.render(template, {data: data});

    $("#" + idContentData).html(renderedHtml);
}

function getTimeFromString(dateString) {

    var arrFecha = dateString.split("/");
    var fecha = new Date();
    var d = arrFecha[0];
    var m = parseInt(arrFecha[1]) + 1;
    var y = arrFecha[2];

    fecha.setDate(d);
    fecha.setMonth(m);
    fecha.setFullYear(y);

    return fecha.getTime();
}

function getCurrentTime() {

    return (new Date()).getTime();
}

function isValidEmail(mail) {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
}

function isValidDNI(dni) {
    return /^([0-9]{8})$/.test(dni);
}

function isValidNumber(n) {
    return /^([0-9]+)$/.test(n);
}

function isValidNumberAndFixedLength(n, l) {

    return (n.toString().length === l) ? /^([0-9]+)$/.test(n.toString()) : false;

}











