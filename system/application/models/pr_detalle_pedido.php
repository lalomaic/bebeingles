<?php

/**
 * Pr_detalle_pedido Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Pr_detalle_pedido extends DataMapper {

	var $table= "pr_detalle_pedidos";


	function  Pr_detalle_pedido($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pr_detalle_pedido
	 *
	 * Obtiene los detalles del pr_pedido=$id
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pr_detalles_pedido_parent($id)
	{
		//
		$u = new Pr_detalle_pedido();
		//Buscar en la base de datos
		$sql = "SELECT prd.id as pr_detalle_id, pn.id as cproducto_numero_id, prd.cproductos_id, prd.cantidad, cproductos.descripcion AS producto, pn.numero_mm, prd.costo_unitario, prd.costo_total, prd.tasa_impuesto FROM pr_detalle_pedidos as prd LEFT JOIN cproductos ON cproductos.id=prd.cproductos_id left join cproductos_numeracion as pn on pn.id=prd.cproducto_numero_id WHERE prd.pr_pedidos_id = $id and prd.estatus_general_id ='1' order by cproductos.descripcion asc, pn.numero_mm asc";
		$u->query($sql);

		if($u->c_rows >0 ){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_detalle_pedidos()
	{
		// Create a temporary user object
		$e = new Pr_detalle_pedido();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_pr_detalles_pedido($id)
	{
		//Objeto de la clase
		$obj = new Pr_detalle_pedido();
		$sql = "SELECT pn.id as cproducto_numero_id, prd.cproductos_id, prd.cantidad, (cproductos.descripcion || ' ' || pn.numero_mm) AS producto, prd.costo_unitario, prd.costo_total, prd.tasa_impuesto FROM pr_detalle_pedidos as prd LEFT JOIN cproductos ON cproductos.id=pr_detalle_pedidos.cproductos_id left join cproductos_numeracion as pn on pn.id=prd.cproducto_numero_id WHERE pr_detalle_pedidos.id = $id and prd.estatus_general_id='1'";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}
	function get_pr_detalles_pedido_pdf($id)
	{
		//Objeto de la clase
		$obj = new Pr_detalle_pedido();
		$sql = "SELECT pdp.id, pdp.cantidad, p.descripcion AS producto, pn.numero_mm as presentacion, pdp.costo_unitario, pdp.costo_total, pdp.tasa_impuesto FROM pr_detalle_pedidos as pdp LEFT JOIN cproductos as p ON p.id=pdp.cproductos_id left join cproductos_numeracion as pn on pn.id=pdp.cproducto_numero_id WHERE pdp.pr_pedidos_id = $id and pdp.estatus_general_id='1' order by p.descripcion, pn.numero_mm";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

	function get_detalle_by_cproducto($id){
		$d=new Pr_detalle_pedido();
		$d->where('cproductos_id',$id)->limit(1)->get();
		if($d->c_rows==1)
			return true;
		else
			return false;
	}
	function get_pr_detalle_by_parent($id)
	{
		// Create a temporary user object
		$e = new Pr_detalle_pedido();
		//Buscar en la base de datos
		$e->where('pr_pedidos_id', $id)->where('estatus_general_id',1)->order_by('id asc')->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}
}
