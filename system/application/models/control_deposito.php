<?php

/**
 * Tipo Control_deposito Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Control_deposito extends DataMapper {

	var $table= "control_depositos";

	function  Control_deposito($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Control_deposito
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_control_depositos()
	{
		// Create a temporary user object
		$e = new Control_deposito();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_control_deposito($id)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function checar_control_deposito($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		//Buscar en la base de datos
		$u->select("sum(cantidad) as total");
		$u->where('estatus_general_id', '1');
		$u->where('espacios_fisicos_id', $ubicacion);
		$u->where('fecha_venta', $fecha);
		$u->get();
		if($u->total > 0){
			return $u->total;
		} else {
			return 0;
		}
	}

	function get_depositos_tienda_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select count(d.id) as total from control_depositos as d where d.estatus_general_id=1 and d.espacios_fisicos_id=$ubicacion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_depositos_tienda($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select d.*, c.banco, c.numero_cuenta, u.username as usuario from control_depositos as d left join ccuentas_bancarias as c on c.id=d.cuenta_bancaria_id left join usuarios as u on u.id=d.usuario_id where d.estatus_general_id=1 and d.espacios_fisicos_id=$ubicacion order by d.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_depositos_general_count()
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select count(d.id) as total from control_depositos as d where d.estatus_general_id=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_depositos_general($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select d.*, c.banco, c.numero_cuenta, ef.tag as espacio, u.username as usuario from control_depositos as d left join ccuentas_bancarias as c on c.id=d.cuenta_bancaria_id left join espacios_fisicos as ef on ef.id=d.espacios_fisicos_id left join usuarios as u on u.id=d.usuario_id where d.estatus_general_id=1 order by d.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_depositos_by_fecha($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Control_deposito();
		$sql="select d.*, c.banco, c.numero_cuenta, c.cuenta_contable_id, u.username as usuario from control_depositos as d left join ccuentas_bancarias as c on c.id=d.cuenta_bancaria_id left join usuarios as u on u.id=d.usuario_id where d.estatus_general_id=1 and d.espacios_fisicos_id=$ubicacion and fecha_venta='$fecha' and d.estatus_general_id='1' order by d.id asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
