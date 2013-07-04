<?php

/**
 * Origen_pedido Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Origen_pedido extends DataMapper {

	var $table= "corigen_pedidos";



	function  Origen_pedido($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Origen_pedido
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_origen_pedidos()
	{
		// Create a temporary user object
		$e = new Origen_pedido();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Origen_pedido
	 *
	 * Obtiene los datos de un cliente partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_origenes_pedido($id)
	{
		// Create a temporary user object
		$u = new Origen_pedido();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_origenes_pedido_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Origen_pedido();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cclientes as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
