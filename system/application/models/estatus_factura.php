<?php

/**
 * Estatus_factura Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Estatus_factura extends DataMapper {
	var $table= "estatus_facturas";


	function  Estatus_factura($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Estatus_facturas
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_estatus_facturas()
	{
		// Create a temporary user object
		$e = new Estatus_factura();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}


	function get_estatus_factura($id)

	{
		// Create a temporary user object
		$u = new Estatus_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
