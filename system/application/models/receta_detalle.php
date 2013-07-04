<?php

/**
 * Receta_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Receta_detalle extends DataMapper {

	var $table= "receta_detalles";



	function  Receta_detalles($id=null)
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
	function get_receta_detalles()
	{
		// Create a temporary user object
		$e = new Receta_detalle();
		//Buscar en la base de datos
		//$e->where('estatus_general_id','1');
		//	  $e->order_by('nombre', 'asc');
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
	function get_receta_detalle($id)
	{
		// Create a temporary user object
		$u = new Receta_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_receta_detalles_pdf($id)
	{
		// Create a temporary user object
		$u = new Receta_detalle();
		$sql="select r.id, rd.receta_id, rd.cantidad, r.nombre, r.descripcion, r.modo_preparacion, r.dias_consumo, p.descripcion from receta_detalles as rd left join recetas as r on r.id=rd.receta_id left join cproductos as p on p.id=rd.cproducto_id where r.id=$id order by rd.id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
