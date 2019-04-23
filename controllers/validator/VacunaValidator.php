<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 3/04/2019
 * Time: 10:02
 */

require_once "../config/Conexion.php";
require_once "../config/handler.php";
require_once "../models/VacunaRepository.php";

session_start();

class VacunaValidator
{
    private $repository;

    public function __construct()
    {
        $this->repository = new VacunaRepository();
    }

    function getVacunaGeneralMensual(){

        $result = ["error" => "", "rows" => []];

        $data["id_usuario"] = isset($_SESSION["usuario"]["id_usuario"]) ? $_SESSION["usuario"]["id_usuario"] : 0;
        $data["anio"] = isset($_GET["anio"]) ? $_GET["anio"] : 0;
        $data["mes"] = isset($_GET["mes"]) ? $_GET["mes"] : 0;
        $data["grupo"] = isset($_GET["grupo"]) ? $_GET["grupo"] : 0;
        $data["pageSize"] = isset($_GET["pageSize"]) ? $_GET["pageSize"] : 0;
        $data["pageIndex"] = isset($_GET["pageIndex"]) ? $_GET["pageIndex"] : 0;

        if ( $data["id_usuario"] == 0 || $data["id_usuario"] == "" ) $result["error"] = "El id del postulante es requerido";
        else if ($data["anio"] == 0) $result["error"] = "Seleccione el año";
        else if ($data["mes"] == 0) $result["error"] = "Seleccione el mes";

        if($result["error"] === ""){
            $result = $this->repository->getVacunaGeneralMensual($data);
        }
        return $result;
    }

    function getVgGeneral(){

        $result = ["error" => "", "rows" => []];

        $data["id_usuario"] = isset($_SESSION["usuario"]["id_usuario"]) ? $_SESSION["usuario"]["id_usuario"] : 0;
        $data["anio"] = isset($_GET["anio"]) ? $_GET["anio"] : 0;
        $data["grupo"] = isset($_GET["grupo"]) ? $_GET["grupo"] : 0;
        $data["pageSize"] = isset($_GET["pageSize"]) ? $_GET["pageSize"] : 0;
        $data["pageIndex"] = isset($_GET["pageIndex"]) ? $_GET["pageIndex"] : 0;


        if ( $data["id_usuario"] == 0 || $data["id_usuario"] == "" ) $result["error"] = "El id del postulante es requerido";
        else if ($data["anio"] == 0) $result["error"] = "Seleccione el año";
        else if ($data["grupo"] == 0) $result["error"] = "Seleccione grupo";

        if($result["error"] === ""){
            $result = $this->repository->getVgGeneral($data);

        }
        return $result;
    }

