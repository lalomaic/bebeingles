<?php

/**
 * Promocion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Promocion extends DataMapper {

	var $table= "promociones";
	var $has_one = array(
			'cproducto' => array(
					'class' => 'producto',
					'other_field' => 'promocion'
			),
			'estatus_general' => array(
					'class' => 'estatus_general',
					'other_field' => 'promocion'
			)
	);


	function  Promocion($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Promocion
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_promociones_validas()
	{
		// Create a temporary user object
		$u = new Promocion();
		$sql="select p.*, cp.descripcion, cp.descripcion as presentacion from promociones as p left join cproductos as cp on cp.id=p.cproducto_id where p.fecha_final>='".date("Y-m-d")."' and p.fecha_inicio<='".date("Y-m-d")."' and p.estatus_general_id=1" ;
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows>0){
			return $u;
		} else {
			return FALSE;
		}
	}
	/**
	 * get_Promocion
	 *
	 * Obtiene los datos de un promocion partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_promocion($id)
	{
		// Create a temporary user object
		$u = new Promocion();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_promociones_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Promocion();
		$sql="select p.*, e.tag as estatus from promociones as p left join estatus_general as e on e.id=p.estatus_general_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_promociones_pdf()
	{
		// Create a temporary user object
		$u = new Promocion();
		$sql="select c.*, e.tag as estatus from promociones as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function cancelar_promocion($id = -1) {
		$e = new Promocion($id);
		$e->estatus_general_id = 2;
		$e->save();
	}
	function activar_promocion($id = -1) {
		$e = new Promocion($id);
		$e->estatus_general_id = 1;
		$e->save();
	}

	function get_promociones_lista($count = 20, $offset = 0) {
		$e = new Promocion();
		$e->get($count,$offset);
		$e->order_by('fecha_captura', 'ASC');
		if ($e->c_rows > 0) {
			return $e;
		} else {
			return FALSE;
		}
	}
	function get_promociones_count(){
		$e=new Promocion();
		return $e->get()->count();

	}
}
