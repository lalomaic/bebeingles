<?php

/**
 * Pr_factura Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pr_factura extends DataMapper {

	var $table= "pr_facturas";


	function  Pr_factura($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Pr_facturas
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pr_factura($id)
	{
		// Create a temporary user object
		$u = new Pr_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_factura_entrada($id)
	{
		// Create a temporary user object
		$u = new Pr_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->cproveedores_id;
		} else {
			return FALSE;
		}
	}

	function get_pr_facturas()
	{
		// Create a temporary user object
		$e = new Pr_factura();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

function get_monto_factura_id($factura_id)
	{
		// Create a temporary user object
		$e = new Pr_factura();
		$e->select("monto_total");
		$e->where("id", $factura_id);
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows==1){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_pr_facturas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, e.razon_social as empresa, cp.razon_social as proveedor, u.nombre as usuario from pr_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cproveedores as cp on cp.id=prf.cproveedores_id left join usuarios as u on u.id=prf.usuario_id order by prf.fecha_captura desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_facturas_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, prf.pr_pedido_id as pedido_id, e.razon_social as empresa, cp.razon_social as proveedor, u.nombre as usuario, ep.tag as espacio, ef.tag as estatus_factura from pr_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cproveedores as cp on cp.id=prf.cproveedores_id left join usuarios as u on u.id=prf.usuario_id left join espacios_fisicos as ep on ep.id=prf.espacio_fisico_id left join estatus_facturas as ef on ef.id=prf.estatus_factura_id $where $order_by";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_factura_pdf($id)

	{
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select f.*, f.id as factura_id,e.razon_social as empresa, cp.razon_social as proveedor, u.nombre as usuario,ep.tag as espacio, ef.tag as estatus_factura from pr_facturas as f left join empresas as e on e.id=f.empresas_id left join cproveedores as cp on cp.id=f.cproveedores_id left join usuarios as u on u.id=f.usuario_id  left join espacios_fisicos as ep on ep.id=f.espacios_fisicos_id left join estatus_facturas as ef on ef.id=f.estatus_factura_id where f.id=$id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_facturas_xpagar($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, e.razon_social as empresa, cp.razon_social as proveedor, cp.dias_credito, u.username as usuario from pr_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cproveedores as cp on cp.id=prf.cproveedores_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2 order by prf.id limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_facturas_xpagar_count()
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select count(prf.id) as total from pr_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cproveedores as cp on cp.id=prf.cproveedores_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_factura_by_fecha($fecha)
	{
		// Create a temporary user object
		$u = new Pr_factura();
		$sql="select f.*, c.razon_social from pr_facturas as f left join cproveedores as c on c.id=f.cproveedores_id where f.estatus_general_id=1 and fecha='$fecha'";
		$u->query($sql);
		//Buscar en la base de datos
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_facturas_xpagar_verificacion()
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, e.razon_social as empresa, cp.razon_social as proveedor, cp.dias_credito, u.username as usuario from pr_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cproveedores as cp on cp.id=prf.cproveedores_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2 order by prf.id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}

	function get_pr_facturas_xpagar_proveedor($proveedor_id) {
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.id, prf.folio_factura, ( prf.monto_total - prf.descuento ) as monto_total from pr_facturas as prf left join pr_pedidos as pr on pr.id=prf.pr_pedido_id where prf.cproveedores_id='$proveedor_id' and prf.estatus_general_id='1' and pr.cpr_estatus_pedido_id='3' and prf.estatus_factura_id=2 order by prf.folio_factura";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}



}
