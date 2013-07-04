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
class Salida_remision extends DataMapper {
	var $table= "salidas_remision";

	function  Salida_remision($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Salida_remision
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_salida_remision_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Salida_remision();
		$sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_salida_remision($id)
	{
		// Create a temporary user object
		$u = new Salida_remision();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_salida_remision_detalles($id, $ubicacion)
	{
		// Create a temporary user object
		$u = new Salida_remision();
		$sql="select s.* from salidas as s where s.numero_remision='$id' and s.espacios_fisicos_id='$ubicacion' and s.cl_facturas_id='0' and s.ctipo_salida_id='1' and s.estatus_general_id='1'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
