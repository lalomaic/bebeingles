<?php

/**
 * Familia de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Familia_producto extends DataMapper {

	var $table= "cproductos_familias";
	var $has_many = array(
			'subfamilia_producto' => array(
					'class' => 'subfamilia_producto',
					'other_field' => 'cproducto_familia'
			)
	);

	function  Familia_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Familia de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cproductos_familias()
	{
		// Create a temporary user object
		$e = new Familia_producto();
		$e->where('estatus_general_id', "1")->order_by('tag asc');

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_familia_productos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Familia_producto();
		$sql="select fp.*, eg.tag as estatus from cproductos_familias as fp left join estatus_general as eg on eg.id=fp.estatus_general_id order by id limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_familia($id)
	{
		// Create a temporary user object
		$u = new Familia_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_familias_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Familia_producto();
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			$colect[0]="TODOS";
			return $colect;
		} else
			return 0;
	}

}
