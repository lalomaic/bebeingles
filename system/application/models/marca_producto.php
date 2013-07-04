<?php

/**
 * Marca de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Marca_producto extends DataMapper {

	var $table= "cmarcas_productos";



	function  Marca_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Marca de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cmarcas_productos()
	{
		// Create a temporary user object
		$e = new Marca_producto();
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

	function get_marca_productos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Marca_producto();
		$sql="select mp.*, eg.tag as estatus from cmarcas_productos as mp left join estatus_general as eg on eg.id=mp.estatus_general_id order by mp.tag asc limit $per_page offset $offset ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_marca($id)
	{
		// Create a temporary user object
		$u = new Marca_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_marcas_dropd() {
		$marcas = new Marca_producto();
		$marcas->
		where('estatus_general_id', 1)->order_by('tag')->get();
		$marcas_array = array();
		foreach($marcas as $marca)
			$marcas_array[$marca->id] = $marca->tag;
		return $marcas_array;
	}

	function get_marcas_as_array() {
		$marcas = new Marca_producto();
		$marcas->
		order_by('tag')->get();
		$marcas_array = array();
		foreach($marcas as $marca)
			$marcas_array[$marca->id] = $marca->tag;
		return $marcas_array;
	}
}
