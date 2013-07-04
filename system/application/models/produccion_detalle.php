<?php

/**
 * Produccion_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Produccion_detalle extends DataMapper {

	var $table= "produccion_detalles";



	function  Produccion_detallees($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Produccion_detalle
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_produccion_detalles()
	{
		// Create a temporary user object
		$e = new Produccion_detalle();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->order_by('id');
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Produccion_detalle
	 *
	 * Obtiene los datos de Producción a partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_produccion_detalle($id)
	{
		// Create a temporary user object
		$u = new Produccion_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_produccion_detalles_by_parent($id)
	{
		// Obtiene los detalles de la transformacion en base a produccion_id
		$u = new Produccion_detalle();
		//Buscar en la base de datos
		$u->where('produccion_id', $id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
