<?php

    require_once "config/config.php";
    require_once "config/handler.php";
    require_once "controllers/VistasControlador.php";

    $controller = new VistasControlador();

    $url = isset($_GET["url"])? $_GET["url"] : "";
    $controller->loadView( $url );














