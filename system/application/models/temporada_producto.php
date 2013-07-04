<?php

/**
 * Temporadas de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Temporada_producto extends DataMapper {

	var $table= "cproductos_temporada";



	function  Temporada_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Temporada de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_temporadas()
	{
		// Create a temporary user object
		$e = new Temporada_producto();
		$e->where('estatus_general_id', "1");
		$e->order_by("tag asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_temporada_productos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Temporada_producto();
		$sql="select mp.*, eg.tag as estatus, p.razon_social as proveedor from temporadas_productos as mp left join cproveedores as p on p.id=mp.proveedor_id left join estatus_general as eg on eg.id=mp.estatus_general_id order by mp.tag asc limit $per_page offset $offset ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_temporada($id)
	{
		// Create a temporary user object
		$u = new Temporada_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_temporadas_proveedor($proveedor_id)
	{
		// Create a temporary user object
		$e = new Temporada_producto();
		$e->select('id, tag');
		$e->where('estatus_general_id', "1")->where('cproveedor_id', $proveedor_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_temporadas_mtrx(){
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Temporada_producto();
		$ef->where('estatus_general_id', 1);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			$colect[0]="TODAS";
			return $colect;
		} else
			return 0;
	}
}
