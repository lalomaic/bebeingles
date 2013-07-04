<?php

/**
 * Tipo Poliza_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Poliza_detalle extends DataMapper {

	var $table= "poliza_detalles";

	function  Poliza_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Poliza_detalle
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_poliza_detalles()
	{
		// Create a temporary user object
		$e = new Poliza_detalle();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_poliza_detalle($id)
	{
		// Create a temporary user object
		$u = new Poliza_detalle();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}


	function get_poliza_detalles_ingreso($offset, $per_page, $empresa)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select pi.*, ef.tag as espacio from poliza_detalles as pi left join espacios_fisicos as ef on ef.id=pi.espacio_fisico_id where pi.estatus_general_id=1 and empresa_id='$empresa' and ctipo_poliza_id=1 order by fecha desc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	 
	function get_poliza_detalles_by_poliza($poliza_id)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select pi.id as poliza_id1, pi.*, cc.*, c.id as cobro_id, c.cl_factura_id, cd.id as deposito from poliza_detalles as pi left join ccuentas_contables as cc on cc.id=pi.cuenta_contable_id left join cobros as c on c.poliza_detalle_id=pi.id left join control_depositos as cd on cd.poliza_detalle_id=pi.id where pi.poliza_id='$poliza_id' order by poliza_id1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	 
	//Funcion para verificar el debe y haber de polizas de diario
	function get_debe_haber_poliza($id)
	{
		// Create a temporary user object
		$u = new Poliza_detalle();
		$u->select("sum(debe) as debe, sum(haber) as haber");
		$u->where('poliza_id', $id);
		$u->get();
		if($u->c_rows ==1){
			return $u;
		} else {
			$u->haber=0;
			$u->debe=0;
			return $u;
		}
	}
	function get_poliza_detalles_by_poliza_diario($poliza_id)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select pi.id as poliza_id1, pi.*, cc.*, cc.cta as cta, cc.tag as subcuenta from poliza_detalles as pi left join ccuentas_contables as cc on cc.id=pi.cuenta_contable_id where pi.poliza_id='$poliza_id' order by poliza_id1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	
	 function get_poliza_detalles_by_poliza_egreso($poliza_id) {
        // Create a temporary user object
        $u = new Entrada();
        $sql =
        "select
            pi.id as poliza_id1,
            pi.*,
            cc.*,
            c.id as cobro_id,
            c.cl_factura_id,
            cd.id as deposito,
            cd.referencia
        from poliza_detalles as pi
            left join ccuentas_contables as cc
                on cc.id=pi.cuenta_contable_id
            left join cobros as c
                on c.poliza_detalle_id=pi.id
            left join control_depositos as cd
                on cd.poliza_detalle_id=pi.id
        where
            pi.poliza_id='$poliza_id'
        order by cc.cta, cc.scta, cc.sscta, cc.sscta";
        //Buscar en la base de datos cc.cta, cc.scta, cc.sscta, cc.ssscta, pi.debe ASC, pi.haber ASC
        $u->query($sql);
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

}
