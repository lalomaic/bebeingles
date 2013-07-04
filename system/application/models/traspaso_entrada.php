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
class Traspaso_entrada extends DataMapper {
	var $table= "traspasos_entradas";

	function  Traspaso_entrada($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Traspaso_entrada
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_traspasos_entrada_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Traspaso_entrada();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_traspaso_entrada($id)
	{
		// Create a temporary user object
		$u = new Traspaso_entrada();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entradas_traspaso($traspaso_id)
	{
		// Create a temporary user object
		$u = new Traspaso_entrada();
		//Buscar en la base de datos
		$sql="select te.*, e.* from traspasos_entradas as te left join entradas as e on e.id=te.entradas_id where traspasos_id='$traspaso_id' order by e.id";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entradas_pdf($id)
	{
		//Objeto de la clase
		$obj = new Cl_detalle_pedido();
		$sql="select te.*, e.*, cproductos.descripcion AS producto, cproductos.presentacion as presentacion from traspasos_entradas as te left join entradas as e on e.id=te.entradas_id LEFT JOIN cproductos ON cproductos.id=e.cproductos_id where traspasos_id='$id' order by producto";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

}