    function loadDataExcel()
    {

        require_once '../vendor/phpoffice/PHPExcel/Classes/PHPExcel.php';

        $result = ["error" => "", "rows" => []];

        $archivo = $_FILES['file']['tmp_name'];

        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($archivo);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        //$highestColumn = $sheet->getHighestColumn();

        $data = [];

        $metas = $this->repository->getMetasPorEstablecimientos(["anio" => $_POST["anio"]]);

        if ($metas["error"] === "") {

            try {
                $i = 0;

                $total_meta_anual = 0;
                $total_meta_mensual = 0;

                $toal_penta = 0;
                $toal_rota = 0;
                $toal_neumo = 0;
                $toal_apo = 0;
                $toal_spr = 0;

                $totales = [];

                for ($row = 19; $row <= $highestRow; $row++) {

                    $establecimiento = $sheet->getCell("D" . $row)->getValue();
                    $establecimiento = implode(explode("-", trim($establecimiento)));

                    if ($establecimiento !== "") {

                        $data[$i]["anio"] = $_POST['anio'];
                        $data[$i]["mes"] = $_POST['mes'];
                        $data[$i]["redes"] = $sheet->getCell("B" . $row)->getValue();
                        $data[$i]["micro_redes"] = $sheet->getCell("C" . $row)->getValue();
                        $data[$i]["establecimientos"] = $sheet->getCell("D" . $row)->getValue();
                        $data[$i]["renaes"] = trim(explode("-", $data[$i]["establecimientos"])[2]);
                        $data[$i]["apo"] = $sheet->getCell("L" . $row)->getValue();
                        $data[$i]["penta"] = $sheet->getCell("O" . $row)->getValue();
                        $data[$i]["rota"] = $sheet->getCell("W" . $row)->getValue();
                        $data[$i]["neumo"] = $sheet->getCell("Y" . $row)->getValue();
                        $data[$i]["spr"] = $sheet->getCell("AF" . $row)->getValue();

                        $index = array_search($data[$i]["renaes"], array_column($metas['rows'], 'codigo_renaes'));

                        $data[$i]["meta_anual"] = (int)$metas["rows"][$index]["meta_anual"];
                        $data[$i]["establecimiento"] = $metas["rows"][$index]["establecimiento"];
                        $data[$i]["meta_id"] = $metas["rows"][$index]["meta_id"];
                        $data[$i]["meta_mensual"] = $data[$i]["meta_anual"] / 12;
                        $data[$i]["meta_mensual_int"] = round($data[$i]["meta_mensual"], 0);

                        $values = [];

                        $values[] = $data[$i]["apo"];
                        $values[] = $data[$i]["penta"];
                        $values[] = $data[$i]["rota"];
                        $values[] = $data[$i]["neumo"];
                        $values[] = $data[$i]["spr"];

                        $min = min($values);

                        $avance = ($min / $data[$i]["meta_anual"]) * 100;

                        $data[$i]["avance"] = round($avance, 2);

                        if ($data[$i]["avance"] < (77 / 12)) {
                            $data[$i]["bg_class"] = "bg-danger";
                        } else if ($data[$i]["avance"] >= (95 / 12)) {
                            $data[$i]["bg_class"] = "bg-success";
                        } else {
                            $data[$i]["bg_class"] = "bg-warning";
                        }

                        $total_meta_anual += $data[$i]["meta_anual"];
                        $total_meta_mensual += $data[$i]["meta_mensual"];

                        $toal_penta += $data[$i]["penta"];
                        $toal_rota += $data[$i]["rota"];
                        $toal_neumo += $data[$i]["neumo"];
                        $toal_apo += $data[$i]["apo"];
                        $toal_spr += $data[$i]["spr"];

                        $i++;
                    }
                }

                $totales = [];

                $totales[] = $toal_penta;
                $totales[] = $toal_rota;
                $totales[] = $toal_neumo;
                $totales[] = $toal_apo;
                $totales[] = $toal_spr;

                $i = count($data);

                $all_avance  = (min($totales) / $total_meta_anual ) * 100;

                $data[$i]["penta"] = $toal_penta;
                $data[$i]["rota"] = $toal_rota;
                $data[$i]["neumo"] = $toal_neumo;
                $data[$i]["apo"] = $toal_apo;
                $data[$i]["spr"] = $toal_spr;
                $data[$i]["meta_mensual_int"] = (int)$total_meta_mensual;
                $data[$i]["meta_anual"] = $total_meta_anual;
                $data[$i]["avance"] = round($all_avance,2);

                $result["rows"] = $data;

            } catch (Exception $ex) {
                $result["error"] = "Error al cargar los datos del excel" . $ex->getMessage();
            }

        }
        return $result;
    }

    function insVacunaGeneral()
    {

        $result = ["error" => "", "rows" => []];

        $data["vacunas"] = isset($_POST["data"]) ? $_POST["data"] : [];
        $data["id_usuario"] = isset($_SESSION["usuario"]["id_usuario"]) ? $_SESSION["usuario"]["id_usuario"] : 0;

        if ($data["id_usuario"] == 0 || $data["id_usuario"] == "") {
            $result["error"] = "El usuario es requerido";
        }

        if (count($data["vacunas"]) == 0) {
            $result["error"] = "Error al guardar datos";
        }

        if ($result["error"] === "") {
            $result = $this->repository->insVacunaGeneral($data);
        }

        return $result;
    }

}























