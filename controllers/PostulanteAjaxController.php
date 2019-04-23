<?php
/**
 * Created by PhpStorm.
 * User: ACER
 * Date: 19/03/2019
 * Time: 05:57 PM
 */

require_once "validator/PostulanteValidator.php";

class PostulanteAjaxController
{

    private $validator;

    public function __construct()
    {
        $this->validator = new PostulanteValidator();
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
            case "get_datos_personales":$array_result = $this->validator->getDatosPersonales();break;
            case "get_datos_sustentatorios":$array_result = $this->validator->getDatosSustentatorios();break;
            case "get_formacion_academica":$array_result = $this->validator->getFormacionAcademica();break;
            case "get_exp":$array_result = $this->validator->getExperienciaLaboral();break;
            case "get_referencias":$array_result = $this->validator->getReferencias();break;
            case "get_conocimiento":$array_result = $this->validator->getConocimientos();break;
            case "get_publicaciones":$array_result = $this->validator->getPublicaciones();break;
            case "get_exposiciones":$array_result = $this->validator->getExposiciones();break;
            case "get_anexos":$array_result = $this->validator->getAnexos();break;
            default: $array_result["error"] = "No se puedo procesar la petición $action";
        }

        echo json_encode($array_result);

    }

    public function controllerPost(){
        $action = isset($_POST['action']) ? $_POST['action'] : "";

        switch ($action){
            case "register": $array_result = $this->validator->register();break;
            case "set_datos_sustentatorios": $array_result = $this->validator->setDatosSustentatorios();break;
            case "set_datos_personales": $array_result = $this->validator->setDatosPersonales();break;

            case "set_formacion_academica": $array_result = $this->validator->setFormacionAcademica();break;
            case "del_formacion_academica": $array_result = $this->validator->deleteFormacionAcademica();break;

            case "set_exp": $array_result = $this->validator->setExperienciaLaboral();break;
            case "del_exp": $array_result = $this->validator->deleteExperienciaLaboral();break;

            case "set_referencias": $array_result = $this->validator->setReferencias();break;
            case "del_ref": $array_result = $this->validator->deleteReferencias();break;

            case "set_conocimento": $array_result = $this->validator->setConocimientos();break;
            case "del_conocimiento": $array_result = $this->validator->deleteConocimientos();break;

            case "set_publicaciones":$array_result = $this->validator->setPublicaciones();break;
            case "del_pub":$array_result = $this->validator->deletePublicaciones();break;

            case "set_exposiciones":$array_result = $this->validator->setExposiciones();break;
            case "del_expo":$array_result = $this->validator->deleteExposiciones();break;

            case "set_anexos":$array_result = $this->validator->setAnexos();break;
            case "pos_complete":$array_result = $this->validator->completePos();break;
            default: $array_result["error"] = "No se puedo procesar la petición";
        }

        echo json_encode($array_result);
    }

}


$controller = new PostulanteAjaxController();
$controller->procesarPeticion();







