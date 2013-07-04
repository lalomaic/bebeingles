<?php

/**
 *
 * Otros_egresos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Otros_egresos extends DataMapper {

	var $table= "cotros_egresos";

	function  Otros_egresos($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Otros_egresos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_otros_egresos()
	{
		// Create a temporary user object
		$e = new Otros_egresos();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_otros_egresos_dropd(){
		$u = new Otros_egresos();
		$u->where('estatus_general_id',1);
		$u->order_by('tag')->get();
		if($u->c_rows > 0) {
			$select_m[0]='Elija';
			foreach($u->all as $row){
				$select_m[$row->id]=$row->tag;
			}
		} else
			$select_m[0]='Vacío';
		return $select_m;
	}

	function get_otros_egresos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Otros_egresos();
		$sql="select g.*, e.tag as estatus from cotros_egresos as g left join estatus_general as e on e.id=g.estatus_general_id where g.estatus_general_id=1 order by g.id limit $per_page offset $offset";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_otros_egresos_list_count()
	{
		// Create a temporary user object
		$u = new Otros_egresos();
		$sql="select g.id from cotros_egresos as g left join estatus_general as e on e.id=g.estatus_general_id where g.estatus_general_id=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->c_rows;
		} else {
			return FALSE;
		}
	}

	function get_pago($id)
	{
		// Create a temporary user object
		$u = new Otros_egresos();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_otros_egresos_by_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Otros_egresos();
		$e->where("pr_factura_id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_otros_egresos_total_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Otros_egresos();
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
	function get_otros_egresos_pdf($where,$order_by)
	{
		//Objeto de la clase
		$obj = new Otros_egresos();
		$sql = "SELECT cotros_egresos.id, pr_facturas.folio_factura AS folio, cproveedores.razon_social AS proveedor, usuarios.username AS capturista, otros_egresos.fecha, cpr_formas_pago.tag AS forma_pago, ccuentas_bancarias.numero_cuenta, ccuentas_bancarias.banco, otros_egresos.monto_pagado AS importe, ctipos_otros_egresos.tag AS tipo_pago FROM otros_egresos LEFT JOIN pr_facturas ON pr_facturas.id = otros_egresos.pr_factura_id LEFT JOIN cproveedores 	ON cproveedores.id = pr_facturas.cproveedores_id LEFT JOIN usuarios ON usuarios.id = otros_egresos.usuario_id LEFT JOIN cpr_formas_pago ON cpr_formas_pago.id = otros_egresos.cpr_forma_pago_id LEFT JOIN ccuentas_bancarias ON ccuentas_bancarias.id = otros_egresos.cuenta_origen_id LEFT JOIN ctipos_otros_egresos ON ctipos_otros_egresos.id = otros_egresos.ctipo_pago_id $where $order_by";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}


}
