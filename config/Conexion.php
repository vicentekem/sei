<?php

class Conexion
{
    private $host = "localhost";
    private $user = "postgres";
    private $pass = "postgres123";
    private $bdname = "estrategias_indicadores";

    function getConexion(){

        $conexion = null;

        try{

            $conexion = new PDO(
                "pgsql:host=" . $this->host . ";dbname=". $this->bdname,
                $this->user,
                $this->pass
            );

            $conexion ->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            $conexion = null;
        }

        return $conexion;
    }

}

