<?php

/**
 * Tipo Entrada Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_entrada extends DataMapper {

	var $table= "ctipos_entradas";



	function  Tipo_entrada($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Entrada
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_entradas()
	{
		// Create a temporary user object
		$e = new Tipo_entrada();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_entradas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Tipo_entrada();
		$sql="select * from ctipos_entradas order by tag limit $offset offset $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entrada($id)
	{
		// Create a temporary user object
		$u = new Tipo_entrada();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_tipos_entrada_dropd() {
		$tipos = new Tipo_entrada();
		$tipos->
		where('estatus_general_id', 1)->order_by('tag')->get();
		$tipos_array = array();
		foreach($tipos as $tipo)
			$tipos_array[$tipo->id] = $tipo->tag;
		return $tipos_array;
	}

	function get_tipos_entrada_as_array() {
		$tipos = new Tipo_entrada();
		$tipos->get();
		$tipos_array = array();
		foreach($tipos as $tipo)
			$tipos_array[$tipo->id] = $tipo->tag;
		return $tipos_array;
	}

}
