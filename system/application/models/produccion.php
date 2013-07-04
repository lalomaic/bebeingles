<?php

/**
 * Produccion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Produccion extends DataMapper {

	var $table= "produccion";



	function  Producciones($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Produccion
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_producciones()
	{
		// Create a temporary user object
		$e = new Produccion();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->order_by('id');
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Produccion
	 *
	 * Obtiene los datos de Producción a partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_produccion($id)
	{
		// Create a temporary user object
		$u = new Produccion();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_produccion_list($offset, $per_page, $espacio)
	{
		$u = new Produccion();
		$sql="select p.id, p.cantidad_producida, p.fecha_captura, p.entrada_id,p.estatus_general_id, r.nombre, cp.descripcion, cp.presentacion, e.tag espacio from produccion as p left join recetas as r on r.id=p.receta_id left join cproductos as cp on cp.id=r.cproductos_id left join espacios_fisicos as e on e.id=p.espacio_fisico_id where p.estatus_general_id='1' and espacio_fisico_id='$espacio' order by p.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
