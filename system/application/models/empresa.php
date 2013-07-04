<?php

/**
 * Empresa Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Empresa extends DataMapper {

	var $table= "empresas";


	function  Empresa($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------


	function get_empresa($id)
	{
		// Create a temporary user object
		$e = new Empresa();
		//Buscar en la base de datos
		$e->get_by_id($id);
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * Empresas
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_empresas()
	{
		// Create a temporary user object
		$e = new Empresa();
		$e->where('estatus_general_id', "1");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_empresas_pdf($where, $order)
	{
		// Create a temporary user object
		$ef = new Empresa();
		$sql="select e.* from empresas as e $order";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}
	function get_empresas_list($offset, $per_page)
	{
		// Create a temporary user object
		$ef = new Empresa();
		$sql="select e.* from empresas as e limit $per_page offset $offset";
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
