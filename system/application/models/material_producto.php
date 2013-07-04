<?php

/**
 * Materiales de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Material_producto extends DataMapper {

	var $table= "cproductos_material";



	function  Material_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Material de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cproductos_materiales()
	{
		// Create a temporary user object
		$e = new Material_producto();
		$e->where('estatus_general_id', "1");
		$e->order_by("tag asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_materiales_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Material_producto();
		$sql="select sfp.*, eg.tag as estatus from cproductos_material as sfp left join estatus_general as eg on eg.id=sfp.estatus_general_id order by sfp.tag, sfp.tag limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_material($id)
	{
		// Create a temporary user object
		$u = new Material_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
