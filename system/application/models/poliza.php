<?php

/**
 * Tipo Poliza Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Poliza extends DataMapper {

	var $table= "polizas";

	function  Poliza($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Poliza
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_polizas()
	{
		// Create a temporary user object
		$e = new Poliza();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_poliza($id)
	{
		// Create a temporary user object
		$u = new Poliza();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function checar_poliza($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Poliza();
		//Buscar en la base de datos
		$u->where('estatus_general_id', '1');
		$u->where('ctipo_poliza_id', 1);
		$u->where('espacio_fisico_id', $ubicacion);
		$u->where('fecha', $fecha);
		$u->get();
		if($u->c_rows == 0){
			return false;
		} else if($u->c_rows == 1) {
			return $u->id;
		}
	}

	function get_polizas_ingreso($offset, $per_page, $empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=1 order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

 function get_bancos() {
        $bancos = $this->db->query(
                "SELECT
                    id,
                    tag,
                    CASE id
                        WHEN 17 THEN 1
                        WHEN 18 THEN 2
                        WHEN 19 THEN 3
                        WHEN 20 THEN 4
                        ELSE 0 END
                    AS banco
                FROM
                    ccuentas_contables
                WHERE cta = 102 AND tag != 'BANCOS'");
        return $bancos->result();
    }


function get_polizas_ingreso_count($empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select count(pi.id) as total from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=1  group by pi.fecha order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_poliza_ingreso_pdf($id)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio, e.razon_social as empresa, pag.cl_factura_id as factura, pag.id as cobro from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id left join empresas as e on e.id=ef.empresas_id left join cobros as pag on pag.poliza_id=pi.id  where pi.estatus_general_id=1 and pi.id='$id' limit 1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_polizas_egreso($offset, $per_page, $empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=2 order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

function get_polizas_egreso_count($empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select count(pi.id) as total from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=2 GROUP BY pi.fecha order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}


	function get_poliza_egreso_pdf($id)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio, e.razon_social as empresa, pag.pr_factura_id as factura, pag.id as pago from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id left join empresas as e on e.id=ef.empresas_id left join pagos as pag on pag.poliza_id=pi.id where pi.estatus_general_id=1 and pi.id='$id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_poliza_by_fecha_ubicacion($fecha, $ubicacion){
		$u = new Poliza();
		$u->where('fecha', $fecha);
		$u->where('espacio_fisico_id', $ubicacion);
		$u->where('ctipo_poliza_id', 1);
		$u->where('estatus_general_id', 1);
		$u->get();
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_poliza_diario_by_fecha_ubicacion($fecha, $ubicacion){
		$u = new Poliza();
		$u->where('fecha', $fecha);
		$u->where('espacio_fisico_id', $ubicacion);
		$u->where('ctipo_poliza_id', 3);
		$u->where('estatus_general_id', 1);
		$u->get();
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_poliza_diario_by_fecha($fecha){
		$u = new Poliza();
		$u->where('fecha', $fecha);
		$u->where('ctipo_poliza_id', 3);
		$u->where('estatus_general_id', 1);
		$u->get();
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_polizas_diario($offset, $per_page, $empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=3 order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

function get_polizas_diario_count($empresa)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select count(pi.id) as total from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=3 group by pi.fecha order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_poliza_diario_pdf($id)
	{
		// Create a temporary user object
		$u = new Poliza();
		$sql="select pi.*, ef.tag as espacio, e.razon_social as empresa from polizas as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id left join empresas as e on e.id=pi.empresa_id where pi.estatus_general_id=1 and pi.id='$id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function consecutivo_cuatro_d(){
		//Funcion para formar el consecutivo de polizas de ingreso y diarias
		$anio=date('y');
		$mes=date('m');
		$p= new Poliza();
		$p->where("anio", $anio);
		$p->where("mes", $mes);
		$p->order_by('id desc');
		$p->limit(1)->get();
		if($p->c_rows == 1){
			$con_s=intval($p->consecutivo)+1;
			if(strlen($con_s)==1){
				$con_s="000".$con_s;
			} else if(strlen($con_s)==2){
				$con_s="00".$con_s;
			} else if(strlen($con_s)==3){
				$con_s="0".$con_s;
			}
			$x['mes']=$mes;
			$x['anio']=$anio;
			$x['consecutivo']=$con;
			return $u;
		} else {
			$x['mes']=$mes;
			$x['anio']=$anio;
			$x['consecutivo']="0001";
		}
	}
	function consecutivo_tres_d(){
		//Funcion para formar el consecutivo de polizas de ingreso y diarias
		$anio=date('y');
		$mes=date('m');
		$p= new Poliza();
		$p->where("anio", $anio);
		$p->where("mes", $mes);
		$p->order_by('id desc');
		$p->limit(1)->get();
		if($p->c_rows == 1){
			$con_s=intval($p->consecutivo)+1;
			if(strlen($con_s)==1){
				$con_s="00".$con_s;
			} else if(strlen($con_s)==2){
				$con_s="0".$con_s;
			}
			$x['mes']=$mes;
			$x['anio']=$anio;
			$x['consecutivo']=$con;
			return $u;
		} else {
			$x['mes']=$mes;
			$x['anio']=$anio;
			$x['consecutivo']="0001";
		}

	}

	function get_poliza_egresos_proveedor_fecha($fecha, $ubicacion, $empresa){
		$u = new Poliza();
		$u->where('fecha', $fecha);
		$u->where('espacio_fisico_id', $ubicacion);
		$u->where('empresa_id', $empresa);
		$u->where('ctipo_poliza_id', 2);
		$u->where('estatus_general_id', 1);
		$u->get();
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}
}
