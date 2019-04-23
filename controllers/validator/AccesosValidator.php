<?php

require_once "../config/Conexion.php";
require_once "../models/AccesosRepository.php";

class AccesosValidator
{

    private $tablaRepository;

    public function __construct()
    {
        $this->tablaRepository = new AccesosRepository();
    }

    function getAccesosByRol()
    {
        $array_result = ["error" => "", "rows" => []];

        $data["id_rol"] = isset($_GET["id_rol"]) ? $_GET["id_rol"] : 0;

        if($data["id_rol"] == 0) $array_result["error"] = "El id del rol de requerido";

        if($array_result["error"] === ""){
            $array_result = $this->tablaRepository->getAccesosByRol($data);
        }

        return $array_result;
    }

    function saveAccesos()
    {
        $array_result = ["error" => "", "rows" => []];

        $data["id_rol"] = isset($_POST["id_rol"]) ? $_POST["id_rol"] : 0;
        $data["accesos"] = isset($_POST["accesos"]) ? $_POST["accesos"] : [];

        if($data["id_rol"] == 0) $array_result["error"] = "El id del rol de requerido";

        if($array_result["error"] === ""){
            $array_result = $this->tablaRepository->saveAccesos($data);
        }

        return $array_result;
    }


}

















