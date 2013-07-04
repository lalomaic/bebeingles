<?php

/**
 * Lote_factura Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Lote_factura extends DataMapper {

	var $table= "lotes_pr_facturas";

	function  Lote_factura($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Lote_factura
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_lote_factura()
	{
		// Create a temporary user object
		$e = new Lote_factura();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_lote_factura_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Lote_factura();
		$sql="select p.*, mp.tag as marca_lote, psf.tag as subfamilia_lote, eg.tag as estatus, pf.tag as familia_lote, pm.tag as material, pc.tag as color, cmp.tag as marca from clote_factura as p left join cmarcas_lote_factura as mp on mp.id=p.cmarca_lote_id left join clote_factura_subfamilias as psf on psf.id=p.csubfamilia_id left join clote_factura_familias as pf on pf.id=p.cfamilia_id left join clote_factura_material as pm on p.cmaterial_id=pm.id left join clote_factura_color as pc on pc.id=p.ccolor_id left join cmarcas_lote_factura as cmp on cmp.id=p.cmarca_lote_id left join estatus_general as eg on eg.id=p.estatus_general_id order by p.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_lote($id)
	{
		// Create a temporary user object
		$u = new Lote_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_lote_factura_detalles()
	{
		// Create a temporary user object
		$u = new Lote_factura();
		$sql="select p.id as clote_id, pn.id as lote_numero_id, p.descripcion,  'Par' as unidad_medida from clote_factura as p left join clote_factura_numeracion as pn on pn.clote_id=p.id where p.estatus_general_id='1' order by descripcion limit 10";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_lote_factura_etiquetas()
	{
		// Create a temporary user object
		$e = new Lote_factura();
		$e->where("estatus_general_id", 1);
		$e->where('codigo_barras >', 0);
		$e->order_by('descripcion');
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_lote_factura_by_factura_id($id)
	{
		// Create a temporary user object
		$e = new Lote_factura();
		$e->where("pr_factura_id", $id)->get();
		//Buscar en la base de datos
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

}
