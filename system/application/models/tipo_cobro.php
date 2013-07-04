<?php

/**
 * Tipo de Cobros Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_cobro extends DataMapper {

	var $table= "ctipos_cobros";


	var $validation = array(
			array(
					'field' => 'tag',
					'label' => 'Tipo de Cobro',
					'rules' => array('required', 'trim', 'min_length' => 3, 'max_length' => 250)
			),
	);

	function  Tipo_cobro($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo de Cobros
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_cobros()
	{
		// Create a temporary user object
		$e = new Tipo_cobro();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_cobros_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Tipo_cobro();
		$sql="select * from ctipos_cobros order by id limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_tipo_cobro($id)
	{
		// Create a temporary user object
		$u = new Tipo_cobro();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

}
