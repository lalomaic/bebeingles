<?php

/**
 * Receta Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Receta extends DataMapper {

	var $table= "recetas";



	function  Recetas($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Receta
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_recetas()
	{
		// Create a temporary user object
		$e = new Receta();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->order_by('nombre', 'asc');
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Receta
	 *
	 * Obtiene los datos de una receta a partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_receta($id)
	{
		// Create a temporary user object
		$u = new Receta();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_recetas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Receta();
		$sql="select r.*, p.descripcion as producto, eg.tag as estatus from recetas as r left join estatus_general as eg on eg.id=r.estatus_general_id left join cproductos as p on p.id=r.cproductos_id where r.estatus_general_id=1 order by nombre limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	 
	function get_recetas_list_count()
	{
		// Create a temporary user object
		$u = new Receta();
		$sql="select count(r.id) as total from recetas as r left join estatus_general as eg on eg.id=r.estatus_general_id where r.estatus_general_id=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->total;
		} else {
			return FALSE;
		}
	}
	 
	function get_recetas_pdf()
	{
		// Create a temporary user object
		$u = new Receta();
		$sql="select r.id, r.nombre, r.descripcion, r.cantidad, r.dias_consumo, p.descripcion as producto from recetas as r left join cproductos as p on p.id=r.cproductos_id where r.estatus_general_id=1 order by nombre";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
