<?php
/**
 * Estatus de Pedido de compra CPR_ESTATUS_PEDIDO Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Traspaso_salida extends DataMapper {
	var $table= "traspasos_salidas";

	function  Traspaso_salida($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Traspaso_salida
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_traspasos_salida_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Traspaso_salida();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_traspaso_salida($id)
	{
		// Create a temporary user object
		$u = new Traspaso_salida();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salidas_traspaso($traspaso_id)
	{
		// Create a temporary user object
		$u = new Traspaso_salida();
		//Buscar en la base de datos
		$sql="select ts.*, s.*, cp.descripcion from traspasos_salidas as ts left join salidas as s on s.id=ts.salidas_id left join cproductos as cp on cp.id=s.cproductos_id where traspasos_id='$traspaso_id' order by cp.descripcion";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_salidas_pdf($id)
	{
		//Objeto de la clase
		$obj = new Cl_detalle_pedido();
		$sql="select ts.*, s.*, cproductos.descripcion AS producto, cproductos.presentacion as presentacion from traspasos_salidas as ts left join salidas as s on s.id=ts.salidas_id LEFT JOIN cproductos ON cproductos.id=s.cproductos_id where traspasos_id='$id' order by producto";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

}
