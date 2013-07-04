<?php

/**
 * Municipio Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Municipio extends DataMapper {

	var $table= "cmunicipios";


	function  Municipio($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
	function get_municipio($id)
	{
		// Create a temporary user object
		$e = new Municipio();

		//Buscar en la base de datos
		$e->get_by_id($id);
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	/**
	 * Municipio
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_municipios()
	{
		// Create a temporary user object
		$e = new Municipio();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * Obtener Listado de Municipios
	 *
	 * .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_municipios_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Municipio();
		$sql="select cm.*, ce.tag as estado from cmunicipios as cm left join cestados as ce on ce.id=cm.cestado_id order by cm.id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_municipios_pdf()
	{
		//Objeto de la clase
		$obj = new Municipio();
		$sql = "SELECT cmunicipios.id, cmunicipios.tag AS municipio, cestados.tag AS estado FROM cmunicipios LEFT JOIN cestados ON cestados.id = cmunicipios.cestado_id WHERE cmunicipios.cestado_id > 0 ORDER BY estado,municipio";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

}
