<?php

/**
 *
 * Ctipo_gasto Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @link
 */
class Ctipo_gasto extends DataMapper {

	var $table= "ctipo_gastos";

	function  Ctipo_gasto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Ctipo_gasto
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipo_gasto()
	{
		// Create a temporary user object
		$e = new Ctipo_gasto();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_ctipo_gasto_dropd(){
		$u = new Ctipo_gasto();
		$u->where('estatus_general_id',1);
		$u->order_by('tag')->get();
		if($u->c_rows > 0) {
			$select_m[0]='Elija';
			foreach($u->all as $row){
				$select_m[$row->id]=$row->tag;
			}
		} else
			$select_m[0]='Vací­o';
		return $select_m;
	}


}
