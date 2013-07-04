<?php

/**
 * Cuenta Contable Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link    	
 */
class Cuenta_contable extends DataMapper {

    var $table = "ccuentas_contables";
    var $has_many = array(
        'poliza_detalle' => array(
            'class' => 'poliza_detalle',
            'other_field' => 'cuenta_contable'
        ),
        'cuentas_bancarias' => array(
            'class' => 'cuenta_bancaria',
            'other_field' => 'cuenta_contable'
        )
    );
    var $has_one = array(
        'ctipo_cuenta_contable' => array(
            'class' => 'tipo_cuenta_contable',
            'other_field' => 'cuentas_contables'
        )
    );

    function Cuenta_contable($id=null) {
        parent::DataMapper($id);
    }

    /**
     * Cuentas Contables
     *
     * Authenticates a user for logging in.
     *
     * @access	public
     * @return	bool
     */
    function get_cuentas_contables() {
        $e = new Cuenta_contable();
        $e->get();
        if ($e->c_rows > 0)
            return $e;
        else
            return FALSE;
    }

    function get_cuentas_contables_list($offset, $per_page) {
        // Create a temporary user object
        $u = new Cuenta_contable();
        $sql = "select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa from ccuentas_contables as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id left join empresas as e on e.id=cb.empresa_id order by banco limit $offset, $per_page";
        //Buscar en la base de datos
        $u->query($sql);
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_cuenta_contable($id) {
        // Create a temporary user object
        $u = new Cuenta_contable();
        //Buscar en la base de datos
        $u->get_by_id($id);
        if ($u->c_rows == 1) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_cuentas_contables_empresa($id) {
        // Create a temporary user object
        $u = new Cuenta_contable();
        //Buscar en la base de datos
        $u->where("empresa_id", $id);
        $u->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_cuentas_contables_filtrado($cuenta_id, $empresa) {
        $cuentas = new Cuenta_contable();
        $cuentas->
                include_related("ctipo_cuenta_contable", "tag")->
                where("cta", $cuenta_id)->
                where("empresa_id", $empresa)->
                where("estatus_general_id", 1)->
                order_by("cta")->order_by("scta")->order_by("sscta")->get();
        return count($cuentas->all) > 0 ? $cuentas : false;
    }
    
    
function get_cuentas_contables_ajax($cuenta_id, $empresa, $texto) {
        $u = new Cuenta_contable();
        $sql =
                "SELECT id, cta, scta, sscta, concat(CAST(scta AS CHAR) ,CAST(sscta AS CHAR), CAST(ssscta AS CHAR) ,tag) as descripcion, tag
        from ccuentas_contables
        where cta='$cuenta_id' and empresa_id='$empresa' and estatus_general_id=1 and
     (concat(CAST(scta AS CHAR) , CAST(sscta AS CHAR) , CAST(ssscta AS CHAR) , tag)  like '%$texto%' )
        order by cta, scta, sscta;";
        $u->query($sql);
        if ($u->c_rows > 0)
            return $u;
        else
            return FALSE;
    }    
    

   

    function get_cuentas_contables_filtrado_count($cuenta_id, $empresa) {
        $u = new Cuenta_contable();
        $sql = "select count(id) as total from ccuentas_contables where cta=$cuenta_id and empresa_id='$empresa' and estatus_general_id=1 order by cta, scta, sscta";
        $u->query($sql);
        if ($u->c_rows > 0)
            return $u->total;
        else
            return FALSE;
    }

    function get_list_cuentas_contables($offset, $per_page, $empresa) {
        $cuentas = new Cuenta_contable();
        $cuentas->
                include_related("ctipo_cuenta_contable", "tag")->
                where("empresa_id", $empresa)->
                where("estatus_general_id", 1)->
                order_by("cta")->order_by("scta")->order_by("sscta")->
                limit($per_page, $offset)->get();
        return count($cuentas->all) > 0 ? $cuentas : false;
    }

    function get_list_cuentas_contables_count($empresa) {
        $u = new Cuenta_contable();
        $sql = "select count(id) as total from ccuentas_contables where empresa_id='$empresa' and estatus_general_id=1 group by cta,scta,sscta order by cta, scta, sscta  ";
        $u->query($sql);
        if ($u->c_rows > 0)
            return $u->total;
        else
            return FALSE;
    }

    function get_list_cuentas_contables_select_todos($empresa) {
        $u = new Cuenta_contable();
        $sql = "select * from ccuentas_contables where empresa_id='$empresa' and scta='0' and estatus_general_id=1 order by cta";
        $u->query($sql);
        if ($u->c_rows > 0)
            return $u;
        else
            return FALSE;
    }

    function get_cuentas_contables_pdf($tipos_cuenta, $cta, $scta, $sscta) {
        $cuentas = new Cuenta_contable();
        $cuentas->
                where_in('ctipo_cuenta_contable_id', $tipos_cuenta)->
                where('empresa_id', $GLOBALS['empresa_id'])->
                where('estatus_general_id', 1);
        if ($cta > 0) {
            $cuentas->where('cta', $cta);
            if ($scta > 0) {
                $cuentas->where('scta', $scta);
                if ($sscta > 0)
                    $cuentas->where('sscta', $sscta);
            }
        }
        $cuentas->
                order_by('ctipo_cuenta_contable_id')->
                order_by('cta')->order_by('scta')->order_by('sscta')->order_by('ssscta')->get();
        return count($cuentas->all) > 0 ? $cuentas : false;
    }

    function get_cuenta_by_cta_scta_sscta($cta, $scta, $sscta) {
        $cuentas = new Cuenta_contable();
        $cuentas->where("cta", $cta)->where("scta", $scta)->where("sscta", $sscta)->
                where("estatus_general_id", 1)->get();
        return count($cuentas->all) > 0 ? $cuentas : false;
    }

    function get_cuentas_libro_mayor($q) {
        $cuentas = new Cuenta_contable();

        $sql = "SELECT cta, CONCAT(CAST(cta AS CHAR), ' - ', tag) as descr FROM ccuentas_contables WHERE scta = 0 AND ( CAST(cta AS CHAR) LIKE '%$q%' OR tag LIKE '%$q%' ) ORDER BY cta";
        //Buscar en la base de datos
        $cuentas->query($sql);
        if ($cuentas->c_rows > 0) {
            return $cuentas;
        } else {
            return FALSE;
        }
    }

    function get_scuentas_libro_mayor($q, $cta) {
        $cuentas = new Cuenta_contable();

        $sql = "SELECT scta, CONCAT(CAST(scta AS CHAR), ' - ', tag) as descr FROM ccuentas_contables WHERE (cta = $cta AND scta != 0 AND sscta = 0) AND ( CAST(scta AS CHAR) LIKE '%$q%' OR tag LIKE '%$q%' ) ORDER BY scta";
        //Buscar en la base de datos
        $cuentas->query($sql);
        if ($cuentas->c_rows > 0) {
            return $cuentas;
        } else {
            return FALSE;
        }
    }

    function get_sscuentas_libro_mayor($q, $cta, $scta) {
        $cuentas = new Cuenta_contable();

        $sql = "SELECT sscta, CONCAT(CAST(sscta AS CHAR), ' - ', tag) as descr FROM ccuentas_contables WHERE (cta = $cta AND scta = $scta AND sscta != 0) AND ( CAST(sscta AS CHAR) LIKE '%$q%' OR tag LIKE '%$q%' ) ORDER BY sscta";
        //Buscar en la base de datos
        $cuentas->query($sql);
        if ($cuentas->c_rows > 0) {
            return $cuentas;
        } else {
            return FALSE;
        }
    }

    function get_cuentas_pdf($cta, $scta, $sscta, $fecha_inicio, $fecha_final) {

        if ($sscta != 0) {
            $select = "ssscta";
            $group_order = " AND `ccuentas_contables`.`sscta` = $sscta
                            AND `ccuentas_contables`.`scta` = $scta
                            AND `ccuentas_contables`.`cta` = $cta
                            GROUP BY `ccuentas_contables`.`ssscta`
                            ORDER BY `ccuentas_contables`.`ssscta`";
        } else if ($scta != 0) {
            $select = "sscta";
            $group_order = " AND `ccuentas_contables`.`scta` = $scta
                            AND `ccuentas_contables`.`cta` = $cta
                            GROUP BY `ccuentas_contables`.`sscta`
                            ORDER BY `ccuentas_contables`.`sscta`";
        } else if ($cta != 0) {
            $select = "scta";
            $group_order = " AND `ccuentas_contables`.`cta` = $cta
                            GROUP BY `ccuentas_contables`.`scta`
                            ORDER BY `ccuentas_contables`.`scta`";
        } else {
            $select = "cta";
            $group_order = " GROUP BY
                            `ccuentas_contables`.`cta`
                            ORDER BY `ccuentas_contables`.`cta`";
        }

        $sql = "SELECT `ccuentas_contables`.`$select` AS cuenta_contable,
                        SUM(
                                if(`polizas`.`fecha` < '$fecha_inicio', 
                                        `poliza_detalles`.`debe`-`poliza_detalles`.`haber`, 

                                        0
                                )
                        ) AS saldo_i,

                        SUM(
                                if(`polizas`.`fecha` >= '$fecha_inicio', 
                                        `poliza_detalles`.`debe`, 

                                        0
                                )
                        ) AS debe_t, 

                        SUM(
                                if(`polizas`.`fecha` >= '$fecha_inicio', 
                                        `poliza_detalles`.`haber`, 

                                        0
                                )
                        ) AS haber_t
                FROM
                        (`poliza_detalles`)
                LEFT OUTER JOIN `ccuentas_contables` as ccuentas_contables ON
                        `ccuentas_contables`.`id` = `poliza_detalles`.`cuenta_contable_id`
                LEFT OUTER JOIN `polizas` as
                        polizas ON `polizas`.`id` = `poliza_detalles`.`poliza_id`
                WHERE `poliza_detalles`.`cuenta_contable_id` != 0
                AND `polizas`.`fecha` <= '$fecha_final'
                $group_order";
        $cuentas = new Poliza_detalle();
        $cuentas->query($sql);
        return ($cuentas->c_rows > 0) ? $cuentas : false;
    }

    function get_cuentas_tags_pdf($cta, $scta, $sscta, $todas) {
        $cuentas = new Cuenta_contable();
        $cuentas->select("tag")->select("cta")->select("scta")->select("sscta")->select("ssscta");
        if ($todas) {
            $cuentas->order_by("cta")->
                    order_by("scta")->
                    order_by("sscta")->
                    order_by("ssscta")->get();
        } else if ($sscta != 0) {
            $cuentas->select("ssscta AS num_c")->
                    where("cta", $cta)->
                    where("scta", $scta)->
                    where("sscta", $sscta)->
                    order_by("cta")->
                    order_by("scta")->
                    order_by("sscta")->
                    order_by("ssscta")->get();
        } else if ($scta != 0) {
            $cuentas->select("sscta AS num_c")->
                    where("cta", $cta)->
                    where("scta", $scta)->
                    where("ssscta =", 0)->
                    order_by("cta")->
                    order_by("scta")->
                    order_by("sscta")->
                    order_by("ssscta")->get();
        } else if ($cta != 0) {
            $cuentas->select("scta AS num_c")->
                    where("cta", $cta)->
                    where("sscta", 0)->
                    where("ssscta", 0)->
                    order_by("cta")->
                    order_by("scta")->
                    order_by("sscta")->
                    order_by("ssscta")->get();
        } else {
            $cuentas->select("cta AS num_c")->
                    where("scta", 0)->
                    where("sscta", 0)->
                    where("ssscta", 0)->
                    order_by("cta")->
                    order_by("scta")->
                    order_by("sscta")->
                    order_by("ssscta")->get();
        }
        return count($cuentas->all) > 0 ? $cuentas : false;
    }

    function get_estado_resultados($fecha_inicio, $fecha_fin, $tipos_cuentas, $show_cero) {

        $sql =
                "SELECT
	cc.cta,
	(
		SELECT
			cc1.tag
		FROM
			ccuentas_contables AS cc1
		WHERE
			cc1.cta = cc.cta
			AND cc1.scta = 0
			AND cc1.sscta = 0
			AND cc1.ssscta = 0
	) AS tag,
	SUM(
		IF(
			p.fecha <= '$fecha_fin',
				pd.debe-pd.haber,
				0
		)
	) AS acumulado,
	SUM(
		IF(
			p.fecha >= '$fecha_inicio' AND p.fecha <= '$fecha_fin',
				pd.debe-pd.haber,
				0
		)
	) AS mes,
	cc.ctipo_cuenta_contable_id
FROM
	" . ($show_cero ? 'ccuentas_contables AS cc' : 'poliza_detalles AS pd') . "
	LEFT JOIN " . (!$show_cero ? 'ccuentas_contables AS cc' : 'poliza_detalles AS pd') . "
		ON cc.id = pd.cuenta_contable_id
	LEFT JOIN polizas AS p
		ON pd.poliza_id = p.id
WHERE
	cc.ctipo_cuenta_contable_id IN(" . implode(',', $tipos_cuentas) . ")
GROUP BY
	cc.cta
ORDER BY
	ctipo_cuenta_contable_id, cc.cta;";
        $cuenta = new Cuenta_contable();
        $cuenta->query($sql);
        return $cuenta;
    }

    function get_saldo_inicial_libro_aux($cta_id, $fecha_inicio) {
        $sql = "SELECT 
                        sum(`poliza_detalles`.`debe` - `poliza_detalles`.`haber`) AS salndo_i
                FROM  `poliza_detalles`
                LEFT OUTER JOIN `polizas` as polizas ON `polizas`.`id` = `poliza_detalles`.`poliza_id`
                WHERE `poliza_detalles`.cuenta_contable_id = $cta_id
                AND `polizas`.`fecha` < '$fecha_inicio'";
        $cuentas = new Poliza_detalle();
        $cuentas->query($sql);
        return ($cuentas->c_rows > 0) ? $cuentas->salndo_i : false;
    }

    function get_detalles_libro_aux($cta, $scta, $sscta, $fecha_inicio, $fecha_final){
        $sub_where = "";
        if($sscta != 0){
            $sub_where .= "WHERE cta = ".$cta.
                            " AND scta = ".$scta.
                            " AND sscta = ".$sscta;
        } else if ($scta != 0) {
            $sub_where .= "WHERE cta = ".$cta.
                            " AND scta = ".$scta;
        } else if ($cta != 0) {
            $sub_where .= "WHERE cta = ".$cta;
        }
        
        
        $sql = "SELECT ctipos_polizas.tag AS tipo_serie, polizas.folio_poliza AS poliza, polizas.fecha AS fecha,
                        polizas.concepto, poliza_detalles.debe, poliza_detalles.haber, ccuentas_contables.id AS cuenta_id, 
                        ccuentas_contables.cta, ccuentas_contables.scta, ccuentas_contables.sscta, ccuentas_contables.tag, 
                        case polizas.ctipo_poliza_id
                            when 2 then CAST(polizas.numero_referencia AS CHAR)
                            when 1 then CAST(cobros.numero_referencia AS CHAR)
                            else '' 
                        end AS cheque,
                        if(ccuentas_contables.scta != 0,
                            (SELECT count.tag FROM ccuentas_contables AS count WHERE count.cta = ccuentas_contables.cta AND count.scta = 0 AND count.sscta = 0),
                            0
                        ) AS cuenta_padre,
                        if(ccuentas_contables.sscta != 0,
                            (SELECT count.tag FROM ccuentas_contables AS count WHERE count.cta = ccuentas_contables.cta AND count.scta = ccuentas_contables.scta AND count.sscta = 0),
                            0
                        ) AS sub_cuenta_padre
                FROM  `poliza_detalles`
                LEFT OUTER JOIN `polizas` as polizas ON polizas.id = poliza_detalles.poliza_id
                LEFT OUTER JOIN `ctipos_polizas` as ctipos_polizas ON ctipos_polizas.id = polizas.ctipo_poliza_id
                LEFT OUTER JOIN `cobros` as cobros ON cobros.poliza_detalle_id = poliza_detalles.id
		LEFT OUTER JOIN `ccuentas_contables` as ccuentas_contables ON ccuentas_contables.id = poliza_detalles.cuenta_contable_id
                WHERE poliza_detalles.cuenta_contable_id IN (SELECT id FROM ccuentas_contables $sub_where)
                AND polizas.fecha <= '$fecha_final'
                AND polizas.fecha >= '$fecha_inicio'
                ORDER BY ccuentas_contables.cta, ccuentas_contables.scta, ccuentas_contables.sscta, polizas.fecha, polizas.folio_poliza";
        $cuentas = new Poliza_detalle();
        $cuentas->query($sql);
        return ($cuentas->c_rows > 0) ? $cuentas : false;
    }
    
    function get_cuentas_balance_general($fecha_inicio, $fecha_final){
        $sql = "SELECT
                    (SUM(poliza_detalles.debe) - SUM(poliza_detalles.haber)) AS saldo, 
                    ccuentas_contables.cta
                FROM  
                    `poliza_detalles`
                LEFT OUTER JOIN
                    `polizas` as polizas ON polizas.id = poliza_detalles.poliza_id
                LEFT OUTER JOIN 
                    `ccuentas_contables` as ccuentas_contables ON ccuentas_contables.id = poliza_detalles.cuenta_contable_id
                WHERE 
                    ccuentas_contables.cta > 0
                AND
                    polizas.fecha <= '$fecha_final'
                AND 
                    polizas.fecha >= '$fecha_inicio'
                GROUP BY
                    ccuentas_contables.cta
                ORDER BY 
                    ccuentas_contables.cta";
        $cuentas = new Poliza_detalle();
        $cuentas->query($sql);
        return ($cuentas->c_rows > 0) ? $cuentas : false;
    }
    
    function get_cuentas_principales_orderby_tipocuenta(){
        $sql = "SELECT 
                        ccuentas_contables.cta,
                        ccuentas_contables.tag AS cuenta,
                        ctipo_cuentas_contables.tag AS tipo_cuenta,
                        ctipo_cuentas_contables.catalogo_anterior AS clave
                FROM 
                        `soleman`.`ccuentas_contables`
                LEFT OUTER JOIN 
                        `ctipo_cuentas_contables` as ctipo_cuentas_contables ON ccuentas_contables.ctipo_cuenta_contable_id = ctipo_cuentas_contables.id
                WHERE
                        scta = 0
                AND
                        ctipo_cuentas_contables.catalogo_anterior NOT LIKE '%D%'
                ORDER BY 
                        ctipo_cuentas_contables.catalogo_anterior,
                        ccuentas_contables.cta;";
        $cuentas = new Poliza_detalle();
        $cuentas->query($sql);
        return ($cuentas->c_rows > 0) ? $cuentas : false;
    }
     
}