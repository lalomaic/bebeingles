<?php

/**
 * Tipo Entrada Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramírez SARS
 * @link
 */
class Entrada extends DataMapper {

	var $table= "entradas";
	var $has_one = array(
			'cproductos' => array(
					'class' => 'producto',
					'other_field' => 'entrada'
			),
			'espacios_fisicos' => array(
					'class' => 'espacio_fisico',
					'other_field' => 'entrada'
			),
			'cproducto_numero' => array(
					'class' => 'producto_numeracion',
					'other_field' => 'entradas'
			)
	);
	function  Entrada($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo Entrada
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_entradas()
	{
		// Create a temporary user object
		$e = new Entrada();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_entradas_validadas($offset, $per_page, $auth,$espacio)
	{
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, e.lote_id, cel.tag as estatus_traspaso ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 $validacion and prf.espacios_fisicos_id=$espacio".
	
                        "group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, e.lote_id, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
        
       
        function get_entradas_list($offset, $per_page, $auth,$espacio)
	{
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, e.lote_id, cel.tag as estatus_traspaso ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where prf.estatus_general_id=1 and ctipo_entrada=1 $validacion and e.espacios_fisicos_id=$espacio ".
	
                        "group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, e.lote_id, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
        

	function get_entradas_proveedor_list($id) {
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatuse, e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and e.cproveedores_id='$id'".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id,e.lote_id, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entradas_sucursal_list($id) {
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatuse,e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and usuario_validador_id>0".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, e.lote_id , cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entradas_marcas_list($id) {
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus,e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and prf.cmarca_id='$id'".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, e.lote_id , cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_count_validas($auth,$espacio)
	{
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=1 $validacion and e.espacios_fisicos_id=$espacio";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}
        
        
        
        function get_count_entradas($auth,$espacio)
	{
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where prf.estatus_general_id=1 and ctipo_entrada=1 $validacion and e.espacios_fisicos_id=$espacio";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}
        
        
        

	function get_entrada($id)
	{
		// Create a temporary user object
		$u = new Entrada();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	
	function get_entrada_pr_factura($pr_factura_id,$auth)
	{
		// Create a temporary user object
		if($auth==0){
			$validacion="and e.estatus_general_id=2 ";
		} else {
			$validacion=" and e.estatus_general_id=1 ";
		}
		$u = new Entrada();
		$sql="select e.id as entrada_id, e.*, e.tasa_impuesto as iva, pr.razon_social as proveedor, cp.*, prf.folio_factura, cte.tag as tipo_entrada, ef.tag as espacio_fisico, eg.tag as estatus, um.tag as unidad_medida, pn.numero_mm, pn.codigo_barras, pcol.tag as colores from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join ctipos_entradas as cte on cte.id=e.ctipo_entrada left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id left join cproductos as cp on cp.id=e.cproductos_id left join cunidades_medidas as um on um.id=cp.cunidad_medida_id left join cproductos_numeracion as pn on e.cproducto_numero_id=pn.id left join cproductos_color as pcol on pcol.id=cp.ccolor_id where pr_facturas_id='$pr_factura_id' $validacion order by cp.descripcion asc, pn.numero_mm asc";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	
	function get_entrada_pr_pedido_id($id){
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatuse, e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and prf.pr_pedido_id='$id'".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id,e.lote_id, cel.tag  ".
				"order by e.pr_facturas_id desc,e.fecha desc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_entrada_pr_factura_id($id,$auth){
		// Create a temporary user object
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
			
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatuse, e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and prf.id='$id' $validacion".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id,e.lote_id, prf.descuento, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_entrada_lote_id($id,$auth){
		// Create a temporary user object
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 ";
		}
			
		$u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(e.fecha) fecha, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatuse, e.lote_id, cel.tag as estatus_traspaso  ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=1 and lf.lote_id='$id' $validacion".
				"group by e.pr_facturas_id, e.id, e.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id,e.lote_id, prf.descuento, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc ";
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_entradas_boni_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id), e.id as id1, e.fecha, sum(costo_total) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.espacios_fisicos_id=$ubicacion and e.estatus_general_id=1 and ctipo_entrada=9 group by e.id, e.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id  order by e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_entradas_boni_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.espacios_fisicos_id=$ubicacion and e.estatus_general_id=1 and ctipo_entrada=9";
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
		$u = new Entrada();
		//Buscar en la base de datos
		$u->select("costo_unitario");
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->costo_unitario;
		} else {
			return 0;
		}
	}

 function get_inventario_inicial($offset,$per_page,$espacio){
            $u = new Entrada();
		$sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(prf.fecha)fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, e.lote_id, cel.tag as estatus_traspaso ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
				"left join estatus_general as eg on eg.id=e.estatus_general_id ".
				"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
				"where e.estatus_general_id=1 and ctipo_entrada=6 and e.espacios_fisicos_id=$espacio ".
	
                        "group by e.pr_facturas_id, e.id, prf.fecha, importe_factura, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, e.lote_id, cel.tag ".
				"order by e.pr_facturas_id desc,e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}           
            
        }

   function get_inventario_inicial_count($espacio){
            $u = new Entrada();
            $sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=6 and e.espacios_fisicos_id=$espacio";
            
            
                 $u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}         
            
        }

	function get_entradas_general_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id), e.id as id1, e.fecha, date(e.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=1 group by e.id, e.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable, prf.descuento order by e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_entradas_general_list_count()
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_entradas_boni_gral_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id), e.id as id1, e.fecha, sum(costo_total) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where  e.estatus_general_id=1 and ctipo_entrada=9 group by e.id, e.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id  order by e.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_entradas_boni_gral_list_count($ubicacion)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=9";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_entradas_by_factura($factura_id) {
		// Create a temporary user object
		$u = new Entrada();
		$sql="select e.id as id1, e.*, um.tag as unidad_medida, cp.* from entradas as e left join cproductos as cp on cp.id=e.cproductos_id left join cunidades_medidas as um on um.id=cp.cunidad_medida_id where pr_facturas_id='$factura_id'  order by cp.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	 
	function get_entrada_by_cproducto($id){
		$d=new Entrada();
		$d->where('cproductos_id',$id)->limit(1)->get();
		if($d->crows==1)
			return true;
		else
			return false;
	}

	/** Funciones para modulo de contabilidad listados de compras */
	function get_entradas_contabilidad_list($offset, $per_page,$auth)
	{
		if($auth==0){
			$validacion="and prf.usuario_validador_id=0 and prf.validacion_contable=0 ";
		} else {
			$validacion=" and prf.usuario_validador_id>0 and prf.validacion_contable=1 ";
		}
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e ".
		"left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id ".
		"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
		"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
		"where prf.estatus_general_id=1 and ctipo_entrada=1  $validacion
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag ".
		"order by prf.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
		
	}
	
	
	
	function get_entradas_contabilidad_sin_list($offset, $per_page,$estatus)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e ".
		"left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id ".
		"left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
		"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id ".
		"where prf.estatus_general_id=1 and ctipo_entrada=1 and prf.usuario_validador_id=0
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag ".
		"order by prf.fecha desc limit $per_page offset $offset";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
		
	}
		
	
function get_entradas_contabilidad_sin_list_count($estatus)
	{
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where e.estatus_general_id=1 and ctipo_entrada=1";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}	
	
	
	function get_entradas_contabilidad_list_count($estatus)
	{
	if($estatus==0){
			$validacion=" and prf.estatus_general_id=1 and prf.validacion_contable=0 and usuario_validador_id=0";
		} else {
			$validacion=" and prf.validacion_contable=1 ";
		}		
		
		// Create a temporary user object
		$u = new Entrada();
		$sql="select count(distinct(e.pr_facturas_id)) as total from entradas as e left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id left join estatus_general as eg on eg.id=e.estatus_general_id where ctipo_entrada=1 $validacion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1){
			return $u->total;
		} else {
			return FALSE;
		}
	}

	function get_entradas_contabilidad_sucursal_list($espacio,$estatus)
	{
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e
		left join cproveedores as pr on pr.id=e.cproveedores_id 
		left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id 
		left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id 
		left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id 
		left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id 
		where prf.estatus_general_id=1 and ctipo_entrada=1  and prf.usuario_validador_id=$estatus and prf.espacios_fisicos_id=$espacio
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable, prf.descuento, cel.tag order by prf.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
			return $u;

	}

	function get_entradas_contabilidad_proveedor_list($prov,$estatus)
	{
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e
		left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id 
		left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id 
		where prf.estatus_general_id=1 and ctipo_entrada=1 and prf.usuario_validador_id=$estatus and prf.cproveedores_id=$prov
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag order by prf.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
			return $u;
	
	}

	function get_entradas_contabilidad_marca_list($prov,$estatus)
	{
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e
		left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id 
		left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id  
		where prf.estatus_general_id=1 and ctipo_entrada=1 and prf.usuario_validador_id=$estatus and prf.cmarca_id=$prov
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag order by prf.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}
	function get_entradas_contabilidad_pr_factura_list($pr_factura,$estatus)
	{
		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e
		left join cproveedores as pr on pr.id=e.cproveedores_id left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id 
		left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id 
		where prf.estatus_general_id=1 and ctipo_entrada=1 and prf.usuario_validador_id=$estatus and prf.id=$pr_factura
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag order by prf.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}

	function get_entradas_contabilidad_fecha_list($espacio, $fecha1a, $fecha2a,$estatus)
	{
		//Fecha
		if($fecha1a=="" and strlen($fecha2a)>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2a=="" and strlen($fecha1a)>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		if($espacio>0)
			$espacio_str=" and prf.espacios_fisicos_id=$espacio";
		else
			$espacio_str='';

		$u = new Entrada();
		$sql="select distinct(e.pr_facturas_id) as pr_facturas_id, prf.fecha, date(prf.fecha) as fecha, prf.fecha_pago, ( prf.monto_total - prf.descuento ) as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, m1.tag as marca, prf.validacion_contable, cel.tag as estatus_traspaso
		from entradas as e
		left join cproveedores as pr on pr.id=e.cproveedores_id 
		left join pr_facturas as prf on prf.id=e.pr_facturas_id 
		left join cmarcas_productos as m1 on m1.id=prf.cmarca_id 
		left join espacios_fisicos as ef on ef.id=prf.espacios_fisicos_id 
		left join estatus_general as eg on eg.id=e.estatus_general_id 
		left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id 
		where prf.estatus_general_id=1 and ctipo_entrada=1 and prf.usuario_validador_id=$estatus and prf.fecha>='$fecha1' and prf.fecha<'$fecha2'
		group by e.pr_facturas_id, prf.fecha, pr.razon_social, prf.folio_factura, prf.pr_pedido_id, ef.tag, eg.tag,  e.pr_facturas_id, e.cproveedores_id, m1.tag, prf.fecha_pago, prf.monto_total, prf.validacion_contable,prf.descuento, cel.tag order by prf.fecha desc";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}

	function get_entradas_by_lote_cproducto($lote_id, $cproducto_id){
		$e=new Entrada();
		$sql="select costo_unitario from entradas as e where lote_id=$lote_id and cproductos_id=$cproducto_id and ctipo_entrada=1 and estatus_general_id=1 limit 1";
		$e->query($sql);
		if($e->c_rows > 0)
			return $e;
		else
			return FALSE;
	}
	function get_precio_compra($producto_id){
		$e=new Entrada();
		$e->select('costo_unitario')->where('cproductos_id', $producto_id)->where('ctipo_entrada', 1)->where('estatus_general_id',1)->limit(1)->get();
		if($e->c_rows==1)
			return $e;
		else
			return false;
	}

	// 	//Usada por supervision_reportes para rep_ajuste_pdf
	//     function get_precio_unitario($id) {
	//         // Create a temporary user object
	//         $u = new Entrada();
	//         //Buscar en la base de datos
	//         $u->select("costo_unitario");
	//         $u->get_by_id($id);
	//         if ($u->c_rows == 1) {
	//             return $u->costo_unitario;
	//         } else {
	//             return 0;
	//         }
	//     }

	//Funcion para obtener el total de pares de una factura de proveedor
	function get_pares_by_pr_factura($id){
		$e=new Entrada();
		$e->select("sum(cantidad) as total")->where('pr_facturas_id', $id)->where('estatus_general_id',1)->get();
		return $e->total;
	}
	}

