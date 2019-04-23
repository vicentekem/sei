<?php
/**
 * Created by PhpStorm.
 * User: ACER
 * Date: 19/03/2019
 * Time: 02:19 PM
 */

class TablaRepository
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getAllByTable($table_id)
    {

        $result = ["error" => "", "rows" => []];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select id_tipo as id, descripcion from tabla_tipo where id_tabla = ? order by id_tipo";

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, strtoupper($table_id));

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

    public function getAllByTablePaginate($table_id, $desc, $pageSize, $pageIndex)
    {

        $result = ["error" => "", "rows" => []];

        try {

            $cn = $this->conexion->getConexion();
            $sql = "select id_tipo as id, descripcion as desc from tabla_tipo where id_tabla = ? and descripcion like '%' || ? || '%'  order by id_tipo LIMIT ? OFFSET ( (? - 1) * ? ) ";

            $total_sql = "SELECT COUNT(*) as total_filas FROM tabla_tipo where id_tabla = ?";

            $stmt = $cn->prepare($total_sql);

            $stmt->bindValue(1, $table_id);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["total_filas"] = $row["total_filas"];
                }
            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $table_id);
            $stmt->bindValue(2, strtoupper($desc));
            $stmt->bindValue(3, $pageSize);
            $stmt->bindValue(4, $pageIndex);
            $stmt->bindValue(5, $pageSize);

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

    function insertTabla($data)
    {
        $result = ["error" => ""];

        $sel_desc = "select descripcion from tabla_tipo where id_tabla = ? and UPPER(descripcion) = UPPER(?)";
        $select = "select MAX(id_tipo) as max_id from tabla_tipo where id_tabla = ?";
        $sql = "insert into tabla_tipo(id_tabla, id_tipo , descripcion) values(?,?,?)";

        try {

            $cn = $this->conexion->getConexion();

            //ejecutar el select
            $stmt = $cn->prepare($sel_desc);

            $stmt->bindValue(1, $data["id_tabla"]);
            $stmt->bindValue(2, $data["desc"]);

            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count == 0) {

                //ejecutar el select
                $stmt = $cn->prepare($select);
                $stmt->bindValue(1, $data["id_tabla"]);
                $ok = $stmt->execute();

                if ($ok) {
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $id_tipo = $row['max_id'] == null ? 1 : $row['max_id'] + 1;
                    } else {
                        $result["error"] = "No se pudo registrar los datos";
                    }
                }

            } else {
                $result["error"] = "No se permiten duplicados";
            }

            if ($result["error"] == "") {

                $stmt = $cn->prepare($sql);

                $stmt->bindValue(1, $data["id_tabla"]);
                $stmt->bindValue(2, $id_tipo);
                $stmt->bindValue(3, strtoupper($data["desc"]));

                $ok = $stmt->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }


        } catch (Exception $ex) {
            $result["error"] = "Error al registrar" . $ex->getMessage();
        } finally {
            $cn = null;
        }

        return $result;

    }

    function updateTabla($data)
    {
        $result = ["error" => ""];
        $sel_desc = "select descripcion from tabla_tipo where id_tabla = ? and UPPER(descripcion) = UPPER(?)";
        $sql = "update tabla_tipo set descripcion = ? where id_tabla = ? and id_tipo = ?  ";

        try {

            $cn = $this->conexion->getConexion();

            //ejecutar el select
            $stmt = $cn->prepare($sel_desc);

            $stmt->bindValue(1, $data["id_tabla"]);
            $stmt->bindValue(2, $data["desc"]);

            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count == 0) {

                $stmt = $cn->prepare($sql);

                $stmt->bindValue(1, strtoupper($data["desc"]));
                $stmt->bindValue(2, $data["id_tabla"]);
                $stmt->bindValue(3, $data["id_tipo"]);

                $ok = $stmt->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo guardar los datos";
                }

            } else {
                $result["error"] = "No se permiten duplicados";
            }


        } catch (Exception $ex) {
            $result["error"] = "Error al registrar los datos " . $ex->getMessage();
        } finally {
            $cn = null;
        }

        return $result;

    }

}











