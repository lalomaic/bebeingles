<?php

/**
 * Pr_pedido_multiple Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pr_pedido_multiple extends DataMapper {

	var $table= "pr_pedidos_multiples";


	function  Pr_pedido_multiple($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pr_pedido_multiples
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pr_pedido_multiple($id)
	{
		// Create a temporary user object
		$u = new Pr_pedido_multiple();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pedidos()
	{
		// Create a temporary user object
		$e = new Pr_pedido_multiple();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_pr_pedido_multiples_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario from pr_pedido_multiples as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id where pr.cpr_estatus_pedido_id !='4' order by pr.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedido_multiples_listado_ubicacion($offset, $per_page, $ubicacion){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario from pr_pedido_multiples as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id where u.espacio_fisico_id='$ubicacion'  order by pr.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_pedido_multiples_list_auth($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario from pr_pedido_multiples as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id where pr.cpr_estatus_pedido_id='2' and pr.espacio_fisico_id=$ubicacion order by pr.id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedido_multiples_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, pr.fecha_entrega as fecha_entrega_f, e.razon_social as empresa, cp.razon_social as proveedor, cp.telefono, cp.fax, cp.lada, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario from pr_pedido_multiples as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id $where $order_by";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
