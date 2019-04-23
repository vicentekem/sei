<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 9/04/2019
 * Time: 09:23
 */

class AccesosRepository
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getAccesosByRol($data)
    {
        $result = ["error" => "", "rows" => []];

        try {

            $cn = $this->conexion->getConexion();

            $count = "select count(*) as count from rol_permiso where id_rol = ?";

            $sql = "select id_rol,id_permiso as id,me.bar_name as desc,me.pad_name as desc_pad
                    from rol_permiso rp
                    inner join menu me on me.id_menu = rp.id_permiso
                    where id_rol = ?";

            $stmt = $cn->prepare($count);

            $stmt->bindValue(1, $data["id_rol"]);

            $ok = $stmt->execute();

            if ($ok) {

                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["total_filas"] = $row["count"];
                }else{
                    $result["error"] = "No se pudo obtener los datos";
                }

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

            $stmt = $cn->prepare($sql);

            $stmt->bindValue(1, $data["id_rol"]);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

                if (count($result["rows"]) == 0) {
                    $result["error"] = "No se encontraron registros";
                }

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo obtener el listado de datos";
        }

        return $result;
    }


    public function saveAccesos($data)
    {
        $result = ["error" => "", "rows" => []];

        try {

            $cn = $this->conexion->getConexion();

            $del = "delete from rol_permiso where id_rol = :id_rol";

            $sql = "insert into rol_permiso(id_rol, id_permiso) values (:id_rol,:id_acc)";

            //obtener listado de permiso_usuario
            $stmt = $cn->prepare($del);

            $stmt->bindValue(":id_rol", $data["id_rol"]);

            $ok = $stmt -> execute();

            $listado = [];

            if($ok){

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $listado[] = $row;
                }

            }else{
                $result["error"] = "Error al guardar datos";
            }

            //eliminar e insertar permisos
            $cn->beginTransaction();

            $stmt = $cn->prepare($del);

            $stmt->bindValue(":id_rol", $data["id_rol"]);

            $ok = $stmt -> execute();

            if(!$ok){
                $cn->rollBack();
                $result["error"] = "Error al guardar los datos";
            }else{

                foreach ( $data["accesos"] as $acceso){

                    $stmt = $cn->prepare($sql);

                    $stmt->bindValue(":id_rol", $data["id_rol"]);
                    $stmt->bindValue(":id_acc", $acceso["id"]);

                    $ok = $stmt->execute();

                    if(!$ok){
                        $cn->rollBack();
                        $result["error"] = "Error al guardar los datos";
                        break;
                    }
                }

            }

            $cn->commit();

        } catch (Exception $ex) {

            if( isset($cn) ) $cn->rollBack();

            $result["error"] = "No se pudo obtener el listado de datos";
        }finally{
            $stmt = null;
            $cn = null;
        }

        return $result;
    }

}



















