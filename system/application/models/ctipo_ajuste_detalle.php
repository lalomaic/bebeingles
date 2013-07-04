<?php

/**
 * Ctipo_ajuste_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Ctipo_ajuste_detalle extends DataMapper {
	var $table= "ctipo_ajuste_detalles";


	function  Ctipo_ajuste_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Ctipo_ajuste_detalles
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipos_ajuste_detalles()
	{
		// Create a temporary user object
		$e = new Ctipo_ajuste_detalle();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_ctipo_ajuste_detalles_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select a.*, u.nombre as usuario, ef.tag as espacio, ce.tag as estatus, eg.tag as estatus_general from ctipo_ajuste_detalles as a left join usuarios as u on u.id=a.usuario_id left join cestatus_ctipo_ajuste_detalles as ce on ce.id=a.cestatus_ctipo_ajuste_detalle_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id left join estatus_general as eg on eg.id=a.estatus_general_id order by a.fecha desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ctipo_ajuste_detalles_tienda($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select a.*, u.nombre as usuario, ef.tag as espacio, ce.tag as estatus from ctipo_ajuste_detalles as a left join usuarios as u on u.id=a.usuario_id left join cestatus_ctipo_ajuste_detalles as ce on ce.id=a.cestatus_ctipo_ajuste_detalle_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id where a.estatus_general_id='1' and a.espacio_fisico_id='$ubicacion' order by a.fecha desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ctipo_ajuste_detalle($id)
	{
		// Create a temporary user object
		$u = new Ctipo_ajuste_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_ctipo_ajuste_detalles_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select a.*, u.nombre as usuario, ef.tag as espacio, ce.tag as estatus from ctipo_ajuste_detalles as a left join usuarios as u on u.id=a.usuario_id left join cestatus_ctipo_ajuste_detalles as ce on ce.id=a.cestatus_ctipo_ajuste_detalle_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id $where and a.estatus_general_id='1' $order_by";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
