<?php

/**
 * Cl_factura Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Cl_factura extends DataMapper {
	var $table= "cl_facturas";


	function  Cl_factura($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cl_facturas
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_cl_facturas()
	{
		// Create a temporary user object
		$e = new Cl_factura();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_cl_facturas_list($offset, $per_page) {
		// Create a temporary user object
		$u = new Usuario();
		$sql="select prf.*, cp.razon_social as cliente, u.nombre as usuario, estatus.tag as estatus_factura, espacio.tag as espacio 
                    from cl_facturas as prf 
                    left join cclientes as cp on cp.id=prf.cclientes_id 
                    left join usuarios as u on u.id=prf.usuario_id 
                    left join estatus_facturas as estatus on estatus.id=prf.estatus_factura_id
                    left join espacios_fisicos as espacio on espacio.id=prf.espacios_fisicos_id
                    order by prf.fecha_captura desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

function buscar_cl_facturas($args){
            $and = "where true ";
            $and .= $args['espacio'] != 0 ? " and espacio.id = ".$args['espacio'] : ""; 
            $and .= $args['cliente'] != "" ? " and cp.razon_social like '%".strtoupper($args['cliente'])."%'" : ""; 
            $and .= $args['fecha'] != "" ? " and prf.fecha = '".str_replace(" ","-",$args['fecha'])."'" : ""; 
            $and .= $args['folio'] != "" ? " and prf.folio_factura = '".$args['folio']."'" : ""; 
            $and .= $args['serie'] != "" ? " and prf.serie_factura = '".$args['serie']."'" : ""; 
            
            // Create a temporary user object
            $u = new Usuario();
            $sql="select prf.*, cp.razon_social as cliente, u.nombre as usuario, estatus.tag as estatus_factura, espacio.tag as espacio 
                from cl_facturas as prf 
                left join cclientes as cp on cp.id=prf.cclientes_id 
                left join usuarios as u on u.id=prf.usuario_id 
                left join estatus_facturas as estatus on estatus.id=prf.estatus_factura_id
                left join espacios_fisicos as espacio on espacio.id=prf.espacios_fisicos_id
                $and
                order by prf.fecha_captura desc";
            //Buscar en la base de datos
            return $u->query($sql);            
        }
                
        function get_cl_facturas_count(){
            $f = new Cl_factura();
            return $f->count();
        }
        
        function get_cl_factura_salidas($factura_id){
            // Create a temporary user object
            $u = new Cl_factura();
            $sql="select s.cantidad,s.costo_unitario,s.costo_total, p.descripcion, pn.numero_mm, medida.tag as medida 
                from salidas as s 
                left join cproductos as p on p.id = s.cproductos_id 
                left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id 
                left join cunidades_medidas as medida on medida.id = p.cunidad_medida_id
                where s.cl_facturas_id=$factura_id and s.estatus_general_id = 1";
            //Buscar en la base de datos
            return $u->query($sql);
        }
        
        function get_folio_certificado($espacio_fisico_id){
            // Create a temporary user object
            $u = new Cl_factura();
            $sql="select * 
                from facturas_folios                
                where espacios_fisicos_id = $espacio_fisico_id";
            //Folio Certificado
            return $u->query($sql);
        }
    
	function get_cl_facturas_tienda($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select f.*, f.id as factura_id, e.razon_social as empresa, cp.razon_social as cliente, u.username as usuario, eg.tag as estatus from cl_facturas as f left join empresas as e on e.id=f.empresas_id left join cclientes as cp on cp.id=f.cclientes_id left join usuarios as u on u.id=f.usuario_id left join estatus_general as eg on eg.id=f.estatus_general_id where f.espacios_fisicos_id=$ubicacion order by factura_id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_facturas_tienda_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select count(f.id) as total from cl_facturas as f left join cortes_diarios as c on c.factura_id=f.id left join empresas as e on e.id=f.empresas_id left join cclientes as cp on cp.id=f.cclientes_id left join usuarios as u on u.id=f.usuario_id where f.espacios_fisicos_id=$ubicacion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_factura($id)

	{
		// Create a temporary user object
		$u = new Cl_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_factura_pdf($id)

	{
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select f.*, f.id as factura_id,e.razon_social as empresa, cp.razon_social as cliente, u.nombre as usuario from cl_facturas as f left join empresas as e on e.id=f.empresas_id left join cclientes as cp on cp.id=f.cclientes_id left join usuarios as u on u.id=f.usuario_id where f.id=$id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_factura_salida($id)
	{
		// Create a temporary user object
		$u = new Cl_factura();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->cclientes_id;
		} else {
			return FALSE;
		}
	}
	function get_cl_facturas_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select clf.*, e.razon_social as empresa, cl.clave as cliente, u.username as usuario from cl_facturas as clf left join empresas as e on e.id=clf.empresas_id left join cclientes as cl on cl.id=clf.cclientes_id left join usuarios as u on u.id=clf.usuario_id $where $order_by ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_cl_factura_by_pedido($id)
	{
		// Create a temporary user object
		$u = new Cl_factura();
		$u->where('cl_pedido_id', $id);
		$u->where("estatus_general_id", 1);
		//Buscar en la base de datos
		$u->get();
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_facturas_xcobrar($offset, $per_page) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select prf.*, e.razon_social as empresa, cp.razon_social as cliente, cp.dias_credito, u.username as usuario from cl_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cclientes as cp on cp.id=prf.cclientes_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2 and prf.estatus_general_id=1 order by prf.id limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_facturas_xcobrar_count() {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select count(prf.id) as total from cl_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cclientes as cp on cp.id=prf.cclientes_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2 and prf.estatus_general_id=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cl_facturas_cliente($cliente_id) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select prf.id, prf.monto_total from cl_facturas as prf left join empresas as e on e.id=prf.empresas_id left join cclientes as cp on cp.id=prf.cclientes_id left join usuarios as u on u.id=prf.usuario_id where prf.estatus_factura_id=2 and prf.estatus_general_id=1 and cclientes_id=$cliente_id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_monto_total_poliza($ubicacion, $fecha1) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select sum(monto_total) as total from cl_facturas as f where f.estatus_general_id=1 and f.espacios_fisicos_id=$ubicacion and fecha='$fecha1' and estatus_factura_id!='2'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->total > 0){
			return $u->total;
		} else {
			return 0;
		}
	}
	function get_monto_total_poliza_credito($ubicacion, $fecha1) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select sum(monto_total) as total from cl_facturas as f where f.estatus_general_id=1 and f.espacios_fisicos_id=$ubicacion and fecha='$fecha1' and estatus_factura_id='2'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->total > 0){
			return $u->total;
		} else {
			return 0;
		}
	}

	function get_iva_total($ubicacion, $fecha1) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select sum(iva_total) as total from cl_facturas as f where f.estatus_general_id=1 and f.espacios_fisicos_id=$ubicacion and fecha='$fecha1' and estatus_factura_id='1'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return 0;
		}
	}

	function get_iva_total_credito($ubicacion, $fecha1) {
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select sum(iva_total) as total from cl_facturas as f where f.estatus_general_id=1 and f.espacios_fisicos_id=$ubicacion and fecha='$fecha1' and estatus_factura_id='2'";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return 0;
		}
	}

 function get_total_contado($ubicacion_id, $fecha) {
        $factura = new Cl_factura();
        $factura->query(
                "SELECT
                    SUM(monto_total-iva_total) AS total,
                    SUM(iva_total) AS iva_total
                FROM
                    cl_facturas
                WHERE
                    fecha = '$fecha'
                    AND espacios_fisicos_id = '$ubicacion_id'
                    AND estatus_factura_id = 1
                    AND estatus_general_id = 1");
        return $factura;
    }


 function get_total_credito($ubicacion_id, $fecha) {
        $factura = new Cl_factura();
        $factura->query(
                "SELECT
                    SUM(monto_total-iva_total) AS total,
                    SUM(
                        IF(estatus_factura_id = 2, iva_total, 0)
                    ) AS iva_total_cred,
                    SUM(
                        IF(estatus_factura_id = 3, iva_total, 0)
                    ) AS iva_total_pagado
                FROM
                    cl_facturas
                WHERE
                    fecha = '$fecha'
                    AND espacios_fisicos_id = '$ubicacion_id'
                    AND estatus_factura_id != 1
                    AND estatus_general_id = 1");
        return $factura;
    }


	function get_cl_factura_by_ubicacion_fecha($ubicacion, $fecha)
	{
		// Create a temporary user object
		$u = new Cl_factura();
		$sql="select f.*, c.razon_social from cl_facturas as f left join cclientes as c on c.id=f.cclientes_id where f.estatus_general_id=1 and f.espacios_fisicos_id=$ubicacion and fecha='$fecha'";
		$u->query($sql);
		//Buscar en la base de datos
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

}
