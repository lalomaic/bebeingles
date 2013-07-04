<?php

/**
 * Producto_numeracion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Producto_numeracion extends DataMapper {

	var $table= "cproductos_numeracion";
	var $has_many = array(
			'entradas' => array(
					'class' => 'entrada',
					'other_field' => 'cproducto_numero'
			)
	);

	function  Producto_numeracion($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
	function get_productos_numeracion()
	{
		// Create a temporary user object
		$ef = new Producto_numeracion();
		$ef->where('estatus_general_id', "1");
		//Buscar en la base de datos
		$ef->get();
		if($ef->c_rows>0){
	  return $ef;
		} else {
	  return FALSE;
		}
	}

	function get_productos_numeracion_tag($id)
	{
		// Create a temporary user object
		$ef = new Producto_numeracion();
		//Buscar en la base de datos
		$ef->get_by_id($id);
		if($ef->c_rows>0){
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
	function get_producto_numeracion($id)
	{
		// Create a temporary user object
		$ef = new Producto_numeracion();

		//Buscar en la base de datos
		$ef->get_by_id($id);
		if($ef->c_rows>0){
	  return $ef;
		} else {
	  return FALSE;
		}
	}

	function get_producto_numeracion_pdf($where, $order)
	{
		// Create a temporary user object
		$ef = new Producto_numeracion();
		$sql="select ef.*, e.razon_social as empresa, te.tag as tipo_espacio, ce.tag as estado, cm.tag as municipio from producto_numeracion as ef left join empresas as e on e.id=ef.empresas_id left join tipos_espacios as te on te.id=ef.tipo_espacio_id left join cestados as ce on ce.id=ef.estado_id left join cmunicipios as cm on cm.id=ef.municipio_id $where $order";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}
	function get_producto_numeracion_list($offset, $per_page)
	{
		// Create a temporary user object
		$ef = new Producto_numeracion();
		$sql="select ef.*, e.razon_social as empresa, te.tag as tipo_espacio from producto_numeracion as ef left join empresas as e on e.id=ef.empresas_id left join tipos_espacios as te on te.id=ef.tipo_espacio_id limit $per_page offset $offset";
		//Buscar en la base de datos
		//die($sql);
		$ef->query($sql);
		if($ef->c_rows > 0){
			return $ef;
		} else {
			return FALSE;
		}
	}

	function get_numeracion_by_producto($id,$p_num)
{
	// Create a temporary user object
	$ef = new Producto_numeracion();
        $sql="select p.id as cproducto_id,p.descripcion as tag, pn.numero_mm as talla, pn.id as    
          numeracion_id
        from
        cproductos as p
        join cproductos_numeracion as pn
        on pn.cproducto_id=p.id
        where
        p.id='$id'
        and
        pn.id='$p_num'
        and
        p.estatus_general_id='1'";
	//Buscar en la base de datos
	//$ef->where('cproducto_id', $id)->where('estatus_general_id',1)->order_by('numero_mm')->ge

	
           $ef->query($sql);
           if($ef->c_rows>0){
		return $ef;
	} else {
		return FALSE;
	}
}

	function get_numeracion_producto($id){
		// Create a temporary user object
		$ef = new Producto_numeracion();

		//Buscar en la base de datos
		$ef->where('cproducto_id', $id)->order_by('numero_mm')->get();
		if($ef->c_rows>0){
			return $ef;
		} else {
			return FALSE;
		}
	}
	function get_producto_id_by_id($id) {
		// Create a temporary user object
		$ef = new Producto_numeracion();
		//Buscar en la base de datos
		$ef->get_by_id($id);
		if ($ef->c_rows > 0) {
			return $ef->cproducto_id;
		} else {
			return FALSE;
		}
	}

	function get_by_clave_anterior($clave) {
		// Create a temporary user object
		$ef = new Producto_numeracion();
		//Buscar en la base de datos
		$ef->where("clave_anterior",$clave)->get();
		if ($ef->c_rows > 0) {
			return $ef->id;
		} else {
			return FALSE;
		}
	}
                
        function get_numeracion_by_prod_val($pid,$val){
            $ef = new Producto_numeracion();
            $ef->where('cproducto_id', $pid);
            $ef->where('numero_mm', $val);
            $ef->get();
            
            if ($ef->c_rows > 0) 
                return $ef->codigo_barras;
            else 
		return 'Sin codigo';
	}
        function check_cod_bar_by_prod($prodId){
            $nn = new Producto_numeracion();
            $nn->where("codigo_barras","");
            $nn->where("cproducto_id",$prodId);
            $n_bar=$nn->get();
            if ($n_bar->c_rows == 0) 
                return true;
            else 
                return false;
		
        }
}
