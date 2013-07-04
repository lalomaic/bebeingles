<?php

/**
 * Traspaso_tienda Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Traspaso_tienda extends DataMapper {

	var $table= "traspasos_tiendas";

	function  Traspaso_tienda($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Traspaso_tienda
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_traspaso_tienda()
	{
		// Create a temporary user object
		$e = new Traspaso_tienda();
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_traspaso_tienda_by_espacio($offset, $limit, $espacio){
		$e=new Traspaso_tienda();
		if($espacio>0)
			$espacio_str=" where tt.espacio_fisico_id=$espacio and ";
		else
			$espacio_str=" and ";

		$sql="select tt.*, date(tt.fecha_salida) as fecha_salida, s.cproductos_id, s.cproducto_numero_id, p.descripcion, pn.numero_mm, e.tag as envia, e1.tag as recibe from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id left join espacios_fisicos as e on e.id=tt.espacio_fisico_id left join espacios_fisicos as e1 on e1.id=tt.espacio_fisico_recibe_id $espacio_str s.estatus_general_id=1  order by tt.fecha_salida desc, p.descripcion, pn.numero_mm limit $limit offset $offset";
		$e->query($sql);
		if($e->c_rows > 0){
			return $e;
		} else {
			return 0;
		}

	}

	function get_traspaso_tienda_filtrado($espacio1, $espacio2,$fecha1a,$fecha2a, $descripcion){
		$e=new Traspaso_tienda();
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
			$fecha1=date("2000-01-01");
			$fecha2=date("Y-m-d", strtotime(date("Y-m-d")) + (24 * 60 * 60));
		}
		if($espacio1>0)
			$espacio_str1=" and tt.espacio_fisico_id=$espacio1";
		else
			$espacio_str1='';
		if($espacio2>0)
			$espacio_str2=" and tt.espacio_fisico_recibe_id=$espacio2";
		else
			$espacio_str2='';
		if(strlen($descripcion)>0)
			$descripcion_str=" and p.descripcion like '%$descripcion%' ";
		else
			$descripcion_str= " ";
		$sql="select tt.*, date(tt.fecha_salida) as fecha_salida, s.cproductos_id, s.cproducto_numero_id, p.descripcion, pn.numero_mm, e.tag as envia, e1.tag as recibe from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id left join espacios_fisicos as e on e.id=tt.espacio_fisico_id left join espacios_fisicos as e1 on e1.id=tt.espacio_fisico_recibe_id where fecha>='$fecha1' and fecha<'$fecha2'  $espacio_str1 $espacio_str2 $descripcion_str and s.estatus_general_id=1  order by tt.fecha_salida desc, p.descripcion, pn.numero_mm ";
		$e->query($sql);
		if($e->c_rows > 0){
			return $e;
		} else {
			return 0;
		}
	}

	function get_traspaso_tienda_by_espacio_count($espacio){
		$e=new Traspaso_tienda();
		if($espacio>0)
			$espacio_str=" where tt.espacio_fisico_id=$espacio and ";
		else
			$espacio_str=" and ";
		$sql="select count(tt.id) as total from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.cproducto_id=s.cproducto_numero_id $espacio_str s.estatus_general_id=1 ";
		$e->query($sql);
		if($e->c_rows > 0){
			return $e->total;
		} else {
			return FALSE;
		}

	}

	function get_traspasos_tienda_pendientes($espacio,$recibe=false){
		$e=new Traspaso_tienda();
		if($espacio>0 and $recibe==false)
			$espacio_str=" where tt.espacio_fisico_id=$espacio and ";
		if($espacio>0 and $recibe==true)
			$espacio_str=" where tt.espacio_fisico_recibe_id=$espacio and ";
		else
			$espacio_str=" and ";
		$sql="select tt.*, date(tt.fecha_salida) as fecha_salida,  s.cproductos_id, s.cproducto_numero_id, s.lote_id, p.descripcion, pn.numero_mm, pn.clave_anterior, e.tag as envia, e1.tag as recibe from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id left join espacios_fisicos as e on e.id=tt.espacio_fisico_id left join espacios_fisicos as e1 on e1.id=tt.espacio_fisico_recibe_id $espacio_str s.estatus_general_id=1 and entrada_id=0 order by tt.fecha_salida, p.descripcion, pn.numero_mm ";
		$e->query($sql);
		if($e->c_rows > 0){
			return $e;
		} else {
			return 0;
		}

	}


	function get_listado_deuda_tiendas($espacio1, $espacio2,$fecha2a){
		$e=new Traspaso_tienda();
		//Fecha
		if($fecha2a=="") {
			$hoy=date("Y-m-d");
			$fecha2a=date("Y-m-d", strtotime($hoy));
		}
		if($espacio1>0)
			$espacio_str1=" and tt.espacio_fisico_id=$espacio1";
		else
			$espacio_str1='';
		if($espacio2>0)
			$espacio_str2=" and tt.espacio_fisico_recibe_id=$espacio2";
		else
			$espacio_str2='';
		$sql="select tt.*, date(tt.fecha_entrada) as fecha_entrada, e.id, e.cproductos_id, e.cantidad, e.costo_unitario, e.costo_total from traspasos_tiendas as tt left join entradas as e on e.id=tt.entrada_id left join cproductos as p on p.id=e.cproductos_id where fecha<='$fecha2a'  $espacio_str1 $espacio_str2  and e.estatus_general_id=1  and e.deuda_tiendas=0 order by e.fecha asc";
		$e->query($sql);
		if($e->c_rows > 0){
			return $e;
		} else {
			return 0;
		}
	}

}
