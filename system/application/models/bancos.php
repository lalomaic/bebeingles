<?php

/**
 * Marca de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Bancos extends DataMapper {

	var $table= "bancos";



	function  bancos($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Marca de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_bancos()
	{
		// Create a temporary user object
		$e = new bancos();
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

	function get_bancos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Marca_producto();
		$sql="select ban.tag,ban.id, eg.tag as estatus from bancos as ban left join estatus_general as eg on eg.id=ban.estatus_general_id where estatus_general_id='1' order by ban.tag asc limit $per_page offset $offset ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_banco($id)
	{
		// Create a temporary user object
		$u = new bancos();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_bancos_filtro()
	{
		// Create a temporary user object
		$e = new Bancos();
           $e->where('estatus_general_id',"1");
            $e->order_by("tag asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

}

