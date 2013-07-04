<?php

/**
 * Pago Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pago extends DataMapper {

	var $table= "pagos";

	function  Pago($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pagos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pagos()
	{
		// Create a temporary user object
		$e = new Pago();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_pagos_list( $offset, $per_page, $where, $periodo)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(p.pr_factura_id) as pr_factura_id, f.fecha, p.fecha as fecha_pago, f.folio_factura as factura, f.monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  ef.tag as estatus, mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id $where $periodo group by p.pr_factura_id, f.fecha, f.folio_factura, f.monto_total, cp.razon_social, ef.tag, mp.tag, p.fecha order by p.fecha desc, proveedor asc limit $per_page offset $offset ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_proveedores_list($id)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(p.pr_factura_id) as pr_factura_id, f.fecha, p.fecha as fecha_pago,  f.folio_factura as factura, f.monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  ef.tag as estatus, mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.cproveedores_id=$id group by p.pr_factura_id, f.fecha, f.folio_factura, f.monto_total, cp.razon_social, ef.tag, mp.tag, p.fecha order by p.fecha desc, proveedor asc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_marcas_list($id)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(p.pr_factura_id) as pr_factura_id, f.fecha, p.fecha as fecha_pago,  f.folio_factura as factura, f.monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  ef.tag as estatus, mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.cmarca_id=$id group by p.pr_factura_id, f.fecha, f.folio_factura, f.monto_total, cp.razon_social, ef.tag, mp.tag, p.fecha order by p.fecha desc, proveedor asc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}



	function get_pagos_filtrado_fecha_list($fecha1a, $fecha2a, $cproveedor)
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
		if($cproveedor>0)
			$espacio_str=" and prf.espacios_fisicos_id=$cproveedor";
		else
			$espacio_str='';
		$u = new Pago();
		$sql="select distinct(p.pr_factura_id) as pr_factura_id, f.fecha, p.fecha as fecha_pago, f.folio_factura as factura, f.monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  ef.tag as estatus, mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.fecha >='$fecha1' and f.fecha<'$fecha2'  $espacio_str group by p.pr_factura_id, f.fecha, f.folio_factura, f.monto_total, cp.razon_social, ef.tag, mp.tag, p.fecha order by p.fecha desc, proveedor asc";
		 
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_pagos_list_count($where, $periodo)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select count(distinct(p.pr_factura_id)) as total from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id $where $periodo ";
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
		$u = new Pago();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

 function get_suma_pago($id)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select sum(monto_pagado)
from pagos
where
pr_factura_id=";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_by_factura_id($factura_id)
	{
		// Create a temporary user object
		$obj = new Pago();
		$sql = "select p.id, p.fecha,p.numero_referencia,p.monto_pagado,cb.banco,cb.numero_cuenta
from pagos as p
left join ccuentas_bancarias as cb on cb.id=p.cuenta_origen_id
where
pr_factura_id=$factura_id
order by p.id";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}
	function get_pagos_total_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Pago();
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
	function get_pagos_pdf($where,$order_by)
	{
		//Objeto de la clase
		$obj = new Pago();
		$sql = "SELECT pagos.id, pr_facturas.folio_factura AS folio, cproveedores.razon_social AS proveedor, usuarios.username AS capturista, pagos.fecha, cpr_formas_pago.tag AS forma_pago, ccuentas_bancarias.numero_cuenta, ccuentas_bancarias.banco, pagos.monto_pagado AS importe, ctipos_pagos.tag AS tipo_pago FROM pagos LEFT JOIN pr_facturas ON pr_facturas.id = pagos.pr_factura_id LEFT JOIN cproveedores 	ON cproveedores.id = pr_facturas.cproveedores_id LEFT JOIN usuarios ON usuarios.id = pagos.usuario_id LEFT JOIN cpr_formas_pago ON cpr_formas_pago.id = pagos.cpr_forma_pago_id LEFT JOIN ccuentas_bancarias ON ccuentas_bancarias.id = pagos.cuenta_origen_id LEFT JOIN ctipos_pagos ON ctipos_pagos.id = pagos.ctipo_pago_id $where $order_by";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

	function get_editar_pagos_pr_factura($id)
	{
		//Objeto de la clase
		$obj = new Pago();
		$sql = "SELECT pagos.id, pr_facturas.folio_factura AS folio, pagos.numero_referencia, usuarios.username AS capturista, pagos.fecha, cpr_formas_pago.tag AS forma_pago, ccuentas_bancarias.numero_cuenta, ccuentas_bancarias.banco, pagos.monto_pagado AS importe, ctipos_pagos.tag AS tipo_pago FROM pagos LEFT JOIN pr_facturas ON pr_facturas.id = pagos.pr_factura_id LEFT JOIN cproveedores 	ON cproveedores.id = pr_facturas.cproveedores_id LEFT JOIN usuarios ON usuarios.id = pagos.usuario_id LEFT JOIN cpr_formas_pago ON cpr_formas_pago.id = pagos.cpr_forma_pago_id LEFT JOIN ccuentas_bancarias ON ccuentas_bancarias.id = pagos.cuenta_origen_id LEFT JOIN ctipos_pagos ON ctipos_pagos.id = pagos.ctipo_pago_id where pr_facturas.id=$id order by pagos.fecha asc";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

	// 	Seccion de funciones para pagos multiples
	function get_pagos_multiples_list( $offset, $per_page, $where, $periodo) {
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(cp.razon_social, p.fecha),  cp.id as proveedor_id, p.fecha as fecha_pago, sum(f.monto_total) as monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id $where $periodo group by cp.razon_social, cp.id, p.fecha, mp.tag  order by p.fecha desc, proveedor asc limit $per_page offset $offset ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_multiples_proveedores_list($id) {
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(cp.razon_social, p.fecha),  cp.id as proveedor_id, p.fecha as fecha_pago, sum(f.monto_total) as monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.cproveedores_id=$id group by cp.razon_social, cp.id, p.fecha, mp.tag  order by p.fecha desc, proveedor asc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_multiples_marcas_list($id) {
		// Create a temporary user object
		$u = new Pago();
		$sql="select distinct(cp.razon_social, p.fecha),  cp.id as proveedor_id, p.fecha as fecha_pago, sum(f.monto_total) as monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.cmarca_id=$id group by cp.razon_social, cp.id, p.fecha, mp.tag  order by p.fecha desc, proveedor asc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function  get_pagos_multiples_filtrado_fecha_list($fecha1a, $fecha2a, $cproveedor) {
		// Create a temporary user object
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
		if($cproveedor>0)
			$espacio_str=" and prf.espacios_fisicos_id=$cproveedor";
		else
			$espacio_str='';
		$u = new Pago();
		$sql="select distinct(cp.razon_social, p.fecha),  cp.id as proveedor_id, p.fecha as fecha_pago, sum(f.monto_total) as monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as proveedor,  mp.tag as marca from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id left join cmarcas_productos as mp on mp.id=f.cmarca_id where f.fecha >='$fecha1' and f.fecha<'$fecha2'  $espacio_str group by cp.razon_social, cp.id, p.fecha, mp.tag  order by p.fecha desc, proveedor asc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pagos_borrado($proveedor_id, $fecha)
	{
		// Create a temporary user object
		$u = new Pago();
		$sql="select  p.id, p.pr_factura_id, p.salidas_str from pagos as p left join pr_facturas as f on f.id=p.pr_factura_id left join cproveedores as cp on cp.id=f.cproveedores_id  where f.cproveedores_id=$proveedor_id and p.fecha='$fecha' ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
