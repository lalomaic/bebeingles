<?php

/**
 *
 * Gasto_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Gasto_detalle extends DataMapper {

	var $table= "gastos_detalles";

	function  Gasto_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Gasto_detalle
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_gasto_detalle()
	{
		// Create a temporary user object
		$e = new Gasto_detalle();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_gasto_detalle_dropd(){
		$u = new Gasto_detalle();
		$u->where('estatus_general_id',1);
		$u->order_by('descr')->get();
		if($u->result_count() > 0) {
			$select_m[0]='Elija';
			foreach($u->all as $row){
				$select_m[$row->id]=$row->tag;
			}
		} else
			$select_m[0]='Vacío';
		return $select_m;
	}

	function get_gastos_detalles($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Gasto_detalle();
		$sql="select g.*, cg.tag as concepto_gasto, t.tag as tipo, e.tag as espacio, u.username as usuario from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id left join ctipo_gastos as t on t.id=g.ctipo_gasto_id  left join espacios_fisicos as e on e.id=g.espacios_fisicos_id left join usuarios as u on u.id=g.usuario_id where g.estatus_general_id='1' order by g.fecha desc, cg.tag limit $per_page offset $offset";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_gastos_detalles_count()
	{
		// Create a temporary user object
		$u = new Gasto_detalle();
		$sql="select count(g.id) as total from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id where g.estatus_general_id='1'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_pago($id)
	{
		// Create a temporary user object
		$u = new Gasto_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_gasto_detalle_by_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Gasto_detalle();
		$e->where("pr_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_gasto_detalle_total_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Gasto_detalle();
		$e->select("sum(monto_pagado) as total");
		$e->where("pr_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows==1){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_gasto_detalle_pdf($where,$order_by)
	{
		//Objeto de la clase
		$obj = new Gasto_detalle();
		$sql = "SELECT gasto_detalle.id, pr_facturas.folio_factura AS folio, cproveedores.razon_social AS proveedor, usuarios.username AS capturista, gasto_detalle.fecha, cpr_formas_pago.tag AS forma_pago, ccuentas_bancarias.numero_cuenta, ccuentas_bancarias.banco, gasto_detalle.monto_pagado AS importe, ctipos_gasto_detalle.tag AS tipo_pago FROM gasto_detalle LEFT JOIN pr_facturas ON pr_facturas.id = gasto_detalle.pr_factura_id LEFT JOIN cproveedores 	ON cproveedores.id = pr_facturas.cproveedores_id LEFT JOIN usuarios ON usuarios.id = gasto_detalle.usuario_id LEFT JOIN cpr_formas_pago ON cpr_formas_pago.id = gasto_detalle.cpr_forma_pago_id LEFT JOIN ccuentas_bancarias ON ccuentas_bancarias.id = gasto_detalle.cuenta_origen_id LEFT JOIN ctipos_gasto_detalle ON ctipos_gasto_detalle.id = gasto_detalle.ctipo_pago_id $where $order_by";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

function get_gastos_detalles_filtrado($espacio, $fecha1a='01 01 2010', $fecha2a,$concepto_id,$tipo_gasto_id)
	{
		//Fecha
		if($fecha1a==""){
			$hoy="2010-01-01";
			$fecha1=date("Y-m-d", strtotime($hoy));
		} else if(strlen($fecha1a)>0) {
			$fecha=explode(" ", $fecha1a);
			$fecha1b=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha1=date("Y-m-d", strtotime($fecha1b));
		}

		if($fecha2a==""){
			$fecha2=date("Y-m-d");
		} else if(strlen($fecha2a)>0) {
			$fecha=explode(" ", $fecha2a);
			$fecha2=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		}
		if($espacio>0)
			$espacio_str=" and g.espacios_fisicos_id=$espacio";
		else
			$espacio_str='';
		if($concepto_id>0)
			$concepto_str=" and g.cgastos_id=$concepto_id";
		else
			$concepto_str='';
		if($tipo_gasto_id>0)
			$ctipo_gasto_str=" and g.ccuenta_bancaria_id=$tipo_gasto_id";
		else
			$ctipo_gasto_str='';
		// Create a temporary user object
		$u = new Gasto_detalle();
		$sql="select g.*, cg.tag as concepto_gasto, t.tag as tipo, e.tag as espacio, u.username as usuario from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id left join ctipo_gastos as t on t.id=g.ctipo_gasto_id left join espacios_fisicos as e on e.id=g.espacios_fisicos_id left join usuarios as u on u.id=g.usuario_id where g.estatus_general_id='1' and fecha>='$fecha1' and fecha<='$fecha2'  $espacio_str $concepto_str $ctipo_gasto_str order by g.fecha asc, e.tag, cg.tag ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


}

