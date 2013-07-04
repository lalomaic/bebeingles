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
class Cuenta_bancaria extends DataMapper {

	var $table= "ccuentas_bancarias";


	function  Cuenta_bancaria($id=null)
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
	function get_cuentas_bancarias()
	{
		// Create a temporary user object
		$e = new Cuenta_bancaria();
		$e->where('estatus_general_id',1)->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cuentas_bancarias_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa,b.tag as banco_nombre  from ccuentas_bancarias as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id 
		left join empresas as e on e.id=cb.empresa_id
left join bancos as b on b.id=cb.banco		
		 where cb.estatus_general_id=1 order by id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	
	
		function get_cuentas_bancarias_banco()
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa,b.tag as banco_nombre  
		from ccuentas_bancarias as cb 
		left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id 
		left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id 
		left join empresas as e on e.id=cb.empresa_id
left join bancos as b on b.id=cb.banco		
		 where cb.estatus_general_id=1 order by id desc ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
        
               
	function get_cuentas_bancarias_count($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa from ccuentas_bancarias as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id left join empresas as e on e.id=cb.empresa_id where cb.estatus_general_id=1 order by banco";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}
        
function get_cuenta_bancaria($id)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa from ccuentas_bancarias as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id left join empresas as e on e.id=cb.empresa_id where cb.estatus_general_id=1 and cb.id=$id order by banco";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows>0){
			return $u;
		} else {
			return FALSE;
		}
	}        
        
        

	function get_cuentas_bancarias_prov($prov_id)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		//Buscar en la base de datos
		$u->where("cproveedor_id", $prov_id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cuentas_bancarias_cliente($prov_id)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		//Buscar en la base de datos
		$u->where("ccliente_id", $prov_id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cuentas_bancarias_empresa($id)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		//Buscar en la base de datos
		$u->where("empresa_id", $id);
		$u->get();
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cuenta_contable_banco($id)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$u->get_by_id($id);
		if($u->c_rows > 0){
			return $u->cuenta_contable_id;
		} else {
			return FALSE;
		}
	}

}