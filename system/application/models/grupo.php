<?php

/**
 * Grupo Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Grupo extends DataMapper {

	var $table= "grupos";



	function  Grupo($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
	function get_grupo($id)
	{
		// Create a temporary user object
		$e = new Grupo();
		//Buscar en la base de datos
		$e->get_by_id($id);
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	/**
	 * Grupo
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_grupos()
	{
		// Create a temporary user object
		$e = new Grupo();
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
	 * Obtener Listado de Grupos
	 *
	 * .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_grupos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Grupo();
		$sql="select * from grupos order by id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_grupos_pdf()
	{
		// Create a temporary user object
		$ef = new Grupo();
		$sql="select * from grupos order by nombre";
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
