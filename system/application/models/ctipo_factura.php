<?php

/**
 * Ctipo_factura Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Ctipo_factura extends DataMapper {
	var $table= "ctipo_facturas";


	function  Ctipo_factura($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Ctipo_facturas
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_factura()
	{
		// Create a temporary user object
		$e = new Ctipo_factura();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}


	function get_ctipo_factura($id)

	{
		// Create a temporary user object
		$u = new Ctipo_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
