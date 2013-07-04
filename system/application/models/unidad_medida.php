<?php

/**
 * Unidad de Medida Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Unidad_medida extends DataMapper {

	var $table= "cunidades_medidas";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Unidad de Medida',
					'rules' => array('required', 'trim', 'unique', 'min_length' => 3, 'max_length' => 50)
			),
			array(
					'field' => 'fecha_alta',
					'label' => 'Fecha de Alta',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 20)
			),
	);

	function  Unidad_medida($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Unidad de Medida
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cunidades_medidas()
	{
		// Create a temporary user object
		$e = new Unidad_medida();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_unidades_medidas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Unidad_medida();
		$sql="select * from cunidades_medidas order by tag limit  $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_unidad($id)
	{
		// Create a temporary user object
		$u = new Unidad_medida();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
