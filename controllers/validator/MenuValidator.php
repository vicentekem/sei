<?php

require_once "../config/Conexion.php";
require_once "../models/MenuRepository.php";

class MenuValidator
{

    private $tablaRepository;

    public function __construct()
    {
        $this->tablaRepository = new MenuRepository();
    }

    function getMenu()
    {
        $array_result = ["error" => "", "rows" => []];

        if($array_result["error"] === ""){
            $array_result = $this->tablaRepository->getMenu();
        }

        return $array_result;
    }

    function getSubMenu()
    {
        $array_result = ["error" => "", "rows" => []];

        $data["id_menu"] = isset($_GET["id_menu"]) ? $_GET["id_menu"] : 0;

        if($data["id_menu"] == 0) $array_result["error"] = "El menu es requerido";

        if($array_result["error"] === ""){
            $array_result = $this->tablaRepository->getSubMenuUrlsByMenu($data["id_menu"]);
        }
        return $array_result;
    }


}