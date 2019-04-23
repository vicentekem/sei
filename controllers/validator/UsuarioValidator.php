<?php
/**
 * Created by PhpStorm.
 * User: ACER
 * Date: 18/03/2019
 * Time: 08:34 AM
 */

require_once "../config/Conexion.php";
require_once "../models/UsuarioRepository.php";

class UsuarioValidator
{

    private $repository;

    public function __construct()
    {
        $this->repository = new UsuarioRepository();
    }

    public function login()
    {

        $array_result = ["errors" => [] ];

        $usuario = isset($_POST["usuario"])? $_POST["usuario"] : "";
        $clave = isset($_POST["clave"])? $_POST["clave"] : "";

        if($usuario == ""){
            $array_result["errors"][] = "Ingrese usuario";
        }

        if($clave == ""){
            $array_result["errors"][] = "Ingrese contraseña";
        }

        if( count($array_result["errors"]) == 0){
            $array_result = $this->repository->login( $usuario , $clave );
        }

        return $array_result;
    }

    function getUsuarios(){
        $result = ["error" => ""];

        if( $result["error"] == ""){
            if(isset($_GET["pageSize"]) && isset($_GET["pageIndex"]) ){

                $data["pageSize"] = isset($_GET["pageSize"]) ? $_GET["pageSize"] : 0;
                $data["pageIndex"] = isset($_GET["pageIndex"]) ? $_GET["pageIndex"] : 0;

                $result = $this->repository->getUsuariosPaginate($data);
            }else{
                $result = $this->repository->getUsuarios();
            }
        }

        return $result;
    }

    function updUsuario(){

        $result = ["error" => "", "rows" => []];

        $data["id_usuario"] = isset($_POST["id_usuario"]) ? $_POST["id_usuario"] : 0;
        $data["nombre_usuario"] = isset($_POST["nombre_usuario"]) ? $_POST["nombre_usuario"] : '';
        $data["dni"] = isset($_POST["dni"]) ? $_POST["dni"] : '';
        $data["nombres"] = isset($_POST["nombres"]) ? $_POST["nombres"] : '';
        $data["apellido_pat"] = isset($_POST["apellido_pat"]) ? $_POST["apellido_pat"] : '';
        $data["apellido_mat"] = isset($_POST["apellido_mat"]) ? $_POST["apellido_mat"] : '';
        $data["email"] = isset($_POST["email"]) ? $_POST["email"] : '';

        if( $data["id_usuario"] === 0 ) $result["error"] = "El id del usuario requerido";
        else if( $data["nombre_usuario"] === "" ) $result["error"] = "El nombre de usuario es requerido";
        else if( $data["dni"] === "" ) $result["error"] = "El DNI es requerido";
        else if( $data["nombres"] === "" ) $result["error"] = "El nombre es requerido";
        else if( $data["apellido_pat"] === "" ) $result["error"] = "El apellido paterno es requerido";
        else if( $data["apellido_mat"] === "" ) $result["error"] = "El apellido materno es requerido";
        else if( $data["email"] === "" ) $result["error"] = "El email es requerido";
        
        if ($result["error"] === "") {
            $result = $this->repository->updUsuario($data);
        }

        return $result;
    }

    function insUsuario(){

        $result = ["error" => "", "rows" => []];

        $data["nombre_usuario"] = isset($_POST["nombre_usuario"]) ? $_POST["nombre_usuario"] : '';
        $data["dni"] = isset($_POST["dni"]) ? $_POST["dni"] : '';
        $data["nombres"] = isset($_POST["nombres"]) ? $_POST["nombres"] : '';
        $data["apellido_pat"] = isset($_POST["apellido_pat"]) ? $_POST["apellido_pat"] : '';
        $data["apellido_mat"] = isset($_POST["apellido_mat"]) ? $_POST["apellido_mat"] : '';
        $data["email"] = isset($_POST["email"]) ? $_POST["email"] : '';
        $data["id_rol"] = isset($_POST["id_rol"]) ? $_POST["id_rol"] : 0;

        if( $data["nombre_usuario"] === "" ) $result["error"] = "El nombre de usuario es requerido";
        else if( $data["dni"] === "" ) $result["error"] = "El DNI es requerido";
        else if( $data["nombres"] === "" ) $result["error"] = "El nombre es requerido";
        else if( $data["apellido_pat"] === "" ) $result["error"] = "El apellido paterno es requerido";
        else if( $data["apellido_mat"] === "" ) $result["error"] = "El apellido materno es requerido";
        else if( $data["email"] === "" ) $result["error"] = "El email es requerido";
        else if( $data["id_rol"] === 0 ) $result["error"] = "Selecciones rol de usuario";

        if ($result["error"] === "") {
            $result = $this->repository->insUsuario($data);
        }

        return $result;
    }

}


















