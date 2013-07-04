<?php

/**
 * Tipo Espacio Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Subtipo_espacio extends DataMapper {

	var $table= "subtipos_espacios";


	function  Subtipo_espacio($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Subtipo_espacio
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_subtipos_espacios()
	{
		// Create a temporary user object
		$e = new Subtipo_espacio();
		//Buscar en la base de datos
		$e->where('estatus_general_id', 1)->order_by('id asc');
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

}
