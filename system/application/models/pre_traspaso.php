<?php

/**
 * Pre_traspaso Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pre_traspaso extends DataMapper {

	var $table= "pre_traspasos";



	function  Pre_traspaso($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pre_traspaso
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pre_traspasos() {
		// Create a temporary user object
		$e = new Pre_traspaso();
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
	 * get_Pre_traspaso
	 *
	 * Obtiene los datos de un pre_traspaso partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_pre_traspaso($id)
	{
		// Create a temporary user object
		$u = new Pre_traspaso();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pre_traspasos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Pre_traspaso();
		$sql="select p.*, e1.tag as origen, e2.tag as destino, u.username as usuario, e.tag as estatus from pre_traspasos as p left join espacios_fisicos as e1 on e1.id=p.espacio_salida_id left join espacios_fisicos as e2 on e2.id=p.espacio_entrada_id left join usuarios as u on u.id=p.usuario_id left join estatus_general as e on e.id=p.estatus_general_id order by fecha desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pre_traspasos_count() {
		// Create a temporary user object
		$e = new Pre_traspaso();
		//Buscar en la base de datos
		$e->select("count(id) as total")->get();
		if($e->c_rows>0){
			return $e->total;
		} else {
			return FALSE;
		}
	}
	function get_pre_traspaso_pdf($id)
	{
		// Create a temporary user object
		$u = new Pre_traspaso();
		$sql="select p.*, e1.tag as origen, e2.tag as destino, u.username as usuario, e.tag as estatus from pre_traspasos as p left join espacios_fisicos as e1 on e1.id=p.espacio_salida_id left join espacios_fisicos as e2 on e2.id=p.espacio_entrada_id left join usuarios as u on u.id=p.usuario_id left join estatus_general as e on e.id=p.estatus_general_id where  p.id=$id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}
}
