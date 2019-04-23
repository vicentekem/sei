<?php

require_once "validator/AccesosValidator.php";

class AccesosAjaxController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new AccesosValidator();
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
            case "qry_accesos": $array_result = $this->validator->getAccesosByRol();break;
            default: $array_result["error"] = "Error al procesar la petición";
        }
        echo json_encode($array_result);
    }

    public function controllerPost(){
        $action = isset($_POST['action']) ? $_POST['action'] : "";

        switch ($action){
            case "save_accesos": $array_result = $this->validator->saveAccesos(); break;

            default : $array_result["error"] = "";
        }

        echo json_encode($array_result);
    }

}

$controller = new AccesosAjaxController();
$controller->procesarPeticion();
























