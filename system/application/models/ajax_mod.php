<?php

/**
 * Modelo para Escribir el menu en la IGU fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Ajax_mod extends Model{


	function Ajax_mod()
	{
		parent::Model();
		$this->load->model( 'producto' );
		$this->load->model( 'pr_factura' );
	}

	/**Obtener los precios del producto especificado*/
	function get_select_precios($id){
		$pr=new Producto();
		$pr->select('precio1, precio2, precio3, precio4, tasa_impuesto');
		$pr->where('id', "$id");
		$pr->get();
		if($pr->c_rows==1){
			return $pr;
		} else {
			return FALSE;
		}
	}


	function get_pr_facturas_select($id,$pr_factura_id=0){
		
		 if($pr_factura_id>0){
			$factura_str=" and prf.id!=$pr_factura_id ";
            }else
			$factura_str= " ";
		
		$p= new Pr_factura();
		$sql="select prf.id, prf.folio_factura, prf.monto_total from pr_facturas as prf left join pr_pedidos as pr on pr.id=prf.pr_pedido_id where prf.cproveedores_id='$id' and prf.estatus_general_id='1' and prf.usuario_validador_id>0 and prf.estatus_factura_id='2' $factura_str order by prf.folio_factura";
		$p->query($sql);
		if($p->c_rows > 0){
			return $p;
		} else {
			return FALSE;
		}
	}
	function get_cl_facturas_select($id){
		$p= new Pr_factura();
		$sql="select clf.id, clf.folio_factura, clf.monto_total from cl_facturas as clf left join cl_pedidos as cl on cl.id=clf.cl_pedido_id where clf.cclientes_id='$id' and clf.estatus_general_id='1' and clf.estatus_factura_id=2 order by clf.folio_factura";
		$p->query($sql);
		if($p->c_rows > 0){
			return $p;
		} else {
			return FALSE;
		}
	}

	function get_cl_facturas_dev($id){
		$p= new Salida();
		$sql="select distinct(e.cl_facturas_id), e.id as id1, prf.cl_pedido_id, e.fecha, sum(costo_total) as importe_factura, pr.razon_social as cliente, prf.folio_factura, ef.tag as espacio_fisico, eg.tag as estatus from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and e.cclientes_id=$id and ctipo_salida_id=1 group by e.cl_facturas_id, e.cclientes_id order by prf.folio_factura desc";
		$p->query($sql);
		if($p->c_rows > 0){
			return $p;
		} else {
			return FALSE;
		}
	}
	function get_pr_facturas_dev($id){
		$p= new Entrada();
		$sql="select distinct(e.pr_facturas_id), e.id as id1, prf.pr_pedido_id, e.fecha, sum(costo_total) as importe_factura, pr.razon_social as proveedor, prf.folio_factura, ef.tag as espacio_fisico, eg.tag as estatus from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and e.cproveedores_id=$id and ctipo_entrada=1 group by e.pr_facturas_id, e.cproveedores_id order by prf.id desc ";
		$p->query($sql);
		if($p->c_rows > 0){
			return $p;
		} else {
			return FALSE;
		}
	}

	function get_marcas_select($proveedor_id)
	{
		// Create a temporary user object
		$e = new Marca_producto();
		$e->select('id, tag');
		$e->where('estatus_general_id', "1")->where('proveedor_id', $proveedor_id)->where('id >',0);
		$e->order_by('tag asc');
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}
}
