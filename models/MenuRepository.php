<?php

class MenuRepository
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getSubMenusUrlsForUser($id_usuario)
    {

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select 
                        usp.id_usuario,men.alias,men.bar_link archivo_url
                    from usuario_permiso usp
                    inner join usuario usu on usu.id = usp.id_usuario
                    inner join menu men on men.id_menu = usp.id_permiso
                    where usp.id_usuario = :id_usu
                    order by men.bar_order";

            $stmt = $cn->prepare($sql);

            $stmt->bindParam(":id_usu",$id_usuario);

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

    public function getMenuUrlsForUser($id_usuario)
    {

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select substr(id_menu,1,2) as pad_menu,pad_name,bar_order,bar_img
                    from usuario_permiso usp
                        inner join usuario usu on usu.id = usp.id_usuario
                        inner join menu men on men.id_menu = usp.id_permiso
                    where usp.id_usuario = :id_usu
                    group by substr(id_menu,1,2),pad_name,bar_order,bar_img
                    order by men.bar_order";

            $stmt = $cn->prepare($sql);

            $stmt->bindParam(":id_usu",$id_usuario);

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
            $result["error"] = "Error al obtener datos" .$ex->getMessage() ;
        }

        return $result;
    }

    public function getSubMenuUrlsByMenu($pad_menu,$id_usuario)
    {

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select id_menu as id,bar_name as desc,id_menu,bar_name,bar_link,bar_img,bar_img_p,alias
                      from menu me
                      inner join usuario_permiso up on me.id_menu = up.id_permiso
                    where substr(id_menu,1,2) = :pad_menu and  up.id_usuario = :id_usuario";

            $stmt = $cn->prepare($sql);

            $stmt->bindParam(":pad_menu",$pad_menu);
            $stmt->bindParam(":id_usuario",$id_usuario);

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


    public function getMenu()
    {

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select substr(id_menu,1,2) as id,pad_name as desc
                      from menu
                    group by substr(id_menu,1,2),pad_name";

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