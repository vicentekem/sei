<?php

class VacunaRepository
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getMetasPorEstablecimientos($data)
    {
        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql = "select es.codigo_renaes,es.descripcion as establecimiento , me.meta_anual,mg.anio,me.id as meta_id
                        from establecimiento es inner join meta me on es.id = me.establecimiento
                        inner join meta_general mg on mg.id = me.meta_general
                        where anio = ?";

            $stmt = $cn->prepare($sql);
            $stmt->bindValue(1, strtoupper($data["anio"]));

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
        }finally{
            $cn = null;
            $stmt = null;
        }

        return $result;
    }

    public function insVacunaGeneral($data){

        $result = ["error" => ""];

        $sql = "insert into vacuna_general(meta, mes, penta, rota, neumo, apo, spr, avance_mensual, id_usuario) 
                  values(?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {

            $cn = $this->conexion->getConexion();

            $cn->beginTransaction();

            foreach ( $data["vacunas"] as $vacuna_general ){

                //echo $vacuna_general["meta_id"] . " " . $vacuna_general["mes"] . "<br/>";

                $stmt = $cn->prepare($sql);

                $stmt->bindValue(1, strtoupper($vacuna_general["meta_id"]));
                $stmt->bindValue(2, strtoupper($vacuna_general["mes"]));
                $stmt->bindValue(3, strtoupper($vacuna_general["penta"]));
                $stmt->bindValue(4, strtoupper($vacuna_general["rota"]));
                $stmt->bindValue(5, strtoupper($vacuna_general["neumo"]));
                $stmt->bindValue(6, strtoupper($vacuna_general["apo"]));
                $stmt->bindValue(7, strtoupper($vacuna_general["spr"]));
                $stmt->bindValue(8, strtoupper($vacuna_general["avance"]));
                $stmt->bindValue(9, strtoupper($data["id_usuario"]));

                $ok = $stmt->execute();

                if (!$ok) {
                    $cn->rollBack();
                    $result["error"] = "No se pudo guardar los datos";
                    break;
                }
            }

            if( $result["error"] === "" ){
                $cn->commit();
            }

        } catch (Exception $ex) {
            //$cn->rollBack();
            $result["error"] = "Error al guardar los datos";
        }finally{
            $stmt = null;
            $cn = null;
        }

        return $result;
    }

    function getVacunaGeneralMensual($data){

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql_count = "select count(*) as cant_reg
                        from vacuna_general vg
                    inner join meta me on me.id = vg.meta
                    inner join meta_general mg on me.meta_general = mg.id
                    where vg.mes = :mes and mg.anio = :anio";

            $sql = "select 
                        ri.id as id_ris , ri.val_abrev as ris,es.id as id_establecimiento,
                        es.descripcion as establecimiento,mg.anio,tt.id_tipo as nro_mes,tt.descripcion as mes,mg.meta_porcentaje,me.meta_anual,
                        COALESCE(vg.penta,0) as penta, COALESCE(vg.rota,0) as rota,	COALESCE(vg.neumo,0) as neumo,
                        COALESCE(vg.apo,0) as apo, COALESCE(vg.spr,0) as spr, COALESCE(vg.avance_mensual,0) as avance_mensual,
                        COALESCE((select sum(avance_mensual)
                            from vacuna_general 	
                            where meta = me.id and mes BETWEEN 1 and :mes
                            group by meta ),0) as avance_acumulado,
                        COALESCE( (select sum(avance_mensual) 
                            from vacuna_general	where meta = me.id
                            group by meta ),0 ) as avance_acumulado_total
                    from meta me
                    inner join meta_general mg on me.meta_general = mg.id
                    inner join establecimiento es on es.id = me.establecimiento
                    left join vacuna_general vg on vg.meta = me.id and vg.mes = :mes
                    left join tabla_tipo tt on tt.id_tipo = vg.mes and tt.id_tabla = 1
                    left join ris ri on ri.id = es.red
                    where (tt.id_tipo = :mes or tt.id_tipo is null) and mg.anio = :anio and mg.paciente_tipo_edad = :grupo
                    order by ri.id,es.id
                    LIMIT :size OFFSET (:index - 1)* :size";


            $stmt = $cn->prepare($sql_count);

            $stmt->bindValue(":mes", strtoupper($data["mes"]));
            $stmt->bindValue(":anio", strtoupper($data["anio"]));

            $ok = $stmt->execute();

            $cant_reg = 0;

            if ($ok) {

                if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $cant_reg = $row["cant_reg"];
                }

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

            if($result["error"] === "" && $cant_reg > 0 ){

                $stmt = $cn->prepare($sql);

                $stmt->bindValue(":mes", strtoupper($data["mes"]));
                $stmt->bindValue(":anio", strtoupper($data["anio"]));
                $stmt->bindValue(":size", strtoupper($data["pageSize"]));
                $stmt->bindValue(":index", strtoupper($data["pageIndex"]));
                $stmt->bindValue(":grupo", strtoupper($data["grupo"]));

                $ok = $stmt->execute();

                if ($ok) {

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result["rows"][] = $row;
                    }

                    if(count($result["rows"]) == 0) $result["error"] = "No se encontraron datos";

                } else {
                    $result["error"] = "No se pueden mostrar los registros";
                }

            }else{
                $result["error"] = "No se encontraron registros";
            }

            if($result["error"] === ""){

                $sql_count = "select count(*) as total_rows
                    from meta me
                    inner join meta_general mg on me.meta_general = mg.id
                    inner join establecimiento es on es.id = me.establecimiento                    
                    left join vacuna_general vg on vg.meta = me.id and vg.mes = :mes
                    left join tabla_tipo tt on tt.id_tipo = vg.mes and tt.id_tabla = 1
                    left join ris ri on ri.id = es.red
                    where (tt.id_tipo = :mes or tt.id_tipo is null) and mg.anio = :anio and mg.paciente_tipo_edad = :grupo";

                $stmt = $cn->prepare($sql_count);

                $stmt->bindValue(":mes", strtoupper($data["mes"]));
                $stmt->bindValue(":anio", strtoupper($data["anio"]));
                $stmt->bindValue(":grupo", strtoupper($data["grupo"]));

                $ok = $stmt->execute();

                if($ok){
                    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result["total_rows"] = $row["total_rows"];
                    }
                }else{
                    $result["error"] = "No se pueden mostrar los registros";
                }
            }

        } catch (Exception $ex) {
            $result["error"] = "Error al obtener datos";
        }finally{
            $cn = null;
            $stmt = null;
        }

        return $result;

    }

    function getVgGeneral($data){

        $result = ["error" => "","rows" =>[] ];

        try {

            $cn = $this->conexion->getConexion();

            $sql_count = "select count(*) as cant_reg
                            from vacuna_general vg
                        inner join meta me on me.id = vg.meta
                        inner join meta_general mg on me.meta_general = mg.id
                        where mg.anio = :anio";

            $sql = "select 
                        es.id,r.id as id_ris,r.val_abrev as ris,codigo_renaes,es.descripcion as establecimiento, 
                        COALESCE(CAST(public.get_meta_by_mes(me.id,1) AS VARCHAR(5)) ,'NULL') as meta_1,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,2) AS VARCHAR(5)),'NULL') as meta_2,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,3) AS VARCHAR(5)),'NULL') as meta_3,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,4) AS VARCHAR(5)),'NULL') as meta_4,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,5) AS VARCHAR(5)),'NULL') as meta_5,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,6) AS VARCHAR(5)),'NULL') as meta_6,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,7) AS VARCHAR(5)),'NULL') as meta_7,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,8) AS VARCHAR(5)),'NULL') as meta_8,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,9) AS VARCHAR(5)),'NULL') as meta_9,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,10) AS VARCHAR(5)),'NULL') as meta_10,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,11) AS VARCHAR(5)),'NULL') as meta_11,
                        COALESCE(CAST(public.get_meta_by_mes(me.id,12) AS VARCHAR(5)),'NULL') as meta_12,                        
                        COALESCE( CAST( (select sum(avance_mensual) 
                            from vacuna_general	where meta = me.id
                            group by meta) AS VARCHAR(5) ) ,'NULL') as avance_total                        
                    from establecimiento es
                    inner join meta me on me.establecimiento = es.id
                    inner join meta_general mg on mg.id = me.meta_general
                    inner join ris r on r.id = es.red
                    where mg.anio = :anio and es.activo = true and mg.paciente_tipo_edad = :grupo 
                    LIMIT :size OFFSET (:index - 1)* :size";


            $stmt = $cn->prepare($sql_count);

            $stmt->bindValue(":anio", strtoupper($data["anio"]));

            $ok = $stmt->execute();

            $cant_reg = 0;

            if ($ok) {

                if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $cant_reg = $row["cant_reg"];
                }

            } else {
                $result["error"] = "No se pueden mostrar los registros";
            }

            if($result["error"] === "" && $cant_reg > 0 ){

                $stmt = $cn->prepare($sql);

                $stmt->bindValue(":anio", strtoupper($data["anio"]));
                $stmt->bindValue(":size", strtoupper($data["pageSize"]));
                $stmt->bindValue(":index", strtoupper($data["pageIndex"]));
                $stmt->bindValue(":grupo", strtoupper($data["grupo"]));

                $ok = $stmt->execute();

                if ($ok) {

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result["rows"][] = $row;
                    }

                    if(count($result["rows"]) == 0) $result["error"] = "No se encontraron datos";

                } else {
                    $result["error"] = "No se pueden mostrar los registros";
                }

            }else{
                $result["error"] = "No se encontraron registros";
            }

            if($result["error"] === ""){

                $sql_count = "select 
                                    count(*) as total_rows                                   
                                from establecimiento es
                                inner join meta me on me.establecimiento = es.id
                                inner join meta_general mg on mg.id = me.meta_general
                                inner join ris r on r.id = es.red
                                where mg.anio = :anio and es.activo = true and mg.paciente_tipo_edad = :grupo";

                $stmt = $cn->prepare($sql_count);

                $stmt->bindValue(":anio", strtoupper($data["anio"]));
                $stmt->bindValue(":grupo", strtoupper($data["grupo"]));

                $ok = $stmt->execute();

                if($ok){
                    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result["total_rows"] = $row["total_rows"];
                    }
                }else{
                    $result["error"] = "No se pueden mostrar los registros";
                }
            }

        } catch (Exception $ex) {
            $result["error"] = "Error al obtener datos" . $ex->getMessage();
        }finally{
            $cn = null;
            $stmt = null;
        }

        return $result;

    }

}










