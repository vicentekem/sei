<?php


class PostulanteRepository
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getDatosPersonales($id_postulante)
    {

        $result = ["error" => ""];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select 
                        id, tipo_doc, nro_doc, correo, nacionalidad, apellido_pat,
                        apellido_pat,apellido_mat,nombres, departamento, provincia, 
                        distrito, tipo_zona,estado_civil, nombre_zona, tipo_via, direccion, telefono,
                        celular, discapacidad,licenciado_ffaa, sustento_doc
                    from postulante where activo = true and id = ?";

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, strtoupper($id_postulante));

            $ok = $stmt->execute();

            if ($ok) {

                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["row"] = $row;
                } else {
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

    public function getDatosSustentatorios($id_postulante)
    {

        $result = ["error" => ""];

        $sql = "select 
                  t.descripcion as tipo_doc_adjunto,
                  sustento_doc,to_char(fecha_carga_doc,'dd/mm/yyyy') as fecha_carga_doc 
                from postulante p inner join tabla_tipo t on p.tipo_doc_adjunto = t.id_tipo and t.id_tabla = 12 where id = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, strtoupper($id_postulante));

            $ok = $stmt->execute();

            if ($ok) {

                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["row"] = $row;
                } else {
                    $result["error"] = "No se encontraron los datos sustentatorios";
                }

            } else {
                $result["error"] = "No se encontraron los datos sustentatorios";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron los datos sustentatorios";
        }

        return $result;

    }

    public function getFormacionAcademica($id_postulante)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select 
                  p.id_postulante, n.descripcion as nivel , g.descripcion as grado , especialidad,p.nro_detalle,
                  to_char(fecha_extension_grado,'dd/mm/yyy') as fecha_extension_grado, sustento
                from postulante_formacion_academica_detalle p 
                inner join tabla_tipo n on p.nivel = n.id_tipo and n.id_tabla = 1 
                inner join tabla_tipo g on p.grado = g.id_tipo and g.id_tabla = 2
                where id_postulante = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getExperienciaLaboral($id_postulante, $id_convocatoria)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pe.id_postulacion,pe.nro_detalle, tex.descripcion as tipo_experiencia,pe.cargo, 
                    ten.descripcion as tipo_entidad,pe.nombre_entidad,
                    pe.fecha_inicio, pe.fecha_fin, pe.sustento, pe.nro_detalle,pe.id_postulacion
                from postulacion_experiencia pe
                inner join postulacion po on pe.id_postulacion = po.id 
                inner join tabla_tipo tex on pe.tipo_experiencia = tex.id_tipo and tex.id_tabla = 9 
                inner join tabla_tipo ten on pe.tipo_entidad = ten.id_tipo and ten.id_tabla = 10
                where po.id_postulante = ? and po.id_convocatoria = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);
            $stmt->bindValue(2, $id_convocatoria);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getReferencias($id_postulante, $id_convocatoria)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pe.id_postulacion,pe.nro_detalle,pe.nombre_referencia,pe.cargo,pe.nombre_entidad,pe.tel_entidad
                from postulacion_referencia pe
                inner join postulacion po on pe.id_postulacion = po.id 
                where po.id_postulante = ? and po.id_convocatoria = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);
            $stmt->bindValue(2, $id_convocatoria);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getConocimientos($id_postulante, $id_convocatoria, $tipo_conocimiento)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pc.id_postulacion,pc.nro_detalle,pc.tema,ti.descripcion as idioma,tn.descripcion as nivel,
                    pc.sustento,pc.centro_estudios,pc.inicio,pc.fin
                from postulacion_conocimiento pc
                inner join postulacion po on pc.id_postulacion = po.id 
                left join tabla_tipo ti on pc.idioma = ti.id_tipo and ti.id_tabla = 4
                left join tabla_tipo tn on (pc.nivel = tn.id_tipo and tn.id_tabla = 5) or pc.nivel = null
                inner join tabla_tipo tc on pc.tipo_conocimiento = tc.id_tipo and tc.id_tabla = 3
                where po.id_convocatoria = ? and po.id_postulante = ? and pc.tipo_conocimiento = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);

            $stmt->bindValue(1, $id_convocatoria);
            $stmt->bindValue(2, $id_postulante);
            $stmt->bindValue(3, $tipo_conocimiento);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getPublicaciones($id_postulante, $id_convocatoria)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pp.id_postulacion,pp.nro_detalle,pp.editorial, pp.titulo, pp.grado_participacion, 
                    pp.fecha_publicacion,pp.tipo, pp.sustento
                from postulacion_publicacion pp
                inner join postulacion po on pp.id_postulacion = po.id 
                where po.id_postulante = ? and po.id_convocatoria = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);
            $stmt->bindValue(2, $id_convocatoria);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getExposiciones($id_postulante, $id_convocatoria)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pp.id_postulacion,pp.nro_detalle,pp.institucion, pp.tema, pp.ciudad, 
                    pp.fecha_evento,pp.sustento
                from postulacion_exposicion pp
                inner join postulacion po on pp.id_postulacion = po.id 
                where po.id_postulante = ? and po.id_convocatoria = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);
            $stmt->bindValue(2, $id_convocatoria);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function getAnexos($id_postulante, $id_convocatoria)
    {
        $result = ["error" => "", "rows" => []];

        $sql = "select  
                    pe.anexo as id,pe.respuesta
                from postulacion_anexos pe
                inner join postulacion po on pe.id_postulacion = po.id 
                where po.id_postulante = ? and po.id_convocatoria = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $id_postulante);
            $stmt->bindValue(2, $id_convocatoria);

            $ok = $stmt->execute();

            if ($ok) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["rows"][] = $row;
                }

            } else {
                $result["error"] = "No se encontraron  datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se encontraron datos ";
        }

        return $result;
    }

    public function setDatosPersonales($datos_personales)
    {
        $result = ["error" => ""];

        $sql = "update postulante set 
                  nacionalidad = ?, tipo_doc = ?, estado_civil = ?, departamento = ?,
                  provincia = ?, distrito = ?, tipo_zona = ?, tipo_via = ?,
                  nro_doc = ?, apellido_pat = ?, apellido_mat = ?, nombres = ?,
                  nombre_zona = ?, direccion = ?, telefono = ?, celular = ?,
                  correo = ?, discapacidad = ?, licenciado_ffaa = ?  
                where id = ?";

        try {

            $cn = $this->conexion->getConexion();

            //ejecutar el update
            $stmt = $cn->prepare($sql);

            $stmt->bindValue(1, $datos_personales["nacionalidad"]);
            $stmt->bindValue(2, $datos_personales["tipo_doc"]);
            $stmt->bindValue(3, $datos_personales["estado_civil"]);
            $stmt->bindValue(4, $datos_personales["departamento"]);
            $stmt->bindValue(5, $datos_personales["provincia"]);
            $stmt->bindValue(6, $datos_personales["distrito"]);
            $stmt->bindValue(7, $datos_personales["tipo_zona"]);
            $stmt->bindValue(8, $datos_personales["tipo_via"]);
            $stmt->bindValue(9, $datos_personales["nro_doc"]);
            $stmt->bindValue(10, strtoupper($datos_personales["apellido_pat"]));
            $stmt->bindValue(11, strtoupper($datos_personales["apellido_mat"]));
            $stmt->bindValue(12, strtoupper($datos_personales["nombres"]));
            $stmt->bindValue(13, strtoupper($datos_personales["nombre_zona"]));
            $stmt->bindValue(14, strtoupper($datos_personales["direccion"]));
            $stmt->bindValue(15, $datos_personales["telefono"]);
            $stmt->bindValue(16, $datos_personales["celular"]);
            $stmt->bindValue(17, $datos_personales["correo"]);
            $stmt->bindValue(18, $datos_personales["discapacidad"]);
            $stmt->bindValue(19, $datos_personales["licenciado_ffaa"]);
            $stmt->bindValue(20, $datos_personales["id"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo registrar los datos sustentatorios";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos sustentatorios";
        }

        return $result;
    }

    public function register($data)
    {

        $result = ["error" => ""];

        $select1 = "select id from postulante where nro_doc = ?";
        $select2 = "select id from postulante where correo = ?";

        $sql = "insert into postulante(nacionalidad,tipo_doc,nro_doc,nombres,apellido_pat,apellido_mat,correo,clave,activo) 
                values (?,?,?,?,?,?,?,?,false)";

        try {

            $cn = $this->conexion->getConexion();

            //ejecutar el primer select
            $stmt = $cn->prepare($select1);
            $stmt->bindValue(1, $data["nro_doc"]);
            $stmt->execute();
            $num_rows = $stmt->rowCount();

            if ($num_rows != 0) {
                $result["error"] = "Ya hay un usuario con el DNI " . $data["nro_doc"];
            }

            //ejecutar el segundo select
            $stmt = $cn->prepare($select2);
            $stmt->bindValue(1, $data["correo"]);
            $stmt->execute();
            $num_rows = $stmt->rowCount();

            if ($num_rows != 0) {
                $result["error"] = "Ya hay un usuario con el correo " . $data["correo"];
            }

            if ($result["error"] === "") {
                //ejecutar el update
                $stmt = $cn->prepare($sql);

                $stmt->bindValue(1, $data["nacionalidad"]);
                $stmt->bindValue(2, $data["tipo_doc"]);
                $stmt->bindValue(3, $data["nro_doc"]);
                $stmt->bindValue(4, strtoupper($data["nombres"]));
                $stmt->bindValue(5, strtoupper($data["apellido_pat"]));
                $stmt->bindValue(6, strtoupper($data["apellido_mat"]));
                $stmt->bindValue(7, $data["correo"]);
                $stmt->bindValue(8, md5($data["clave"]));

                $ok = $stmt->execute();

                if (!$ok) {
                    $result["error"] = "Error al momento de registrar";
                }
            }


        } catch (Exception $ex) {
            $result["error"] = "Error al momento de registrar los datos";
        } finally {
            $cn = null;
        }

        return $result;
    }

    public function registerComplete($data){

        $result = ["error" => ""];

        $sql = "update postulante set activo = true where nro_doc = ? ";

        try {
            $cn = $this->conexion->getConexion();

            //ejecutar el primer select
            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $data["nro_doc"]);
            $ok = $stmt->execute();


            if (!$ok ) {
                $result["error"] = "Error al confirmar el correo";
            }

        } catch (Exception $ex) {
            $result["error"] = "Error al momento de registrar los datos";
        } finally {
            $cn = null;
        }
        return $result;
    }

    public function setDatosSustentatorios($datos_sustentatorios)
    {

        $result = ["error" => ""];

        $sql = "update postulante set tipo_doc_adjunto = ? , fecha_carga_doc = CURRENT_DATE , sustento_doc = ? where id = ?";

        try {

            $cn = $this->conexion->getConexion();

            //ejecutar el update
            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, $datos_sustentatorios["tipo_doc"]);
            $stmt->bindValue(2, $datos_sustentatorios["sustento_doc_url"]);
            $stmt->bindValue(3, $datos_sustentatorios["id_postulante"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo registrar los datos sustentatorios";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos sustentatorios";
        }

        return $result;

    }

    public function setFormacionAcademica($data)
    {
        $result = ["error" => ""];

        $select = "select max(nro_detalle) as nro_detalle_max from postulante_formacion_academica_detalle where id_postulante = ?";

        $sql = "insert into postulante_formacion_academica_detalle(id_postulante,nro_detalle,nivel,grado,especialidad,centro_estudios,inicio_year,fin_year,fecha_extension_grado,sustento) 
                  values(?,?,?,?,?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();

            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                }
            }

            //ejecutar el update
            $stmt2 = $cn2->prepare($sql);

            $stmt2->bindValue(1, $data["id"]);
            $stmt2->bindValue(2, $nro_detalle);
            $stmt2->bindValue(3, $data["nivel"]);
            $stmt2->bindValue(4, $data["grado"]);
            $stmt2->bindValue(5, strtoupper($data["especialidad"]));
            $stmt2->bindValue(6, strtoupper($data["centro_estudios"]));
            $stmt2->bindValue(7, $data["inicio_year"]);
            $stmt2->bindValue(8, $data["fin_year"]);
            $stmt2->bindValue(9, $data["fecha_extension_grado"]);
            $stmt2->bindValue(10, $data["sustento"]);

            $ok = $stmt2->execute();

            if (!$ok) {
                $result["error"] = "No se pudo registrar los datos";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos" . $ex->getMessage();
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setExperienciaLaboral($data)
    {
        $result = ["error" => ""];

        $select = "select 
                        id as id_postulacion,
                        (select max(nro_detalle) from postulacion_experiencia pe
                        where pe.id_postulacion = po.id) as nro_detalle_max
                    from postulacion po
                    where id_postulante = ? and  id_convocatoria = ?";

        $sql = "insert into postulacion_experiencia(id_postulacion,nro_detalle,tipo_experiencia,cargo,tipo_entidad,nombre_entidad,                        fecha_inicio,fecha_fin,sustento) 
                  values(?,?,?,?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();
            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);
            $stmt1->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                    $id_postulacion = $row['id_postulacion'];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                //ejecutar el update
                $stmt2 = $cn2->prepare($sql);

                $stmt2->bindValue(1, $id_postulacion);
                $stmt2->bindValue(2, $nro_detalle);
                $stmt2->bindValue(3, $data["tipo_experiencia"]);
                $stmt2->bindValue(4, strtoupper($data["cargo"]));
                $stmt2->bindValue(5, $data["tipo_entidad"]);
                $stmt2->bindValue(6, strtoupper($data["nombre_entidad"]));
                $stmt2->bindValue(7, $data["fecha_inicio"]);
                $stmt2->bindValue(8, $data["fecha_fin"]);
                $stmt2->bindValue(9, $data["sustento"]);

                $ok = $stmt2->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }

            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos";
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setReferencias($data)
    {
        $result = ["error" => ""];

        $select = "select 
                        id as id_postulacion,
                        (select max(nro_detalle) from postulacion_referencia pr
                        where pr.id_postulacion = po.id) as nro_detalle_max
                    from postulacion po
                    where id_postulante = ? and  id_convocatoria = ?";

        $sql = "insert into postulacion_referencia(id_postulacion,nro_detalle,nombre_referencia,cargo,nombre_entidad,tel_entidad) values(?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();
            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);
            $stmt1->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                    $id_postulacion = $row['id_postulacion'];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                //ejecutar el update
                $stmt2 = $cn2->prepare($sql);

                $stmt2->bindValue(1, $id_postulacion);
                $stmt2->bindValue(2, $nro_detalle);
                $stmt2->bindValue(3, strtoupper($data["nombre_referencia"]));
                $stmt2->bindValue(4, strtoupper($data["cargo"]));
                $stmt2->bindValue(5, strtoupper($data["nombre_entidad"]));
                $stmt2->bindValue(6, $data["tel_entidad"]);

                $ok = $stmt2->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }

            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos";
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setConocimientos($data)
    {
        $result = ["error" => ""];

        $select = "select 
                        id as id_postulacion,
                        (select max(nro_detalle) from postulacion_conocimiento pc
                        where pc.id_postulacion = po.id) as nro_detalle_max
                    from postulacion po
                    where id_postulante = ? and  id_convocatoria = ?";

        $sql = "insert into postulacion_conocimiento
                    (id_postulacion,nro_detalle,tipo_conocimiento,tema,idioma,nivel,centro_estudios,inicio,fin,duracion,tipo_sustento,sustento) values
                    (?,?,?,?,?,?,?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();
            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);
            $stmt1->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                    $id_postulacion = $row['id_postulacion'];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                //ejecutar el update
                $stmt2 = $cn2->prepare($sql);

                $stmt2->bindValue(1, $id_postulacion);
                $stmt2->bindValue(2, $nro_detalle);
                $stmt2->bindValue(3, $data["tipo_conocimiento"]);
                $stmt2->bindValue(4, strtoupper($data["tema"]));
                $stmt2->bindValue(5, $data["idioma"]);
                $stmt2->bindValue(6, $data["nivel"]);
                $stmt2->bindValue(7, strtoupper($data["centro_estudios"]));
                $stmt2->bindValue(8, $data["inicio"]);
                $stmt2->bindValue(9, $data["fin"]);
                $stmt2->bindValue(10, $data["duracion"]);
                $stmt2->bindValue(11, $data["tipo_sustento"]);
                $stmt2->bindValue(12, $data["sustento"]);

                $ok = $stmt2->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }

            }

        } catch (Exception $ex) {
            $result["error"] = "Error al registrar " . $ex->getMessage();
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setPublicaciones($data)
    {
        $result = ["error" => ""];

        $select = "select 
                        id as id_postulacion,
                        (select max(nro_detalle) from postulacion_publicacion pp
                        where pp.id_postulacion = po.id) as nro_detalle_max
                    from postulacion po
                    where id_postulante = ? and  id_convocatoria = ?";

        $sql = "insert into postulacion_publicacion(id_postulacion,nro_detalle,editorial,titulo,grado_participacion,fecha_publicacion,sustento) 
                  values(?,?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();
            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);
            $stmt1->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                    $id_postulacion = $row['id_postulacion'];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                //ejecutar el update
                $stmt2 = $cn2->prepare($sql);

                $stmt2->bindValue(1, $id_postulacion);
                $stmt2->bindValue(2, $nro_detalle);
                $stmt2->bindValue(3, strtoupper($data["editorial"]));
                $stmt2->bindValue(4, strtoupper($data["titulo"]));
                $stmt2->bindValue(5, strtoupper($data["grado_participacion"]));
                $stmt2->bindValue(6, $data["fecha_publicacion"]);
                $stmt2->bindValue(7, $data["sustento"]);

                $ok = $stmt2->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }

            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos";
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setExposiciones($data)
    {
        $result = ["error" => ""];

        $select = "select
                        id as id_postulacion,
                        (select max(nro_detalle) from postulacion_exposicion pp
                        where pp.id_postulacion = po.id) as nro_detalle_max
                    from postulacion po
                    where id_postulante = ? and  id_convocatoria = ?";

        $sql = "insert into postulacion_exposicion(id_postulacion,nro_detalle,institucion,tema,ciudad,fecha_evento,sustento) 
                  values(?,?,?,?,?,?,?)";

        try {

            $cn1 = $this->conexion->getConexion();
            $cn2 = $this->conexion->getConexion();

            //ejecutar el select
            $stmt1 = $cn1->prepare($select);

            $stmt1->bindValue(1, $data["id"]);
            $stmt1->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt1->execute();

            if ($ok) {
                if ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    $nro_detalle = $row['nro_detalle_max'] == null ? 1 : $row['nro_detalle_max'] + 1;
                    $id_postulacion = $row['id_postulacion'];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                //ejecutar el update
                $stmt2 = $cn2->prepare($sql);

                $stmt2->bindValue(1, $id_postulacion);
                $stmt2->bindValue(2, $nro_detalle);
                $stmt2->bindValue(3, strtoupper($data["institucion"]));
                $stmt2->bindValue(4, strtoupper($data["tema"]));
                $stmt2->bindValue(5, strtoupper($data["ciudad"]));
                $stmt2->bindValue(6, $data["fecha_evento"]);
                $stmt2->bindValue(7, $data["sustento"]);

                $ok = $stmt2->execute();

                if (!$ok) {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos";
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    public function setAnexos($data)
    {
        $result = ["error" => ""];

        $sql_all = "select count(*) as anexos from postulacion_anexos where id_postulacion = ?";

        $sql_select = "select id from postulacion where id_postulante = ? and id_convocatoria = ?";

        $sql_ins = "insert into postulacion_anexos (id_postulacion,anexo,respuesta,completado) values(:id_postulacion,:anexo,:respuesta,true)";

        $sql_upd = "update postulacion_anexos set respuesta = :respuesta, completado = true where id_postulacion = :id_postulacion and anexo=:anexo";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_select);

            $stmt->bindValue(1, $data["id"]);
            $stmt->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt->execute();

            if ($ok) {
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_postulacion = $row["id"];
                } else {
                    $result["error"] = "No se pudo registrar los datos";
                }
            }

            if ($result["error"] === "") {

                $stmt = $cn->prepare($sql_all);
                $stmt->bindValue(1, $id_postulacion);

                $ok = $stmt->execute();

                if ($ok) {
                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $cant_anexos = $row["anexos"];
                    } else {
                        $result["error"] = "No se pudo registrar los datos";
                    }
                }

                if ($result["error"] === "") {

                    $cn->beginTransaction();

                    foreach ($data["anexos"] as $anexo) {

                        //ejecutar el update o el insert
                        if ($cant_anexos == 0) {
                            $stmt = $cn->prepare($sql_ins);
                        } else {
                            $stmt = $cn->prepare($sql_upd);
                        }

                        $stmt->bindParam(":id_postulacion", $id_postulacion);
                        $stmt->bindParam(":anexo", $anexo["id_anexo"]);
                        $stmt->bindParam(":respuesta", $anexo["value"]);

                        $ok = $stmt->execute();

                        if (!$ok) {
                            $result["error"] = "No se pudo registrar los datos";
                            $cn->rollBack();
                            break;
                        }
                    }

                    $cn->commit();

                }
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo registrar los datos";
            $cn->rollBack();
        } finally {
            $cn1 = null;
            $cn2 = null;
        }

        return $result;
    }

    function completePos($data)
    {

        $result = ["error" => ""];

        $sql_upd = "update postulacion set completado = true where id_postulante = ? and id_convocatoria = ? ";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_upd);

            $stmt->bindValue(1, $data["id"]);
            $stmt->bindValue(2, $data["id_convocatoria"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo completar la postulación";
            }


        } catch (Exception $ex) {
            $result["error"] = "No se pudo completar la postulación";
        } finally {
            $cn = null;
        }

        return $result;

    }

    function deleteFormacionAcademica($data)
    {

        $result = ["error" => ""];

        $sql_del = "delete from postulante_formacion_academica_detalle where id_postulante = ? and nro_detalle = ?";

        try {

            $cn = $this->conexion->getConexion();
            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulante"]);
            $stmt->bindValue(2, $data["nro_detalle"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;
    }

    function deleteExperienciaLaboral($data){
        $result = ["error" => ""];

        $sql_del = "delete from postulacion_experiencia where id_postulacion = ? and nro_detalle = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulacion"]);
            $stmt->bindValue(2, $data["nro_detalle"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;
    }

    function deleteReferencias($data){
        $result = ["error" => ""];

        $sql_del = "delete from postulacion_referencia where id_postulacion = ? and nro_detalle = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulacion"]);
            $stmt->bindValue(2, $data["nro_detalle"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;

    }

    function deleteConocimientos($data){
        $result = ["error" => ""];

        $sql_del = "delete from postulacion_conocimiento where id_postulacion = ? and nro_detalle = ? and tipo_conocimiento = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulacion"]);
            $stmt->bindValue(2, $data["nro_detalle"]);
            $stmt->bindValue(3, $data["tipo"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;
    }

    function deletePublicaciones($data){
        $result = ["error" => ""];

        $sql_del = "delete from postulacion_publicacion where id_postulacion = ? and nro_detalle = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulacion"]);
            $stmt->bindValue(2, $data["nro_detalle"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;
    }

    function deleteExposiciones($data){
        $result = ["error" => ""];

        $sql_del = "delete from postulacion_exposicion where id_postulacion = ? and nro_detalle = ?";

        try {

            $cn = $this->conexion->getConexion();

            $stmt = $cn->prepare($sql_del);

            $stmt->bindValue(1, $data["id_postulacion"]);
            $stmt->bindValue(2, $data["nro_detalle"]);

            $ok = $stmt->execute();

            if (!$ok) {
                $result["error"] = "No se pudo eliminar";
            }

        } catch (Exception $ex) {
            $result["error"] = "No se pudo eliminar";
        } finally {
            $cn1 = null;
        }

        return $result;
    }

}

