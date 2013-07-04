<?php

/**
 * Precio_sucursal Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Precio_sucursal extends DataMapper {

	var $table= "precios_sucursales";


	function  Precio_sucursal($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Precio_sucursal
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_precio_sucursal()
	{
		// Create a temporary user object
		$e = new Precio_sucursal();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}




}
