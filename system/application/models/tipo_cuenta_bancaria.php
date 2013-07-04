<?php

/**
 * Tipo de Cuenta Bancaria Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_cuenta_bancaria extends DataMapper {

	var $table= "ctipos_cuentas";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Tipo de Cuenta Bancaria',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Tipo_cuenta_bancaria($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo de Cuenta Bancaria
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_cuentas()
	{
		// Create a temporary user object
		$e = new Tipo_cuenta_bancaria();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_cuentas_bancarias_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Tipo_cuenta_bancaria();
		$sql="select * from ctipos_cuentas order by id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_tipo_cuenta_bancaria($id)
	{
		// Create a temporary user object
		$u = new Tipo_cuenta_bancaria();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
