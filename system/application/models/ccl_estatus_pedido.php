<?php

/**
 * Estatus de Pedido de compra CPR_ESTATUS_PEDIDO Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Ccl_estatus_pedido extends DataMapper {

	var $table= "ccl_estatus_pedidos";


	function  Ccl_estatus_pedido($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Ccl_estatus_pedido
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ccl_estatus_pedido_all()
	{
		// Create a temporary user object
		$e = new Ccl_estatus_pedido();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_ccl_estatus_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Ccl_estatus_pedido();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_ccl_estatus_pedido($id)
	{
		// Create a temporary user object
		$u = new Ccl_estatus_pedido();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


}
