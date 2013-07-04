<?php

/**
 * Subfamilia de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramírez SARS
 * @link
 */
class Subfamilia_producto extends DataMapper {

	var $table= "cproductos_subfamilias";



	function  Subfamilia_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Subfamilia de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cproductos_subfamilias()
	{
		// Create a temporary user object
		$e = new Subfamilia_producto();
		$e->where('estatus_general_id', "1")->order_by('tag asc');

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_subfamilia_productos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Subfamilia_producto();
		$sql="select sfp.*, pf.tag as familia, eg.tag as estatus from cproductos_subfamilias as sfp left join cproductos_familias as pf on pf.id=sfp.familia_id left join estatus_general as eg on eg.id=sfp.estatus_general_id order by sfp.tag, sfp.tag limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_subfamilia($id)
	{
		// Create a temporary user object
		$u = new Subfamilia_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_subfamilias_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Subfamilia_producto();
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
