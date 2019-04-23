<?php

require_once "../config/Conexion.php";
require_once "../models//TablaRepository.php";

class TablaTipoValidator
{
    private $tablaRepository;

    public function __construct()
    {
        $this->tablaRepository = new TablaRepository();
    }

    function getAll()
    {

        $table_id = isset($_GET["table_id"]) ? $_GET["table_id"] : 0;
        $desc = isset($_GET["desc"]) ? $_GET["desc"] : "";

        if (isset($_GET["pageSize"]) && isset($_GET["pageIndex"])) {
            $array_result = $this->tablaRepository->getAllByTablePaginate($table_id, $desc ,$_GET["pageSize"], $_GET["pageIndex"]);
        } else {
            $array_result = $this->tablaRepository->getAllByTable($table_id);
        }
        return $array_result;
    }

    function insertTabla()
    {
        $result = ["error" => ""];

        $data['id_tabla'] = isset($_POST['id_tabla']) ? $_POST['id_tabla'] : 0;
        $data['desc'] = isset($_POST['desc']) ? $_POST['desc'] : "";

        //validacion de datos en el backend
        if ($data['id_tabla'] === 0) $result["error"] = "El id de la tabla es requerido";
        else if ($data['desc'] === "") $result["error"] = "La descripción es requerida";

        if ($result["error"] === "") {
            $result = $this->tablaRepository->insertTabla($data);
        }

        return $result;
    }

    function updateTabla()
    {
        $result = ["error" => ""];

        $data['id_tabla'] = isset($_POST['id_tabla']) ? $_POST['id_tabla'] : 0;
        $data['id_tipo'] = isset($_POST['id_tipo']) ? $_POST['id_tipo'] : 0;
        $data['desc'] = isset($_POST['desc']) ? $_POST['desc'] : "";

        //validacion de datos en el backend
        if ($data['id_tabla'] === 0) $result["error"] = "El id de la tabla es requerido";
        if ($data['id_tipo'] === 0) $result["error"] = "El id area es requerido";
        else if ($data['desc'] === "") $result["error"] = "La descripción es requerida";

        if ($result["error"] == "") {
                $result = $this->tablaRepository->updateTabla($data);
        }
        return $result;
    }


}