<?php

/**
 * Deuda_tienda Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Deuda_tienda extends DataMapper {

	var $table= "deuda_tiendas";


	function  Deuda_tienda($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Cpr_estatus_pedido
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_formas_deuda_tienda(){
		$e = new Deuda_tienda();
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_listado($offset, $per_page){
		// Create a temporary user object
		$u = new Deuda_tienda();
		$sql="select dt.*, e1.tag as debe, e2.tag as recibe, d.tag as concepto, u.username as usuario from deuda_tiendas as dt left join espacios_fisicos as e1 on e1.id=dt.espacio_fisico_debe_id left join espacios_fisicos as e2 on e2.id=dt.espacio_fisico_recibe_id left join ctipo_deuda_tienda as d on d.id=dt.ctipo_deuda_tienda_id left join usuarios as u on u.id=dt.usuario_id order by fecha desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_deuda_tienda($id)
	{
		// Create a temporary user object
		$u = new Deuda_tienda();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_listado_count(){
		$u = new Deuda_tienda();
		$u->select("count(id) as total")->get();
		return $u->total;
	}

	function get_listado_filtrado($debe_id,$recibe_id, $fecha1a, $fecha2a,$concepto_id){
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
		if($debe_id>0)
			$debe_str=" and dt.espacio_fisico_debe_id=$debe_id";
		else
			$debe_str=' ';
		if($recibe_id>0)
			$recibe_str=" and dt.espacio_fisico_recibe_id=$recibe_id";
		else
			$recibe_str=' ';
		if($concepto_id>0)
			$concepto_str=" and dt.ctipo_deuda_tienda_id=$concepto_id";
		else
			$concepto_str=' ';

		$u = new Entrada();
		$sql="select dt.*, e1.tag as debe, e2.tag as recibe, d.tag as concepto, u.username as usuario
		from deuda_tiendas as dt
		left join espacios_fisicos as e1 on e1.id=dt.espacio_fisico_debe_id
		left join espacios_fisicos as e2 on e2.id=dt.espacio_fisico_recibe_id
		left join ctipo_deuda_tienda as d on d.id=dt.ctipo_deuda_tienda_id
		left join usuarios as u on u.id=dt.usuario_id
		where  dt.fecha>='$fecha1' and dt.fecha<'$fecha2'  $debe_str $recibe_str $concepto_str
		order by fecha desc ";

		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0)
			return $u;
		else
			return FALSE;
	}

}
