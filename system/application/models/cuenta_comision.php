<?php
class Cuenta_comision extends DataMapper {

	var $table= "comisiones_bancarias";


	function  Cuenta_comision($id=null)
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
		$e = new Cuenta_comision();

		//Buscar en la base de datos
		$e->where('estatus_general_id',1)->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cuentas_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Cuenta_bancaria();
		$sql="select cb.*,b.tag as banco from comisiones_bancarias as cb 
left join bancos as b on b.id=cb.banco_id  
where cb.estatus_general_id=1 limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


}

?>
