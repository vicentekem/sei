<?php

require_once "validator/UsuarioValidator.php";

class UsuarioController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new UsuarioValidator();
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
            case "qry_usuario": $array_result = $this->validator->getUsuarios();break;
            default:
                $array_result["error"] = "Error al procesar la petición";
                break;
        }

        echo json_encode( $array_result );

    }

    public function controllerPost(){

        $action = isset($_POST['action']) ? $_POST['action'] : "";
        switch ($action){
            case "login":
                $array_result = $this->validator->login();

                if( count($array_result["errors"]) == 0 ){
                    session_start();

                    $_SESSION["usuario"] = $array_result["row"];

                    header('location:../?url=inicio');
                }else{
                    header('location:../?url=login');
                }
                break;
            case "upd": $array_result = $this->validator->updUsuario();break;
            case "ins": $array_result = $this->validator->insUsuario();break;
            default:
                $array_result["error"] = "Error al procesar la petición";
        }
        echo json_encode( $array_result );
    }

}

$controller = new UsuarioController();
$controller->procesarPeticion();

