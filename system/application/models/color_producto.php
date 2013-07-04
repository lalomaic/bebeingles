<?php

/**
 * Colores de Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Color_producto extends DataMapper {

	var $table= "cproductos_color";

	var $auto_populate_has_one = TRUE;
	var $has_one = array(
			'estatus_general' => array(
					'class' => 'estatus_general',
					'other_field' => 'color_producto'
			),
			'usuario'=>array(
					'class'=>'usuario',
					'other_field'=>'color_producto'
			)
	);


	function  Color_producto($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Color de Productos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cproductos_colores()
	{
		// Create a temporary user object
		$e = new Color_producto();
		$e->where('estatus_general_id', "1");
		$e->order_by("tag asc");
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}
	function get_colores_list($count = 20, $offset = 0) {
		$this->order_by("tag", "asc1");
		$this->get($count,$offset);
		if ($this->c_rows > 0) {
			return $this;
		} else {
			return FALSE;
		}
	}
	function get_colores_count(){
		$e=new Color_producto();
		return $e->get()->count();

	}
	function cancelar_color($id = -1) {
		$e = new Color_producto($id);
		$e->estatus_general_id = 2;
		$e->save();
	}

	function get_color_productos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Color_producto();
		$sql="select sfp.*, eg.tag as estatus from cproductos_colors as sfp left join estatus_general as eg on eg.id=sfp.estatus_general_id order by sfp.tag limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_color($id)
	{
		// Create a temporary user object
		$u = new Color_producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


}