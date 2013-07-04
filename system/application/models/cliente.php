<?php

/**
 * Cliente Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Cliente extends DataMapper {

	var $table= "cclientes";



	function  Cliente($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cliente
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_clientes()
	{
		// Create a temporary user object
		$e = new Cliente();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->order_by('razon_social', 'asc');
		$e->where('estatus_general_id', "1");
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Cliente
	 *
	 * Obtiene los datos de un cliente partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_cliente($id)
	{
		// Create a temporary user object
		$u = new Cliente();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_clientes_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cliente();
		$sql="select p.*, e.tag as estatus from cclientes as p left join estatus_general as e on e.id=p.estatus_general_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_clientes_pdf()
	{
		// Create a temporary user object
		$u = new Cliente();
		$sql="select c.*, e.tag as estatus from cclientes as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
