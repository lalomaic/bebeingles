<?php

/**
 * Espacio_stock Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Espacio_stock extends DataMapper {

	var $table= "espacios_stock";



	function  Espacio_stock($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Espacio_stock
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_espacio_stocks()
	{
		// Create a temporary user object
		$e = new Espacio_stock();
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
	 * get_Espacio_stock
	 *
	 * Obtiene los datos de un espacio_stock partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_espacio_stock($id)
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_espacio_stocks_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		$sql="select p.*, e.tag as estatus from espacio_stocks as p left join estatus_general as e on e.id=p.estatus_general_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_espacio_stocks_pdf()
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		$sql="select c.*, e.tag as estatus from cespacio_stocks as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_espacio_stock_by_plantilla($stock_id)
	{
		// Create a temporary user object
		$e = new Espacio_stock();
		//Buscar en la base de datos
		$e->select("espacio_fisico_id, id");
		$e->where('stock_id',$stock_id);
		$e->get();
		if($e->c_rows > 0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_espacio_stock_by_espacio($espacio_fisico){
		// Create a temporary user object
		$e = new Espacio_stock();
		//Buscar en la base de datos
		$e->select("stock_id");
		$e->where('espacio_fisico_id',$espacio_fisico);
		$e->get();
		if($e->c_rows > 0){
			return $e->stock_id;
		} else
			return FALSE;
	}

	function get_espacio_stocks_select($espacio_fisico)
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		$sql="select c.*, s.nombre from espacios_stock as c left join stock as s on s.id=c.stock_id where c.espacio_fisico_id='$espacio_fisico' and s.estatus_general_id='1' order by nombre";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_espacio_stocks_almacen($empresa_id)
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		$sql="select c.*, s.nombre from espacios_stock as c left join stock as s on s.id=c.stock_id left join espacios_fisicos as ef on ef.id=c.espacio_fisico_id where ef.empresas_id='$empresa_id' and s.estatus_general_id='1' and ef.estatus_general_id='1' order by nombre";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;

	}

	function get_espacio_stocks_ubicacion($stock)
	{
		// Create a temporary user object
		$u = new Espacio_stock();
		$sql="select c.*, s.nombre from espacios_stock as c left join stock as s on s.id=c.stock_id where c.stock_id='$stock' and s.estatus_general_id='1' order by nombre limit 1 ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
