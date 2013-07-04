<?php

/**
 * Cl_detalle_pedido Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Cl_detalle_pedido extends DataMapper {

	var $table= "cl_detalle_pedidos";


	function  Cl_detalle_pedido($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cl_detalle_pedido
	 *
	 * Obtiene los detalles del pr_pedido=$id
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cl_detalles_pedido_parent($id)
	{
		//
		$obj = new Cl_detalle_pedido();
		$sql = "SELECT c.*, cproductos.descripcion AS producto, cproductos.presentacion as presentacion FROM cl_detalle_pedidos as c LEFT JOIN cproductos ON cproductos.id=c.cproductos_id WHERE c.cl_pedidos_id = $id order by cproductos.descripcion";
		$obj->query($sql);
		if($obj->c_rows>0 ){
			return $obj;
		} else {
			return FALSE;
		}
	}
	function get_cl_detalle_pedidos()
	{
		// Create a temporary user object
		$e = new Cl_detalle_pedido();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_cl_detalles_pedido($id)
	{
		//Objeto de la clase
		$obj = new Cl_detalle_pedido();
		$sql = "SELECT cl_detalle_pedidos.id, cl_detalle_pedidos.cantidad, cproductos.descripcion AS producto, cproductos.presentacion as presentacion, cl_detalle_pedidos.costo_unitario, cl_detalle_pedidos.costo_total, cl_detalle_pedidos.tasa_impuesto FROM cl_detalle_pedidos LEFT JOIN cproductos ON cproductos.id=cl_detalle_pedidos.cproductos_id WHERE cl_detalle_pedidos.id = $id";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}
	function get_cl_detalles_pedido_pdf($id)
	{
		//Objeto de la clase
		$obj = new Cl_detalle_pedido();
		$sql = "SELECT cl_detalle_pedidos.id, cl_detalle_pedidos.cantidad, cproductos.descripcion AS producto, cproductos.presentacion as presentacion, cl_detalle_pedidos.costo_unitario, cl_detalle_pedidos.costo_total, cl_detalle_pedidos.tasa_impuesto FROM cl_detalle_pedidos LEFT JOIN cproductos ON cproductos.id=cl_detalle_pedidos.cproductos_id WHERE cl_detalle_pedidos.cl_pedidos_id = $id order by producto";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

}
