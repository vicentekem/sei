<?php

class EstablecimientoRepository
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getAllEstablecimientos()
    {

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select id,codigo_renaes, descripcion
                        from establecimiento order by codigo_renaes";

            $stmt = $cn->prepare($sql);

            $ok = $stmt->execute();

            if ($ok) {

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

                if(count($result["rows"]) == 0) $result["error"] = "No se encontraron datos";

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

        } catch (Exception $ex) {
            $result["error"] = "Error al obtener datos";
        }

        return $result;
    }

}