<?php

/**
 * Stock_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Stock_detalle extends DataMapper {

	var $table= "stock_detalles";



	function  Stock_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Stock_detalle
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_stock_detalles()
	{
		// Create a temporary user object
		$e = new Stock_detalle();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Stock_detalle
	 *
	 * Obtiene los datos de un stock_detalle partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_stock_detalle($id)
	{
		// Create a temporary user object
		$u = new Stock_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_stock_detalles_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Stock_detalle();
		$sql="select p.*, e.tag as estatus from stock_detalles as p left join estatus_general as e on e.id=p.estatus_general_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_stock_detalles_pdf()
	{
		// Create a temporary user object
		$u = new Stock_detalle();
		$sql="select c.*, e.tag as estatus from cstock_detalles as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_stock_detalle_by_stock_id($id)
	{
		// Create a temporary user object
		$u = new Stock_detalle();
		//Buscar en la base de datos
		$sql="select sd.id as stock_detalle_id, sd.*, p.*, f.tag as familia from stock_detalles as sd left join cproductos as p on p.id=sd.cproducto_id left join cproductos_familias as f on f.id=p.cfamilia_id where sd.stock_id='$id'";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}	function get_stock_detalle_by_stock_id_tienda($id)
	{
		// Create a temporary user object
		$u = new Stock_detalle();
		//Buscar en la base de datos
		$sql="select sd.id as stock_detalle_id, sd.*, p.*, f.tag as familia from stock_detalles as sd left join cproductos as p on p.id=sd.cproducto_id left join cproductos_familias as f on f.id=p.cfamilia_id where sd.stock_id='$id' order by p.descripcion";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
