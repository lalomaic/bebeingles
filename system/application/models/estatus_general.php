<?php

/**
 * Estatus General Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Estatus_general extends DataMapper {

	var $table= "estatus_general";
	var $has_many = array(
			'color_producto' => array(
					'class' => 'color_producto',
					'other_field' => 'estatus_general'
			)
			,
			'promocion' => array(
					'class' => 'promocion',
					'other_field' => 'estatus_general'
			)
	);



	function  Estatus_general($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Estatus List
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_estatus()
	{
		// Create a temporary user object
		$e = new Estatus_general();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_estatus_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Estaus_general();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
