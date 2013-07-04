<?php

/**
 * Tipo de Pagos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_pago extends DataMapper {

	var $table= "ctipos_pagos";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Tipo de Pago',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Tipo_pago($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo de Pagos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_pagos()
	{
		// Create a temporary user object
		$e = new Tipo_pago();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_pagos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Tipo_pago();
		$sql="select * from ctipos_pagos order by id limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_tipo_pago($id)
	{
		// Create a temporary user object
		$u = new Tipo_pago();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
