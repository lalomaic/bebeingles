<?php

/**
 * Cobros Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Cobro extends DataMapper {

	var $table= "cobros";

	function  Cobro($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cobros
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cobros()
	{
		// Create a temporary user object
		$e = new Cobro();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cobros_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cobro();
		//	  $sql="select c.id as cobro_id, c.*, f.folio_factura as factura, fc.tag as forma_cobro, cb.numero_cuenta as numero_cuenta, tc.tag as tipo_cobro from cobros as c left join cl_facturas as f on f.id=c.cl_factura_id left join ccl_formas_cobro as fc on fc.id=c.ccl_forma_cobro_id left join ccuentas_bancarias as cb on cb.id=c.cuenta_destino_id left join ctipos_cobros as tc on tc.id=c.ctipo_cobro_id order by factura limit $offset, $per_page";
		$sql="select distinct(p.cl_factura_id), f.fecha, f.folio_factura as factura, f.monto_total, sum(p.monto_pagado) as monto_pagado, cp.razon_social as cliente,  ef.tag as estatus from cobros as p left join cl_facturas as f on f.id=p.cl_factura_id left join cclientes as cp on cp.id=f.cclientes_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id group by p.cl_factura_id order by p.id desc  limit $offset, $per_page ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cobro($id)
	{
		// Create a temporary user object
		$u = new Cobro();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1 ){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cobros_pdf($where, $order_by)

	{

		// Create a temporary user object
		$u = new Cobro();
		$sql="select c.*, f.folio_factura as factura, u.nombre as usuario, cb.numero_cuenta, cb.banco, tc.tag as tipo_cobro, fc.tag as forma_cobro from cobros as c left join cl_facturas as f on f.id=c.cl_facturas_id left join usuarios as u on u.id=c.usuario_id left join ccuentas_bancarias as cb on cb.id=c.cuenta_origen_id left join ctipos_cobros as tc on tc.id=c.ctipo_cobro_id left join ccl_formas_cobro as fc on fc.id=c.ccl_forma_cobro_id $where $order_by ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cobros_by_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Cobro();
		$e->where("cl_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cobro_total_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Cobro();
		$e->select("sum(monto_pagado) as total");
		$e->where("cl_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows==1){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cobros_credito_by_fecha($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select d.*, c.banco, c.numero_cuenta, c.cuenta_contable_id, u.username as usuario from cobros as d left join ccuentas_bancarias as c on c.id=d.cuenta_destino_id left join usuarios as u on u.id=d.usuario_id left join cl_facturas as cl on cl.id=d.cl_factura_id where d.estatus_general_id=1 and cl.espacios_fisicos_id=$ubicacion and d.fecha='$fecha' and cl.estatus_factura_id='2' order by d.id asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cobros_contado_by_fecha($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select d.*, c.banco, c.numero_cuenta, c.cuenta_contable_id, u.username as usuario from cobros as d left join ccuentas_bancarias as c on c.id=d.cuenta_destino_id left join usuarios as u on u.id=d.usuario_id left join cl_facturas as cl on cl.id=d.cl_factura_id where d.estatus_general_id=1 and cl.espacios_fisicos_id=$ubicacion and d.fecha='$fecha' and cl.estatus_factura_id='1' order by d.id asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
