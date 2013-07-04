<?php

/**
 * Cl_pedido Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Cl_pedido extends DataMapper {

	var $table= "cl_pedidos";


	function  Cl_pedido($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cl_pedidos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cl_pedido($id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
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
		$e = new Cl_pedido();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	function get_cl_pedidos_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(pr.id) as total from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cclientes as cp on cp.id=pr.cclientes_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id left join ccl_formas_cobro as cpp on cpp.id=pr.ccl_forma_cobro_id left join usuarios as u on u.id=pr.usuario_id where pr.espacio_fisico_id=$ubicacion and pr.ccl_estatus_pedido_id!=6 and pr.ccl_tipo_pedido_id=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as cliente, cp.clave, cpr.tag as estatus, cpp.tag as forma_cobro, u.username as usuario from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cclientes as cp on cp.id=pr.cclientes_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id left join ccl_formas_cobro as cpp on cpp.id=pr.ccl_forma_cobro_id left join usuarios as u on u.id=pr.usuario_id where pr.espacio_fisico_id=$ubicacion and pr.ccl_estatus_pedido_id!=6 and pr.ccl_tipo_pedido_id=1 order by pr.id desc, estatus limit $offset, $per_page ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_list($offset, $per_page, $espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select t.id as traspaso_id, t.traspaso_estatus, pr.*, e.razon_social as empresa, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where t.ubicacion_entrada_id='$espacio_fisico_id' order by traspaso_id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_pedidos_traspasos_list_count($espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(t.id) as total from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id where t.ubicacion_entrada_id='$espacio_fisico_id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_list_salida($offset, $per_page, $espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select t.id as traspaso_id, pr.*, e.razon_social as empresa, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id  where t.ubicacion_salida_id='$espacio_fisico_id' and pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='2' order by pr.fecha_alta desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_pedidos_traspasos_sterm($offset, $per_page, $espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select t.id as traspaso_id,t.traspaso_estatus, pr.*, e.razon_social as empresa, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id  where t.ubicacion_salida_id='$espacio_fisico_id' and pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='3' order by pr.fecha_alta desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_sterm_count($espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(t.id) as filas from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id where t.ubicacion_salida_id='$espacio_fisico_id' and pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='3'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_cl_pedidos_traspasos_list_global($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.id as cl_pedido_id, t.id as traspaso_id, t.traspaso_estatus, pr.*, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='2' and t.ubicacion_salida_id='$ubicacion' order by traspaso_id desc limit $offset, $per_page ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_enviados($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.id as cl_pedido_id, t.id as traspaso_id, t.traspaso_estatus, pr.*, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='3' and t.traspaso_estatus=1 and t.ubicacion_salida_id='$ubicacion' order by traspaso_id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_enviados_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(pr.id) as total from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id where pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='3' and t.traspaso_estatus=1 and t.ubicacion_salida_id='$ubicacion'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}



	function get_cl_pedidos_traspasos_entregados($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.id as cl_pedido_id, t.id as traspaso_id, t.traspaso_estatus, pr.*, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where pr.ccl_tipo_pedido_id='2' and t.traspaso_estatus=2 and t.ubicacion_salida_id='$ubicacion' order by traspaso_id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_entregados_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(pr.id) as total from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id where pr.ccl_tipo_pedido_id='2' and t.traspaso_estatus=2 and t.ubicacion_salida_id='$ubicacion'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_count_traspasos($ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select count(pr.id) as total from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id where pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='2' and t.ubicacion_salida_id='$ubicacion'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_pedidos_list_auth($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as cliente, cpr.tag as estatus, cpp.tag as forma_cobro, u.nombre as usuario from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cclientes as cp on cp.id=pr.cclientes_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id left join ccl_formas_cobro as cpp on cpp.id=pr.ccl_forma_cobro_id left join usuarios as u on u.id=pr.usuario_id where pr.ccl_estatus_pedido_id='2' and pr.ccl_tipo_pedido_id=1 and pr.espacio_fisico_id='$ubicacion'order by cliente, estatus limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_todos($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.id as cl_pedido_id, t.id as traspaso_id, pr.*, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id=3 and t.ubicacion_salida_id='$ubicacion' and t.traspaso_estatus=1 order by traspaso_id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedido_venta($id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select pr.*, e.razon_social as empresa, cp.razon_social as cliente, cp.clave, cpr.tag as estatus, cpp.tag as forma_cobro, u.username as usuario from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join cclientes as cp on cp.id=pr.cclientes_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id left join ccl_formas_cobro as cpp on cpp.id=pr.ccl_forma_cobro_id left join usuarios as u on u.id=pr.usuario_id where pr.id='$id'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_pedidos_traspasos_almacen($offset, $per_page, $espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select t.id as traspaso_id, pr.*, e.razon_social as empresa, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where t.ubicacion_salida_id='$espacio_fisico_id' and pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='2' order by pr.fecha_alta desc limit $offset, $per_page ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_pedidos_traspasos_almacen_count($espacio_fisico_id)
	{
		// Create a temporary user object
		$u = new Cl_pedido();
		$sql="select t.id as traspaso_id, pr.*, e.razon_social as empresa, ef.tag as recibe, cpr.tag as estatus, u.nombre as usuario, ef1.tag as envia from cl_pedidos as pr left join empresas as e on e.id=pr.empresas_id left join ccl_estatus_pedidos as cpr on cpr.id=ccl_estatus_pedido_id  left join usuarios as u on u.id=pr.usuario_id left join traspasos as t on t.cl_pedido_id=pr.id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where t.ubicacion_salida_id='$espacio_fisico_id' and pr.ccl_tipo_pedido_id='2' and pr.ccl_estatus_pedido_id='2' order by pr.fecha_alta desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->c_rows;
		} else {
			return FALSE;
		}
	}

}
