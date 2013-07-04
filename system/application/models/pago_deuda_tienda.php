<?php

/**
 * Pago_deuda_tienda Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pago_deuda_tienda extends DataMapper {

	var $table= "pago_deuda_tiendas";


	function  Pago_deuda_tienda($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cpr_estatus_pedido
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pagos_deudas_tiendas(){
		$e = new Pago_deuda_tienda();
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_pagos_deuda_tienda_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Pago_deuda_tienda();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pago_deuda_tienda($id)
	{
		// Create a temporary user object
		$u = new Pago_deuda_tienda();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


}
