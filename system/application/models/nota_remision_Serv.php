<?php
/**
 * Estatus de Pedido de compra CPR_ESTATUS_PEDIDO Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Nota_remision extends DataMapper {
	var $table= "nota_remision";

	function  Nota_remision($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Nota_remision
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_notas_remision_list($offset, $per_page, $ubicacion)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		$sql="select n.*, ef.tag from nota_remision as n left join espacios_fisicos as ef on ef.id=n.espacios_fisicos_id where n.estatus_general_id='1' and n.espacios_fisicos_id='$ubicacion'  and n.estatus_general_id='1' order by id desc limit $offset, $per_page";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_nota_remision($id)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_nota_remision_factura($id, $ubicacion)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		$u->where('numero_remision', "$id");
		$u->where('espacio_fisico_id', "$ubicacion");

		//Buscar en la base de datos
		$u->get();
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_notas_remision_corte($fecha, $ubicacion, $inicial, $final)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		$sql="select s.id_ubicacion_local, s.espacios_fisicos_id as espacio_fisico_id, n.estatus_general_id, n.numero_remision from nota_remision as n left join salidas as s on s.numero_remision=n.numero_remision where n.estatus_general_id>'0' and n.espacios_fisicos_id='$ubicacion' and n.fecha='$fecha' and n.numero_remision>=$inicial and n.numero_remision<=$final and s.espacios_fisicos_id='$ubicacion' ";


		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows >0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_notas_remision_corte_count($fecha, $ubicacion,$inicial, $final)
	{
		// Create a temporary user object

		$u = new Nota_remision();
		$sql="select count(id) as conteo from nota_remision as n where n.estatus_general_id='1' and n.espacios_fisicos_id='$ubicacion' and n.fecha='$fecha' and numero_remision>=$inicial and numero_remision<=$final";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1 ){
			return $u->conteo;
		} else {
			return FALSE;
		}
	}
	function get_monto_remisiones_corte($fecha, $ubicacion,$inicial, $final)
	{
		// Create a temporary user object

		$u = new Nota_remision();
		$sql="select sum(importe_total) as importe from nota_remision as n where n.estatus_general_id='1' and n.espacios_fisicos_id='$ubicacion' and n.fecha='$fecha' and numero_remision>=$inicial and numero_remision<=$final";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows == 1 ){
			return $u->importe;
		} else {
			return FALSE;
		}
	}

	function get_impuesto_corte($fecha, $ubicacion)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		$sql="select round(sum(s.costo_total*s.tasa_impuesto/(100+s.tasa_impuesto)),4) as iva from nota_remision as n left join salidas as s on s.numero_remision=n.numero_remision where n.estatus_general_id='1' and n.espacios_fisicos_id='$ubicacion' and s.espacios_fisicos_id='$ubicacion' and n.fecha='$fecha' and s.fecha='$fecha' and s.estatus_general_id='1' and s.cl_facturas_id='0'";
		$u->query($sql);
		if($u->c_rows == 1 ){
			return $u->iva;
		} else {
			return FALSE;
		}
	}
	function get_notas_remision_pdf($where, $order_by)
	{
		// Create a temporary user object
		$u = new Nota_remision();
		$sql="select n.*, ef.tag as espacio_fisico, u.username as usuario, eg.tag as estatus from nota_remision as n left join espacios_fisicos as ef on ef.id=n.espacios_fisicos_id left join usuarios as u on u.id=n.usuario_id left join estatus_general as eg on eg.id=n.estatus_general_id $where $order_by ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ventas_empleados($fecha1,$fecha2,$espacio){
		//$espacio=0;
		if($espacio>0){
			$where= " and n.espacios_fisicos_id='$espacio' and s.espacios_fisicos_id='$espacio'";
		} else
			$where = "";
		$where.=" and n.fecha >= '$fecha1' and n.fecha <= '$fecha2'";


		$u = new Salida();
		$sql="select distinct(n.numero_remision_interno) as rem,n.importe_total, cobro_vales, sum(s.costo_total),sum(cantidad) as cantidad, 
		n.empleado_id,s.espacios_fisicos_id,n.espacios_fisicos_id,(e.nombre || ' ' || e.apaterno || ' ' || e.amaterno) as empleado,
		comi.criterio1 from salidas as s 
		left join nota_remision as n on n.numero_remision_interno=s.numero_remision_id 
		left join empleados as e on e.id=n.empleado_id 
		left join comisiones as comi on comi.id=e.comision_id  
		where n.estatus_general_id='1'
		and s.estatus_general_id='1' 
		and ctipo_salida_id='1' and n.nota_apartado_id=0 $where 
		group by n.numero_remision_interno,comi.criterio1, n.importe_total,cobro_vales,n.empleado_id, s.espacios_fisicos_id,n.espacios_fisicos_id, e.nombre, e.apaterno, e.amaterno order by empleado asc ";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	
function get_ventas_empleados_flete($fecha1,$fecha2,$espacio){
		//$espacio=0;
		if($espacio>0){
			$where= " and n.espacios_fisicos_id='$espacio' and s.espacios_fisicos_id='$espacio'";
		} else
			$where = "";
		$where.=" and n.fecha >= '$fecha1' and n.fecha <= '$fecha2'";


		$u = new Salida();
		$sql="select distinct(n.numero_remision_interno) as rem,n.importe_total, cobro_vales, sum(s.costo_total),sum(cantidad) as cantidad, n.empleado_id,s.espacios_fisicos_id,n.espacios_fisicos_id from salidas as s 
left join nota_remision as n on n.numero_remision_interno=s.numero_remision_id 
left join empleados as e on e.id=n.empleado_id 
		where n.estatus_general_id='1'
		and s.estatus_general_id='1' and s.cproductos_id=3524
		and ctipo_salida_id='1' $where  
group by n.numero_remision_interno, n.importe_total,cobro_vales,n.empleado_id, s.espacios_fisicos_id,n.espacios_fisicos_id, e.nombre, e.apaterno, e.amaterno  
 ";

		//Buscar en la base de datos
		$u->query($sql);

			return $u;
	}	
	

	function get_abonos_empleados_vales($fecha1,$fecha2,$espacio){
			//$espacio=0;
			if($espacio>0){
				$where= " and ab.espacio_fisico_id='$espacio' and na.espacio_fisico_id='$espacio' ";
			} else
				$where = "";
			$where.=" and ab.fecha between '$fecha1' and '$fecha2'";

			$u = new Salida();
			$sql="select sum(ab.monto_abono) as monto,empleado_id,na.espacio_fisico_id as espacio_vales from abonos_pv as ab 
	left join nota_apartado as na on na.id_ubicacion_local=ab.nota_apartado_id
	where ab.vale_apartado>0 $where and ab.estatus_general_id=2
	group by na.empleado_id,na.espacio_fisico_id order by espacio_vales,empleado_id asc";

			$u->query($sql);

				return $u;

		}
	
 	function get_abonos_empleados($fecha1,$fecha2,$espacio){
			//$espacio=0;
			if($espacio>0){
				$where= " and ab.espacio_fisico_id='$espacio' and na.espacio_fisico_id='$espacio'";
			} else
				$where = "";
			$where.=" and ab.fecha between '$fecha1' and '$fecha2'";

			$u = new Salida();
			$sql="select sum(ab.monto_abono) as monto,empleado_id,na.espacio_fisico_id as espacio_abonos from abonos_pv as ab 
	left join nota_apartado as na on na.id_ubicacion_local=ab.nota_apartado_id and na.espacio_fisico_id=ab.espacio_fisico_id
	where na.estatus_general_id=1 $where  and ab.estatus_general_id=1
	group by na.empleado_id,na.espacio_fisico_id order by na.espacio_fisico_id,empleado_id asc";

			$u->query($sql);

				return $u;

		}
        
	
	
function get_comision_tienda($espacio){
		$u = new comision();
		$sql="select * from comisiones
where upper(tag) like upper('%comision%') and espacio_fisico_id=$espacio and tipo_comision_id=3";
				//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
		
	}	

	        function get_ventas_gastos_detalles($fecha1,$fecha2,$espacio){
	            if($espacio>0){
	                $where_g =" and g.espacios_fisicos_id='$espacio' ";
	            }else
	                $where_g= "";
	                $where_g.=" and g.fecha>='$fecha1' and g.fecha<='$fecha2'";

	            $sql="select sum(g.monto) as monto,g.espacios_fisicos_id as espacio_gasto from gastos_detalles as g 
	left join cgastos as cg on cg.id=g.cgastos_id
	where 
	g.estatus_general_id=1 
	and ctipo_gasto_id in (1,2)
	$where_g
	group by g.espacios_fisicos_id order by espacio_gasto asc";

	            $this->query($sql);
	return $this;

	        }
	
	function get_ventas($fecha1,$fecha2){

    $sql="select distinct(n.espacios_fisicos_id) as espacio_id, 
        sum(n.importe_total) as venta_total, 
        sum(cobro_vales) as devolucion, 
        sum(n.importe_total) - sum(cobro_vales) as venta_des, 
        ef.tag as tag 
        from nota_remision as n 
        left join espacios_fisicos as ef on ef.id=n.espacios_fisicos_id 
        where n.estatus_general_id='1' 
        and n.fecha>='$fecha1' and n.fecha<='$fecha2' 
       group by n.espacios_fisicos_id, tag order by espacio_id asc";

    $this->query($sql);
    return $this;

        }

	function get_ventas_espacios($fecha1,$fecha2,$espacio){
		//$espacio=0;
		if($espacio>0){
			$where= " and n.espacios_fisicos_id='$espacio'";
		} else
			$where = "";
		$where.=" and n.fecha >= '$fecha1' and n.fecha < '$fecha2'";


		$u = new Nota_remision();
		$sql="select distinct(n.espacios_fisicos_id) as espacio_id, sum(n.importe_total) as venta_total, sum(cobro_vales) as devolucion, ef.tag as tag from nota_remision as n left join espacios_fisicos as ef on ef.id=n.espacios_fisicos_id where n.estatus_general_id='1'  $where group by n.espacios_fisicos_id, tag order by ef.tag  ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}


}
