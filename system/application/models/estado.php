<?php

/**
 * Estado Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Estado extends DataMapper {

	var $table= "cestados";

	function  Estado($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	function get_estado($id)
	{
		// Create a temporary user object
		$e = new Estado();

		//Buscar en la base de datos
		$e->get_by_id($id);
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * Estado
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_estados()
	{
		// Create a temporary user object
		$e = new Estado();
		$e->where('estatus_general_id', "1");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * Obtener Listado de Estados
	 *
	 * .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_estados_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Estado();
		$sql="select * from cestados order by id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_estados_pdf()
	{
		// Create a temporary user object
		$ef = new Empresa();
		$sql="select * from cestados order by tag";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}

}
