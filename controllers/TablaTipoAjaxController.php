<?php

require_once "validator/TablaTipoValidator.php";

class TablaTipoAjaxController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new TablaTipoValidator();
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

    public function controllerGet()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        switch ($action) {
            case "qry": $array_result = $this->validator->getAll();break;
            default: $array_result["error"] = "Error al procesar la petición";
        }
        echo json_encode($array_result);
    }

    public function controllerPost(){
        $action = isset($_POST['action']) ? $_POST['action'] : "";

        switch ($action){
            case "ins": $array_result = $this->validator->insertTabla(); break;
            case "upd": $array_result = $this->validator->updateTabla(); break;
            default : $array_result["error"] = "";
        }

        echo json_encode($array_result);
    }

}

$controller = new TablaTipoAjaxController();
$controller->procesarPeticion();



