<?php


class VistasControlador
{
    function __construct()
    {

    }

    public function loadView($url)
    {
        global $urls;

        if (in_array($url, array_keys( $urls ))) {

            if( file_exists( "views/" .  $urls[$url] ) ){
                include "views/" .  $urls[$url];
            }else{
                echo "Página no encontrada";
            }

        }else if ($url == "logout"){


            $_SESSION["usuario"] = null;

            session_destroy();

            header('location:?url=inicio');

        }else{
            echo "Página no encontrada";
        }
    }

}














