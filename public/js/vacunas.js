var dataList = [];

function loadData(e) {
    e.preventDefault();

    var anio = $("#cbx_anio").val().trim();
    var mes = $("#cbx_mes").val().trim();
    var fileExcel = document.querySelector("#fl_excel").files[0];

    var errormessage = "";

    if (anio === "") errormessage = "Seleccione del año";
    else if (mes === "") errormessage = "Seleccione el mes";
    else if (!fileExcel) errormessage = "Seleccione un archivo";
    if (errormessage !== "") return showMessage(errormessage, "error");

    var data = new FormData();
    data.append('action', 'load_data_excel');
    data.append('mes', mes);
    data.append('anio', anio);
    data.append('file', fileExcel);

    $.ajax({
        url: "controllers/VacunasAjaxController.php",
        dataType: "json",
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {

            dataList = result.rows;

            if (result.error === "") {

                $("#cbx_anio").prop("disabled",true);
                $("#cbx_mes").prop("disabled",true);
                document.querySelector("#fl_excel").value = "";
                document.querySelector("#fl_excel").parentElement.children[1].innerHTML = "";

                loadDataToTemplate("datos_excel_template","datosExcel",result.rows);

                return showMessage("Datos cargados correctamente", "success");
            }

            clearData();

            $("#datosExcel").html("");

            showMessage(result.error, "error");
        }
    });
}

function saveData(){

    dataList.pop();

    if(dataList.length === 0){
        return showMessage("No hay datos cargados","error");
    }


    $.ajax({
        url: "controllers/VacunasAjaxController.php",
        dataType: "json",
        type: "post",
        data: {
            action:"ins_general",
            data:dataList
        },
        cache: false,
        success: function (result) {

            if (result.error === "") {
                clearData();
                return showMessage("Datos guardados correctamente", "success");
            }

            showMessage(result.error, "error");
        }
    });
}

function clearData(){
    dataList = [];
    $("#datosExcel").html("");
    $("#cbx_anio").prop("disabled",false);
    $("#cbx_mes").prop("disabled",false);
    document.querySelector("#fl_excel").value = "";
    document.querySelector("#fl_excel").parentElement.children[1].innerHTML = "";
}














