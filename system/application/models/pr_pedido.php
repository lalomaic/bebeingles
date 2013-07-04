<?php

/**
 * Pr_pedido Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pr_pedido extends DataMapper {

	var $table= "pr_pedidos";


	function  Pr_pedido($id=null)
	{
		parent::DataMapper($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Pr_pedidos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_pr_pedido($id)
	{
		// Create a temporary user object
		$u = new Pr_pedido();
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
		$e = new Pr_pedido();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_pr_pedidos_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id where pr.cpr_estatus_pedido_id !='4' order by pr.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedidos_listado_capturados($offset, $per_page){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where pr.cpr_estatus_pedido_id='1' and  es_pedido_pendiente=false order by pr.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedidos_listado_autorizados($offset, $per_page,$estatus=2){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca, es_pedido_pendiente as tipo from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where pr.cpr_estatus_pedido_id=$estatus  order by pr.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedidos_listado_prepedidos($offset, $per_page){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where pr.cpr_estatus_pedido_id='5' and  es_pedido_pendiente=false order by pr.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedidos_listado_count($estatus){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select count(distinct(pr.id)) as total from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where pr.cpr_estatus_pedido_id='$estatus'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->total;
		} else {
			return FALSE;
		}
	}


	function get_pedidos_sucursal($espacio_id,$estatus,$and1=''){
		// Create a temporary user object
		if($estatus>0)
			$and=" and pr.cpr_estatus_pedido_id='$estatus'";
		else
			$and= " ";
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, date(pr.fecha_alta) as fecha_alta, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca, lf.lote_id, pr.es_pedido_pendiente as tipo from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  left join lotes_pr_facturas as lf on lf.pr_pedido_id=pr.id where  pr.espacio_fisico_id=$espacio_id $and $and1 order by pr.id desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pedidos_proveedor($proveedor_id,$estatus){
		// Create a temporary user object
		if($estatus>0)
			$and=" and pr.cpr_estatus_pedido_id='$estatus'";
		else
			$and= " ";
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, date(pr.fecha_alta) as fecha_alta, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, lf.lote_id, pr.es_pedido_pendiente as tipo, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=pr.id where  pr.cproveedores_id=$proveedor_id $and order by pr.id desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_pedidos_marcas($marca_id,$estatus){
		// Create a temporary user object
		if($estatus>0)
			$and=" and pr.cpr_estatus_pedido_id='$estatus'";
		else
			$and= " ";
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where cpr_estatus_pedido_id='$estatus' and pr.cmarca_id=$marca_id $and order by pr.id desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pedidos_pedido_id($id){
		// Create a temporary user object
		$u = new Pr_pedido();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  where pr.id=$id and cpr_estatus_pedido_id=2";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_pedidos_list_auth($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, date(pr.fecha_alta) as fecha_alta, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca, lf.lote_id, pr.es_pedido_pendiente as tipo from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=pr.id  where pr.cpr_estatus_pedido_id='2' order by pr.fecha_entrega asc, proveedor limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_pedidos_listado_ingresados($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, date(pr.fecha_alta) as fecha_alta, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca, lf.lote_id from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=pr.id  where pr.cpr_estatus_pedido_id='3' order by pr.fecha_entrega asc, proveedor limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_pr_pedidos_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, pr.fecha_entrega as fecha_entrega_f, e.razon_social as empresa, cp.razon_social as proveedor, cp.telefono, cp.fax, cp.lada, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario, ef.tag as espacio, m.tag as marca from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id  $where $order_by";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_pedido_entrada($id)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, date(pr.fecha_alta) as fecha_alta, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.nombre as usuario, ef.tag as espacio from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id where pr.id='$id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_pr_pedido_detalles($id)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select pr.*, ef.tag as espacio, c.tag as marca, cp.razon_social from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id left join cmarcas_productos as c on c.id=pr.cmarca_id where pr.id='$id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_rep_pedidos($where){
		// Create a temporary user object
		$u = new Usuario();
		$sql="select distinct(pr.id),pr.*, e.razon_social as empresa, cp.razon_social as proveedor, cpr.tag as estatus, cpp.tag as forma_pago, u.username as usuario, ef.tag as espacio_fisico, m.tag as marca, l.lote_id from pr_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cproveedores as cp on cp.id=pr.cproveedores_id left join cmarcas_productos as m on m.id=pr.cmarca_id left join cpr_estatus_pedidos as cpr on cpr.id=cpr_estatus_pedido_id left join cpr_formas_pago as cpp on cpp.id=pr.cpr_forma_pago_id left join usuarios as u on u.id=pr.usuario_id left join espacios_fisicos as ef on ef.id=pr.espacio_fisico_id left join lotes_pr_facturas as l on l.pr_pedido_id=pr.id $where  order by pr.id desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
