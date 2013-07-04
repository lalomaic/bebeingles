<?php
/**
 * Cuenta Bancaria Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Ctipo_deuda_tienda extends DataMapper {

	var $table= "ctipo_deuda_tienda";


	function  Ctipo_deuda_tienda($id=null)
	{
		parent::DataMapper($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Cuentas Bancarias
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_ctipo_deuda_tiendas() {
		$e = new Ctipo_deuda_tienda();
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function ctipo_deuda_tiendas_mtrx(){
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Ctipo_deuda_tienda();
		$ef->order_by('tag')->get();
		if($ef->c_rows>0){
			$colect[0]="Elija";
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			return $colect;
		} else
			return 0;
	}

	function get_ctipo_deuda_tiendas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Ctipo_deuda_tienda();
		$sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa from cctipo_deuda_tiendas as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id left join empresas as e on e.id=cb.empresa_id order by banco limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ctipo_deuda_tienda($id)
	{
		// Create a temporary user object
		$u = new Ctipo_deuda_tienda();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_ctipo_deuda_tiendas_prov($prov_id)
	{
		// Create a temporary user object
		$u = new Ctipo_deuda_tienda();
		//Buscar en la base de datos
		$u->where("cproveedor_id", $prov_id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_ctipo_deuda_tiendas_cliente($prov_id)
	{
		// Create a temporary user object
		$u = new Ctipo_deuda_tienda();
		//Buscar en la base de datos
		$u->where("ccliente_id", $prov_id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ctipo_deuda_tiendas_empresa($id)
	{
		// Create a temporary user object
		$u = new Ctipo_deuda_tienda();
		//Buscar en la base de datos
		$u->where("empresa_id", $id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

}