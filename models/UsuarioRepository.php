<?php

class UsuarioRepository
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function add($usuario){

    }

    public function login($usuario,$clave){

        $result = ["errors" => []];

        try{

            $cn = $this->conexion->getConexion();

            $sql = "
                        select 
                            usu.id as id_usuario , usu.nombre_usuario,
                            upper(usu.nombres || ' ' || usu.apellido_pat || ' ' || usu.apellido_mat) as nombre_completo,
                            usu.email, usu.dni
                        from usuario usu                         
                        where usu.nombre_usuario = :usuario and usu.clave_usuario = md5(:clave) and usu.activo = true";

            $stmt = $cn->prepare( $sql );

            $stmt->bindParam(":usuario",$usuario);
            $stmt->bindParam(":clave",$clave);

            $ok = $stmt->execute();

            if($ok){
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $result["row"] = $row;
                }else{
                    $result["errors"][] = "El usuario o contraseña ingresada es incorrecta";
                }
            }else{
                $result["errors"][] = "Error en la aunteticación de usuario";
            }

        }catch(Exception $ex){
            $cn = null;
            $result["errors"][] = "Error en la aunteticación de usuario";
        }

        return $result;

    }

    public function getUsuariosPaginate($data){

        $result = ["error" => "", "rows" => [] ];

        try{

            $cn = $this->conexion->getConexion();

            $sql = "select 
                        usu.id as id_usuario , usu.nombre_usuario,
                        upper(usu.nombres || ' ' || usu.apellido_pat || ' ' || usu.apellido_mat) as nombre_completo,
                        usu.nombres, usu.apellido_pat, usu.apellido_mat, usu.email, usu.dni
                    from usuario usu
                    order by id_usuario 
                    LIMIT :pageSize OFFSET ( (:pageIndex - 1) * :pageSize ) ";

            $total_sql = "SELECT COUNT(*) as total_filas FROM usuario";

            $stmt = $cn->prepare( $total_sql );
            $ok = $stmt->execute();

            if ($ok) {

                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["total_filas"] = $row["total_filas"];
                }

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

            $stmt = $cn->prepare( $sql );

            $stmt->bindParam(":pageSize",$data["pageSize"]);
            $stmt->bindParam(":pageIndex",$data["pageIndex"]);

            $ok = $stmt->execute();

            if($ok){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $result["rows"][] = $row;
                }
            }else{
                $result["error"] = "No se pudo obtener los datos";
            }

        }catch(Exception $ex){
            $cn = null;
            $result["error"] = "Error al obtener datos " . $ex -> getMessage();
        }

        return $result;
    }

    public function getUsuarios(){

        $result = ["error" => ""];

        try{

            $cn = $this->conexion->getConexion();

            $sql = "select 
                        usu.id as id_usuario , usu.nombre_usuario,
                        upper(usu.nombres || ' ' || usu.apellido_pat || ' ' || usu.apellido_mat) as nombre_completo,
                        usu.email, usu.dni
                    from usuario usu
                    order by id_usuario";

            $stmt = $cn->prepare( $sql );

            $ok = $stmt->execute();

            if($ok){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $result["rows"][] = $row;
                }
            }else{
                $result["error"] = "No se pudo obtener los datos";
            }

        }catch(Exception $ex){
            $cn = null;
            $result["error"] = "Error en la aunteticación de usuario";
        }

        return $result;

    }

    public function updUsuario($data){
        $result = ["error" => ""];

        try{

            $cn = $this->conexion->getConexion();

            $sql = "update usuario set 
                      nombre_usuario = :nombre_usuario,
                      dni = :dni,
                      nombres = :nombres,
                      apellido_pat = :apellido_pat,
                      apellido_mat = :apellido_mat,
                      email = :email where id = :id_usuario                      
                ";

            $stmt = $cn->prepare( $sql );

            $stmt -> bindParam( ':nombre_usuario' ,$data['nombre_usuario'] );
            $stmt -> bindParam( ':dni' ,$data['dni'] );
            $stmt -> bindParam( ':nombres' ,$data['nombres'] );
            $stmt -> bindParam( ':apellido_pat' ,$data['apellido_pat'] );
            $stmt -> bindParam( ':apellido_mat' ,$data['apellido_mat'] );
            $stmt -> bindParam( ':email',$data['email'] );
            $stmt -> bindParam( ':id_usuario',$data['id_usuario'] );

            $ok = $stmt->execute();

            if(!$ok){
                $result["error"] = "No se pudo editar los datos";
            }

        }catch(Exception $ex){
            $cn = null;
            $result["error"] = "Error al ediar los datos " .$ex->getMessage();
        }

        return $result;
    }

    public function insUsuario($data){
        $result = ["error" => ""];

        $sql_accesos = "select 
                                id_rol , id_permiso 
                            from rol_permiso 
                            where id_rol = :id_rol";

        $sql = "insert into usuario 
                        (nombre_usuario, clave_usuario,nombres, apellido_pat,apellido_mat,email, dni, rol) 
                      values ( 
                          :nombre_usuario,
                          md5(:clave_usuario),
                          :nombres,
                          :apellido_pat,
                          :apellido_mat,
                          :email,
                          :dni,
                          :id_rol
                      )";
        $sql_usuario = "select id as id_usuario from usuario where dni = :dni";

        $sql_insert_accesos = "insert into usuario_permiso(id_usuario, id_permiso) values(:id_usuario,:id_permiso)";

        try{

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare( $sql_accesos );

            $stmt -> bindParam( ':id_rol',$data['id_rol'] );

            $ok = $stmt->execute();

            $accesos = [];

            if($ok){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $accesos[] = $row;
                }
            }else{
                $result["error"] = "No se pudo obtener los datos";
            }

            if($result["error"] === ""){

                $stmt = $cn->prepare( $sql );

                $stmt -> bindParam( ':nombre_usuario' ,$data['nombre_usuario'] );
                $stmt -> bindParam( ':clave_usuario' ,$data['dni'] );
                $stmt -> bindParam( ':dni' ,$data['dni'] );
                $stmt -> bindParam( ':nombres' ,$data['nombres'] );
                $stmt -> bindParam( ':apellido_pat' ,$data['apellido_pat'] );
                $stmt -> bindParam( ':apellido_mat' ,$data['apellido_mat'] );
                $stmt -> bindParam( ':email',$data['email'] );
                $stmt -> bindParam( ':id_rol',$data['id_rol'] );

                $ok = $stmt->execute();

                if(!$ok){
                    $result["error"] = "No se pudo guardar los datos";
                }
            }

            if($result["error"] === ""){

                $stmt = $cn->prepare( $sql_usuario );

                $stmt -> bindParam( ':dni',$data['dni'] );

                $ok = $stmt->execute();

                $usuario = [];

                if($ok){
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $usuario = $row;
                    }
                }else{
                    $result["error"] = "No se pudo obtener los datos";
                }

            }

            if($result["error"] === ""){

                $cn->beginTransaction();

                foreach ( $accesos as $acceso ){

                    $stmt = $cn->prepare( $sql_insert_accesos );

                    $stmt -> bindParam( ':id_usuario' ,$usuario['id_usuario'] );
                    $stmt -> bindParam( ':id_permiso' ,$acceso['id_permiso'] );

                    $ok = $stmt->execute();

                    if(!$ok){

                        $result["error"] = "No se pudo guardar los datos";
                        $cn->rollBack();
                        break;
                    }

                }

                $cn->commit();

            }

        }catch(Exception $ex){
            $cn = null;
            $result["error"] = "Error al guardar los datos " . $ex->getMessage();
        }

        return $result;
    }

}














