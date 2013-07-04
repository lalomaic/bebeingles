<?php

/**
 * Espacio_fisico Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Espacio_fisico extends DataMapper {

	var $table= "espacios_fisicos";
	var $has_one= array("empresa");


	function  Espacio_fisico($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
	function get_espacios_f()
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', "1");
		$ef->order_by("tag asc");
		//Buscar en la base de datos
		$ef->get();
		if($ef->c_rows>0){
	  return $ef;
		} else {
	  return FALSE;
		}
	}

	function get_espacios_f_tag($id)
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();
		//Buscar en la base de datos
		$ef->get_by_id($id);
		if($ef->c_rows==1){
			return $ef->tag;
		} else {
			return FALSE;
		}
	}


	/**
	 * Espacios Fisicos
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_espacio_f($id)
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();

		//Buscar en la base de datos
		$ef->get_by_id($id);
		if($ef->c_rows>0){
	  return $ef;
		} else {
	  return FALSE;
		}
	}

	function get_espacios_f_pdf($where, $order)
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();
		$sql="select ef.*, e.razon_social as empresa, te.tag as tipo_espacio, ce.tag as estado, cm.tag as municipio from espacios_fisicos as ef left join empresas as e on e.id=ef.empresas_id left join tipos_espacios as te on te.id=ef.tipo_espacio_id left join cestados as ce on ce.id=ef.estado_id left join cmunicipios as cm on cm.id=ef.municipio_id $where $order";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}
	function get_espacios_f_list($offset, $per_page)
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();
		$sql="select ef.*, e.razon_social as empresa, te.tag as tipo_espacio from espacios_fisicos as ef left join empresas as e on e.id=ef.empresas_id left join tipos_espacios as te on te.id=ef.tipo_espacio_id limit $per_page offset $offset";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}
	function get_espacios_by_empresa_id($id)
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('empresas_id', "$id");
		$ef->where('tipo_espacio_id', '2');
		//Buscar en la base de datos
		$ef->get();
		if($ef->c_rows>0){
	  return $ef;
		} else {
	  return FALSE;
		}
	}
	function get_total_espacios_by_empresa_id($id)
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('empresas_id', "$id");
		$ef->where('tipo_espacio_id <', '3');
		$ef->get();
		if($ef->c_rows>0)
			return $ef->c_rows;
		else
			return 0;

	}
	
	
 function get_espacios_by_empresa_count($fecha_poliza) {
        // Create a temporary user object
        $query_con = $this->db->query(
                "SELECT
                    COUNT(*) as consecutivo
                FROM
                    espacios_fisicos
                WHERE
                    cuenta_contable_id > 0
                    AND fecha_afecta < '$fecha_poliza'
                    AND empresas_id = {$GLOBALS['empresa_id']}
                    AND estatus_general_id = 1;")->row();
        return $query_con->consecutivo;
    }	
	

	function get_cuenta_contable($ubicacion)
	{
		// Create a temporary user object
		$ef = new Espacio_fisico();
		$ef->select("cuenta_contable_id");
		$ef->where('id', "$ubicacion");
		//Buscar en la base de datos
		$ef->get();
		if($ef->c_rows== 1 ){
	  return $ef->cuenta_contable_id;
		} else {
	  return FALSE;
		}
	}

	function get_espacios_almacenes()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id', 1)->order_by("tag asc");
		$ef->get();
		if($ef->c_rows>0)
			return $ef;
		else
			return 0;
	}
	function get_espacios_tiendas()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0)
			return $ef;
		else
			return 0;
	}
	function get_espacios_tiendas_oficinas()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id >', 1);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0)
			return $ef;
		else
			return 0;
	}
	
		function get_espacios_tiendas_oficinas_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id >', 1);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			foreach($ef->all as $row){
				$colect['0']="Todas";
				$colect[$row->id]=$row->tag;
			}
			return $colect;
		} else
			return 0;
	}
	

	function get_espacios_tiendas_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id', 2);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			return $colect;
		} else
			return 0;
	}
	function get_espacios_tiendas_almacenes_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id !=', 3);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			return $colect;
		} else
			return 0;
	}
	function get_tiendas_almacenes_mtrx($id)
	    {
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Espacio_fisico();
		$ef->where('estatus_general_id', 1);
		$ef->where('tipo_espacio_id !=', 3);
		$ef->where('id !=', $id);
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
		    foreach($ef->all as $row){
		        $colect[$row->id]=$row->tag;
		    }
		    return $colect;
		} else
		    return 0;
	    }

	function get_espacios_as_array() {
		$espacios = new Espacio_fisico();
		$espacios->
		order_by('tag')->get();
		$array_tienda = array();
		foreach($espacios as $espacio)
			$array_tienda[$espacio->id] = $espacio->tag;
		return $array_tienda;
	}
	function get_tiendas_dropd() {
		$espacios = new Espacio_fisico();
		$espacios->
		where('estatus_general_id', 1)->
		where('tipo_espacio_id', 2)->order_by('tag')->get();
		$array_tienda[0] = "Elija";
		foreach($espacios as $espacio)
			$array_tienda[$espacio->id] = $espacio->tag;
		return $array_tienda;
	}
        
        function get_espacios_dropd() {
            $espacios = new Espacio_fisico();
            $espacios->
            where('estatus_general_id', 1)->
            order_by('tag')->get();
            $array_tienda[0] = "Elija";
            foreach($espacios as $espacio)
		$array_tienda[$espacio->id] = $espacio->tag;
            return $array_tienda;
	}
}
