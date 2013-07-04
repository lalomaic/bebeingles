<?php

/**
 * Tipos de Pago de Pedido de compra CPR_TIPOS_PAGO Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Cpr_pago extends DataMapper {

	var $table= "cpr_pago";


	function  Cpr_pago($id=null)
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
	function get_formas_pago()
	{
		// Create a temporary user object
		$e = new Cpr_pago();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			//echo $e->c_rows;
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_cpr_forma_pago_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cpr_pago();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cpr_forma_pago($id)
	{
		// Create a temporary user object
		$u = new Cpr_pago();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


}
