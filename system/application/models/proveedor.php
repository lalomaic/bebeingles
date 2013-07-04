<?php

/**
 * Proveedor Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Proveedor extends DataMapper {

	var $table= "cproveedores";

    // Relacion con devoluciones para consultar datos del devoluciones proveedor
    var $has_many = array('devuluciones_proveedor');



	function  Proveedor($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Proveedores
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_proveedores()
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->where('estatus_general_id', "1");
		$e->order_by("razon_social asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	
	function get_proveedores_id($id)
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->where('id', $id);
		$e->order_by("razon_social asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	//Proveedores Habilitados
	function get_proveedores_hab()
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_proveedores_hab_count()
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->select("count(id) as total");
		$e->where('estatus_general_id', "1");
		//Buscar en la base de datos
		$e->get();
		return $e->total;
	}

	function get_proveedores_tienda()
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->where('estatus_general_id', "1");
		$e->where('filtro_sucursal', "1");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_proveedores_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Proveedor();
		$sql="select p.*, e.tag as estatus, u.username as usuario from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join usuarios as u on u.id=p.usuario_id where p.estatus_general_id='1' order by p.razon_social  limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_proveedor($id)
	{
		// Create a temporary user object
		$u = new Proveedor();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_proveedores_pdf()
	{
		// Create a temporary user object
		$u = new Proveedor();
		$sql="select p.*, e.tag as estatus from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id order by p.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_proveedores_marca()
	{
		// Create a temporary user object
		$e = new Proveedor();
		$e->where('estatus_general_id', "1")->where('id >', 1);
		$e->order_by("razon_social asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

}
