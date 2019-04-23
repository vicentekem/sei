<?php

session_start();

$siteName = ""; // la variable siteName define el nombre del sistema
$currentPage = "INICIO";

//$url_base = "http://localhost/";

//$urls: variable para el manejo de rutas amigables
// sintax : urls[ alias(url que se mostrará en la navegador ) ] =  (archivo que se va a renderizar)
$urls = [];
$urls["login"] = "login.php";
$urls[""] = "vacunas.php";
$urls["inicio"] = "vacunas.php";

if( isset($_SESSION["usuario"]) ){

    require_once "config/Conexion.php";
    require_once "models/MenuRepository.php";

    $repository = new MenuRepository();

    $result =  $repository->getSubMenusUrlsForUser( $_SESSION["usuario"]["id_usuario"] );

    if(count($result["rows"]) > 0 ){

        foreach( $result["rows"] as $row ){

            $urls[ $row["alias"] ] = $row["archivo_url"];

        }

    }

}



