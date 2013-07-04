<?php

/**
 * Tipo SalidaClass
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Salida extends DataMapper {

	var $table= "salidas";
	var $has_one = array(
			'cproductos' => array(
					'class' => 'producto',
					'other_field' => 'salida'
			),
        'cproducto_numero' => array(
            'class' => 'producto_numeracion',
            'other_field' => 'salidas'
        )
	);
	function  Salida($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Salida
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_salidas()
	{
		// Create a temporary user object
		$e = new Salida();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_salidas_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select 
                    distinct(e.cl_facturas_id),
                    e.id as id1,
                    prf.cl_pedido_id,
                    e.fecha, sum(costo_total) as importe_factura,
                    pr.razon_social as cliente,
                    prf.folio_factura,
                    ef.tag as espacio_fisico,
                    eg.tag as estatus,
                    ts.tag as tipof 
                    from 
                    salidas as e
                    left join cclientes as pr
                    on pr.id=e.cclientes_id 
                    left join cl_facturas as prf
                    on prf.id=e.cl_facturas_id 
                    left join espacios_fisicos as ef
                    on ef.id=e.espacios_fisicos_id 
                    left join estatus_general as eg 
                    on eg.id=prf.estatus_general_id 
                    left join ctipos_salidas as ts 
                    on ts.id=e.ctipo_salida_id 
                    where 
                    e.espacios_fisicos_id=$ubicacion  
                        and 
                        (ctipo_salida_id=1 or ctipo_salida_id=9) 
                        and prf.estatus_general_id=1 
                        group by 
                        e.id, 
                        prf.cl_pedido_id,
                        e.fecha, pr.razon_social, 
                        ef.tag, eg.tag, 
                        prf.folio_factura, 
                        ts.tag, 
                        e.cl_facturas_id, 
                        e.cclientes_id 
                        order by 
                        e.cl_facturas_id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salidas_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select count(distinct(e.cl_facturas_id)) as total from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id and prf.estatus_general_id=1 where e.espacios_fisicos_id=$ubicacion and ctipo_salida_id=1 ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_salida($id)
	{
		// Create a temporary user object
		$u = new Salida();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_salidas_by_factura($factura_id)
	{
		// Create a temporary user object
		$u = new Salida();
		//	    $sql="select e.id as id1, e.*, um.tag as unidad_medida, cp.* from salidas as e left join cproductos as cp on cp.id=e.cproductos_id left join cunidades_medidas as um on um.id=cp.cunidad_medida_id where cl_facturas_id='$factura_id' and e.estatus_general_id='1' order by cp.descripcion";
		$sql="select e.id as id1, e.*, um.tag as unidad_medida, cp.* from salidas as e left join cproductos as cp on cp.id=e.cproductos_id left join cunidades_medidas as um on um.id=cp.cunidad_medida_id where cl_facturas_id='$factura_id'  order by cp.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salidas_canceladas_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select e.id as id1, e.*, prf.cl_pedido_id, pr.razon_social as cliente, prf.folio_factura, ef.tag as espacio_fisico, eg.tag as estatus from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=2 order by e.fecha desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salidas_canceladas_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select count(e.id) as total from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=2";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	//Usada por supervision_reportes para rep_ajuste_pdf
	function get_precio_unitario($id)
	{
		// Create a temporary user object
		$u = new Salida();
		//Buscar en la base de datos
		//	  $u->select("costo_unitario");
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->costo_unitario;
		} else {
			return 0;
		}
	}

	function get_salidas_general_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select distinct(e.cl_facturas_id), e.id as id1, prf.cl_pedido_id, e.fecha, sum(costo_total) as importe_factura, pr.razon_social as cliente,prf.folio_factura, ef.tag as espacio_fisico, eg.tag as estatus from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where ctipo_salida_id=1 group by e.cl_facturas_id, e.cclientes_id order by e.fecha desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_salidas_general_list_count()
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select count(distinct(e.cl_facturas_id)) as total from salidas as e left join cclientes as pr on pr.id=e.cclientes_id left join cl_facturas as prf on prf.id=e.cl_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where ctipo_salida_id=1 ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_salidas_dev_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();
		echo $offset."&";
		$sql="select distinct(s.nota_credito_id), s.id as id1, s.fecha, n.monto_total, n.pr_factura, pr.razon_social as proveedor, f.folio_factura, ef.tag as espacio_fisico, eg.tag as estatus from salidas as s left join notas_credito as n on n.id=s.nota_credito_id left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join estatus_general as eg on eg.id=s.estatus_general_id left join pr_facturas as f on f.id=n.pr_factura left join cproveedores as pr on f.cproveedores_id=pr.id where ctipo_salida_id=7 and s.espacios_fisicos_id='$ubicacion' and s.estatus_general_id=1 group by s.nota_credito_id order by s.fecha desc limit $offset, $per_page ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_salidas_dev_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Salida();

		$sql="select count(distinct(s.nota_credito_id)) as total from salidas as s left join notas_credito as n on n.id=s.nota_credito_id left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join estatus_general as eg on eg.id=s.estatus_general_id left join pr_facturas as f on f.id=n.pr_factura left join cproveedores as pr on f.cproveedores_id=pr.id where ctipo_salida_id=7 and s.espacios_fisicos_id='$ubicacion' and s.estatus_general_id=1 group by s.nota_credito_id";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_monto_compra_marca($espacio, $where)
	{
		// Create a temporary user object
		$u = new Salida();
		$sql="select sum(costo_total*(e.porcentaje_compra)/100) as costo_compra, sum(costo_total) as costo_total from salidas as s left join cproductos as p on p.id=s.cproductos_id left join espacios_fisicos as e on e.id=espacios_fisicos_id $where and s.espacios_fisicos_id=$espacio and ctipo_salida_id=1 and s.estatus_general_id=1  ";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->costo_compra != $u->costo_total ){
			return $u->costo_compra;
		} else {
			$result=$u->costo_total * .4;
			return $result;
		}
	}


	function get_salidas_defectuosas_by_proveedor($proveedor_id){
		$u=new Salida();

		$sql="select s.id, s.lote_id, s.cantidad, s.cproductos_id, s.costo_unitario from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id where s.estatus_general_id=1 and s.devolucion_finiquitada=0 and m.proveedor_id=$proveedor_id and s.lote_id>0 and ctipo_salida_id=6  order by id ";
		// 		echo $sql;
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows>0 )
			return $u;
		else
			return false;
	}
	function get_salidas_defectuosas_by_proveedor_lote0($proveedor_id){
		$u=new Salida();
		$sql="select s.id,  s.cantidad, date(s.fecha) as fecha, s.cproductos_id, p.descripcion as producto from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id where s.estatus_general_id=1 and s.devolucion_finiquitada=0 and m.proveedor_id=$proveedor_id and s.lote_id=0 and ctipo_salida_id=6  order by producto ";
		$u->query($sql);
		if($u->c_rows>0 )
			return $u;
		else
			return false;
	}
	function get_salidas_detalles_devoluciones($salidas,$lote_verificacion){
		//Obtener los detalles de devoluciones de los productos
		if($lote_verificacion==1){
			$verificador=" and s.lote_id>0 ";
		} else {
			$verificador=" and s.lote_id=0 ";
		}
		$s=new Salida();
		$sql="select s.id, s.cantidad, p.descripcion, pn.numero_mm, s.costo_unitario, s.lote_id from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=cproducto_numero_id where s.id in ($salidas) $verificador order by costo_unitario desc ";
		$s->query($sql);
		if($s->c_rows>0){
			foreach($s->all as $s1){
				$regreso[$s1->id]['cantidad']=$s1->cantidad;
				$regreso[$s1->id]['descripcion']=$s1->descripcion;
				$regreso[$s1->id]['numero']=$s1->numero_mm/10;
				$regreso[$s1->id]['costo_unitario']=$s1->costo_unitario;
			}
			return $regreso;
		}  else
			return false;
	}
	function get_rep_devoluciones_defectuosas($proveedor_id, $fecha1, $fecha2, $marca_id, $finiquitada){
		$marca=""; 		$proveedor="";
		$u=new Salida();
		if($proveedor_id>0)
			$proveedor=" and m.proveedor_id=$proveedor_id";
		if($marca_id>0 and $marca_id!="")
			$marca=" and p.cmarca_producto_id=$marca_id";

		$sql="select s.id,  s.cantidad, date(s.fecha) as fecha, s.cproductos_id, p.descripcion as producto, s.lote_id, s.costo_unitario, s.costo_total, ef.tag as espacio from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id where s.estatus_general_id=1 and s.devolucion_finiquitada=$finiquitada $proveedor and ctipo_salida_id=6  $marca and fecha>='$fecha1' and fecha<'$fecha2' order by m.proveedor_id, m.id, producto ";
		$u->query($sql);
		if($u->c_rows>0 )
			return $u;
		else
			return false;
	}

}
