<?php

/**
 * Forma de Pago Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Forma_pago extends DataMapper {

	var $table= "cpr_formas_pago";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Forma de Pago',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Forma_pago($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Forma de Pago
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_formas_pagos()
	{
		// Create a temporary user object
		$e = new Forma_pago();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_formas_pagos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Forma_pago();
		$sql="select * from cpr_formas_pago order by id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_forma_pago($id)
	{
		// Create a temporary user object
		$u = new Forma_pago();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
