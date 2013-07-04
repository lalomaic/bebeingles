<?php

/**
 * Arqueo_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Arqueo_detalle extends DataMapper {
	var $table= "arqueo_detalles";


	function  Arqueo_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Arqueo_detalles
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_arqueo_detalles()
	{
		// Create a temporary user object
		$e = new Arqueo_detalle();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_arqueo_detalles_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, e.razon_social as empresa, cp.razon_social as cliente, u.nombre as usuario from arqueo_detalles as prf left join empresas as e on e.id=prf.empresas_id left join cclientes as cp on cp.id=prf.cclientes_id left join usuarios as u on u.id=prf.usuario_id order by prf.porciento_error desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_arqueo_detalle($id)
	{
		// Create a temporary user object
		$u = new Arqueo_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_arqueo_detalles_by_parent($id) {
		// Create a temporary user object
		$u = new Arqueo_detalle();
		//Buscar en la base de datos
		$sql="select ad.id as llave, ad.*, u.nombre as usuario, ef.tag as espacio, 
                        p.descripcion as producto,  pn.numero_mm as numero, ta.tag as accion, ta.id as accion_id 
                        from arqueo_detalles as ad left join usuarios as u on u.id=ad.usuario_id 
                        join arqueos as a on a.id=ad.arqueo_id 
                        join ctipo_ajuste_detalles as ta on ta.id=ad.ctipo_ajuste_detalle_id 
                        join espacios_fisicos as ef on ef.id=a.espacio_fisico_id 
                        join cproductos_numeracion as pn on pn.id=ad.cproductos_numero_id 
                        join cproductos as p on p.id=ad.cproducto_id 
                        where arqueo_id='$id'  and ad.estatus_general_id=1 order by producto ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_arqueo_detalles_by_padre($id)
	{
		// Create a temporary user object
		$u = new Arqueo_detalle();
		//Buscar en la base de datos
		$sql="select ad.id as llave, ad.*, u.nombre as usuario, ef.tag as espacio, p.descripcion as producto,  pn.numero_mm as numero, ta.tag as accion, ta.id as accion_id from arqueo_detalles as ad left join usuarios as u on u.id=ad.usuario_id left join arqueos as a on a.id=ad.arqueo_id left join ctipo_ajuste_detalles as ta on ta.id=ad.ctipo_ajuste_detalle_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id left join cproductos_numeracion as pn on pn.id=ad.cproductos_numero_id left join cproductos as p on p.id=ad.cproducto_id where arqueo_id='$id' and a.estatus_general_id='1' and ctipo_ajuste_detalle_id>1 order by ad.id asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_arqueos_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select ad.*, u.nombre as usuario, ef.tag as espacio, concat(p.descripcion, ' - ', p.presentacion) as producto from arqueo_detalles as ad left join usuarios as u on u.id=ad.usuario_id left join arqueos as a on a.id=ad.arqueo_id left join cestatus_arqueos as ce on ce.id=a.cestatus_arqueo_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id left join cproductos as p on p.id=ad.cproducto_id $where and a.estatus_general_id='1' $order_by";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_arqueo_total_xcuadrar($id)
	{
		// Create a temporary user object
		$e = new Arqueo_detalle();
		//Buscar en la base de datos
		$e->select("count(id) as total")->where("arqueo_id", $id)->where("estatus_general_id", 1)->where('ctipo_ajuste_detalle_id > ',0)->get();
		return $e->total;
	}

	function get_arqueo_detalles_cuadrados($id)
	{
		// Create a temporary user object
		$u = new Arqueo_detalle();
		//Buscar en la base de datos
		$sql="select ad.id as llave, ad.*, p.descripcion as producto,  pn.numero_mm as numero, ta.tag as accion, ta.id as accion_id from arqueo_detalles as ad left join usuarios as u on u.id=ad.usuario_id left join arqueos as a on a.id=ad.arqueo_id left join ctipo_ajuste_detalles as ta on ta.id=ad.ctipo_ajuste_detalle_id left join espacios_fisicos as ef on ef.id=a.espacio_fisico_id left join cproductos_numeracion as pn on pn.id=ad.cproductos_numero_id left join cproductos as p on p.id=ad.cproducto_id where arqueo_id='$id'  and ad.estatus_general_id=1 order by llave ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

}

