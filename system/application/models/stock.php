<?php

/**
 * Stock Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Stock extends DataMapper {

	var $table= "stock";



	function  Stock($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Stock
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_stocks()
	{
		// Create a temporary user object
		$e = new Stock();
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
	 * get_Stock
	 *
	 * Obtiene los datos de un stock partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_stock($id)
	{
		// Create a temporary user object
		$u = new Stock();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_stocks_list($offset, $per_page, $empresa)
	{
		// Create a temporary user object
		$u = new Stock();
		$sql="select p.* from stock as p left join usuarios as u on u.id=p.usuario_id where u.empresas_id='$empresa' order by p.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_stocks_pdf()
	{
		// Create a temporary user object
		$u = new Stock();
		$sql="select c.*, e.tag as estatus from cstocks as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
