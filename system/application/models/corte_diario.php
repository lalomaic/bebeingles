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
class Corte_diario extends DataMapper {
	var $table= "cortes_diarios";

	function  Corte_diario($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Corte_diario
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_corte_diario_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Corte_diario();
		$sql="select c.*, e.tag from cortes_diarios left join espacios_fisicos as e on e.id=c.espacios_fisicos_id order by fecha_corte desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cortes_diarios_pendientes_tienda($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Corte_diario();
		$sql="select c.*, e.tag, u.nombre as usuario from cortes_diarios as c left join espacios_fisicos as e on e.id=c.espacios_fisicos_id left join usuarios as u on u.id=c.usuario_id where c.espacios_fisicos_id='$ubicacion' and factura_id is null order by fecha_corte limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cortes_diarios_tienda($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Corte_diario();
		$sql="select c.*, e.tag, u.nombre as usuario, cf.folio_factura from cortes_diarios as c left join espacios_fisicos as e on e.id=c.espacios_fisicos_id left join usuarios as u on u.id=c.usuario_id left join cl_facturas as cf on cf.id=c.factura_id where c.espacios_fisicos_id='$ubicacion' and factura_id>0 order by c.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_corte_diario($id)
	{
		// Create a temporary user object
		$u = new Corte_diario();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_corte_diario_detalles($id, $ubicacion)
	{
		// Create a temporary user object
		$u = new Corte_diario();
		$sql="select sr.*, s.* from salidas_remision as sr left join salidas as s on s.id_ubicacion_local=sr.id_ubicacion_local where sr.numero_remision='$id' and sr.espacio_fisico_id='$ubicacion' and s.cl_facturas_id='0' and s.ctipo_salida_id='1' and s.estatus_general_id='1'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
