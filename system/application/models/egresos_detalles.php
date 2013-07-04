<?php

/**
 *
 * Egresos_detalles Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Egresos_detalles extends DataMapper {

	var $table= "otros_egresos_detalles";

	function  Egresos_detalles($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Egresos_detalles
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_egresos_detalle()
	{
		// Create a temporary user object
		$e = new Egresos_detalles();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_egresos_detalles_dropd(){
		$u = new Egresos_detalles();
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

	function get_egresos_detalles($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Egresos_detalles();
		$sql="select g.*, cg.tag as concepto_gasto, e.tag as espacio, u.username as usuario from otros_egresos_detalles as g left join cotros_egresos as cg on cg.id=g.cotros_egresos_id left join espacios_fisicos as e on e.id=g.espacios_fisicos_id left join usuarios as u on u.id=g.usuario_id where g.estatus_general_id='1' order by g.fecha desc, cg.tag limit $per_page offset $offset";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_egresos_detalles_count()
	{
		// Create a temporary user object
		$u = new Egresos_detalles();
		$sql="select count(g.id) as total from otros_egresos_detalles as g left join cotros_egresos as cg on cg.id=g.cotros_egresos_id where g.estatus_general_id='1'";
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
		$u = new Egresos_detalles();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_egresos_detalles_by_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Egresos_detalles();
		$e->where("pr_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_egresos_detalles_total_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Egresos_detalles();
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
	function get_egresos_detalles_pdf($where,$order_by)
	{
		//Objeto de la clase
		$obj = new Egresos_detalles();
		$sql = "SELECT egresos_detalles.id, pr_facturas.folio_factura AS folio, cproveedores.razon_social AS proveedor, usuarios.username AS capturista, egresos_detalles.fecha, cpr_formas_pago.tag AS forma_pago, ccuentas_bancarias.numero_cuenta, ccuentas_bancarias.banco, egresos_detalles.monto_pagado AS importe, ctipos_egresos_detalles.tag AS tipo_pago FROM egresos_detalles LEFT JOIN pr_facturas ON pr_facturas.id = egresos_detalles.pr_factura_id LEFT JOIN cproveedores 	ON cproveedores.id = pr_facturas.cproveedores_id LEFT JOIN usuarios ON usuarios.id = egresos_detalles.usuario_id LEFT JOIN cpr_formas_pago ON cpr_formas_pago.id = egresos_detalles.cpr_forma_pago_id LEFT JOIN ccuentas_bancarias ON ccuentas_bancarias.id = egresos_detalles.cuenta_origen_id LEFT JOIN ctipos_egresos_detalles ON ctipos_egresos_detalles.id = egresos_detalles.ctipo_pago_id $where $order_by";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

	function get_egresos_detalles_filtrado($espacio, $fecha1a, $fecha2a)
	{
		//Fecha
		if($fecha1a=="" and strlen($fecha2a)>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2a=="" and strlen($fecha1a)>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		if($espacio>0)
			$espacio_str=" and g.espacios_fisicos_id=$espacio";
		else
			$espacio_str='';
		// Create a temporary user object
		$u = new Egresos_detalles();
		$sql="select g.*, cg.tag as concepto_gasto, e.tag as espacio, u.username as usuario from otros_egresos_detalles as g left join cotros_egresos as cg on cg.id=g.cotros_egresos_id left join espacios_fisicos as e on e.id=g.espacios_fisicos_id left join usuarios as u on u.id=g.usuario_id where g.estatus_general_id='1' and fecha>='$fecha1' and fecha<'$fecha2' $espacio_str order by g.fecha asc, e.tag, cg.tag ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


}
