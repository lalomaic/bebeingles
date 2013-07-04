<?php

/**
 * Tipo Salida Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_salida extends DataMapper {

	var $table= "ctipos_salidas";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Nombre del Tipo de Salida',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Tipo_salida($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Salida
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_salidas()
	{
		// Create a temporary user object
		$e = new Tipo_salida();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_salidas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Tipo_salida();
		$sql="select * from ctipos_salidas order by tag limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salida($id)
	{
		// Create a temporary user object
		$u = new Tipo_salida();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_tipos_salida_dropd() {
		$tipos = new Tipo_salida();
		$tipos->
		where('estatus_general_id', 1)->order_by('tag')->get();
		$tipos_array = array();
		foreach($tipos as $tipo)
			$tipos_array[$tipo->id] = $tipo->tag;
		return $tipos_array;
	}

	function get_tipos_salida_as_array() {
		$tipos = new Tipo_salida();
		$tipos->get();
		$tipos_array = array();
		foreach($tipos as $tipo)
			$tipos_array[$tipo->id] = $tipo->tag;
		return $tipos_array;
	}

}
