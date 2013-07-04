<?php

/**
 * Forma de Cobro Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Forma_cobro extends DataMapper {

	var $table= "ccl_formas_cobro";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Forma de Cobro',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Forma_cobro($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Forma de Cobro
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_formas_cobros()
	{
		// Create a temporary user object
		$e = new Forma_cobro();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_formas_cobros_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Forma_cobro();
		$sql="select * from ccl_formas_cobro order by id limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_forma_cobro($id)
	{
		// Create a temporary user object
		$u = new Forma_cobro();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
