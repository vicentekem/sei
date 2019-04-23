<?php

require_once "validator/VacunaValidator.php";

class VacunasAjaxController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new VacunaValidator();
    }

    public function procesarPeticion(){

        if($_GET){
            $this->controllerGet();
        }else if($_POST){
            $this->controllerPost();
        }else{
            $res["error"] = "Error al procesar la petición";
            echo json_encode($res);
        }
    }

    public function controllerGet(){

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        switch ($action){
            case "get_vg_detalle":$array_result = $this->validator->getVacunaGeneralMensual();break;
            case "get_vg_general":$array_result = $this->validator->getVgGeneral();break;
            //case "get_datos_sustentatorios":$array_result = $this->validator->getDatosSustentatorios();break;
            default: $array_result["error"] = "No se puedo procesar la petición $action";
        }

        echo json_encode($array_result);

    }

    public function controllerPost(){
        $action = isset($_POST['action']) ? $_POST['action'] : "";

        switch ($action){
            case "load_data_excel": $array_result = $this->validator->loadDataExcel();break;
            case "ins_general": $array_result = $this->validator->insVacunaGeneral();break;
            default: $array_result["error"] = "No se puedo procesar la petición";
        }

        echo json_encode($array_result);
    }

}

$controller = new VacunasAjaxController();
$controller->procesarPeticion();







