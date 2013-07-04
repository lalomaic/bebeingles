<?php

/**
 * Pre_traspaso_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pre_traspaso_detalle extends DataMapper {

	var $table= "pre_traspasos_detalles";



	function  Pre_traspaso_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pre_traspaso_detalle
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pre_traspaso_detalles() {
		$e = new Pre_traspaso_detalle();
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}
	/**
	 * get_Pre_traspaso_detalle
	 *
	 * Obtiene los datos de un pre_traspaso_detalle partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_pre_traspaso_detalle($id)
	{
		// Create a temporary user object
		$u = new Pre_traspaso_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pre_traspaso_detalles_by_parent_pdf($id)
	{
		// Create a temporary user object
		$u = new Pre_traspaso_detalle();
		$sql="select td.*, p.descripcion as producto  from pre_traspasos_detalles as td left join cproductos as p on p.id=td.cproducto_id where pre_traspaso_id=$id order by p.descripcion asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows >0 ){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_pre_traspaso_detalles_pdf()
	{
		// Create a temporary user object
		$u = new Pre_traspaso_detalle();
		$sql="select c.*, e.tag as estatus from cpre_traspaso_detalles as c left join estatus_general as e on e.id=c.estatus_general_id order by c.razon_social";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
}
