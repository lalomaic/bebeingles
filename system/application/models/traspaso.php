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
class Traspaso extends DataMapper {

var $table= "traspasos";


	function  Traspaso($id=null)
	{
		parent::DataMapper($id);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Traspaso
	 *
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_traspasos_list($offset, $per_page)
	{
	  // Create a temporary user object
	  $u = new Traspaso();
	  $sql="select p.*, e.tag as estatus, m.tag as municipio from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join cmunicipios as m on m.id=p.municipio_id order by p.razon_social limit $offset, $per_page";
	  //Buscar en la base de datos
	  $u->query($sql);
	  if($u->c_rows > 0){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}
	function get_traspaso($id){
	  // Create a temporary user object
	  $u = new Traspaso();
	  //Buscar en la base de datos
	  $u->get_by_id($id);
	  if($u->c_rows ==1){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}

	function get_traspaso_by_cl_pedido_id($id)
	{
	  // Create a temporary user object
	  $u = new Traspaso();
	  $u->where('cl_pedido_id', "$id");
	  //Buscar en la base de datos
	  $u->get();
	  if($u->c_rows ==1){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}

	function get_traspaso_entrada_local($offset, $per_page, $ubicacion)
	{
	  // Create a temporary user object
	  $u = new Traspaso();
	  $sql="select t.id as traspaso_id, t.*, p.*, ef.tag as recibe, u.nombre as usuario, ef1.tag as envia from traspasos as t left join cl_pedidos as p on p.id=t.cl_pedido_id left join espacios_fisicos as ef on ef.id=t.ubicacion_entrada_id left join usuarios as u on u.id=p.usuario_id left join espacios_fisicos as ef1 on ef1.id=t.ubicacion_salida_id where ubicacion_entrada_id='$ubicacion' and ccl_estatus_pedido_id=3 and traspaso_estatus=1 order by t.id limit $offset, $per_page";
	  //Buscar en la base de datos
	  $u->query($sql);
	  if($u->c_rows > 0){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}
	function get_traspaso_pdf($id)
	{
	  // Create a temporary user object
	  $u = new Traspaso();
	  $sql="select t.*, ef.tag as espacio_envia, ef1.tag as espacio_recibe, u.username as usuario, es.tag as estatus from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.id='$id'";
	  //Buscar en la base de datos
	  $u->query($sql);
	  if($u->c_rows > 0){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}
	function get_traspaso_detalles_pdf($id,$estatus='')
	{
		//Objeto de la clase
		$obj = new Traspaso();
		$sql="select e.*, p.descripcion, p.cod_bar_normal  from salidas as e LEFT JOIN cproductos as p ON p.id=e.cproductos_id where traspaso_id=$id and e.estatus_general_id='1' and cantidad>0 order by descripcion";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}
		function get_traspasos_enviados($offset, $per_page, $estatus=1,$espacio=1){
		$l= new Traspaso();
		$sql="select t.id, t.fecha_envio, t.fecha_recibe, ef.tag as espacio_envia, ef1.tag as espacio_recibe, u.username as usuario, es.tag as estatus from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.estatus_general_id=1 and cestatus_traspaso_id=$estatus and espacio_fisico_envia_id=$espacio order by t.id desc  limit $per_page offset $offset";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_traspasos_enviados_count($espacio,$estatus=1){
		$l= new Traspaso();
		$l->select("count(id) as total")->where('estatus_general_id', 1)->where('espacio_fisico_envia_id', $espacio)->where('cestatus_traspaso_id', $estatus)->get();
			return $l->total;
	}
	function get_tras_entrada_local_count($espacio){
		$l= new Traspaso();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and ef1.id='$espacio' ";
		$sql="select t.id from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.estatus_general_id=1 and cestatus_traspaso_id=1  $and order by fecha_envio desc";
		$l->query($sql);
		if($l->c_rows>0)
			return $l->c_rows;
		else
			return FALSE;
	}


	function get_tras_entrada_local($offset, $per_page, $espacio){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and ef1.id='$espacio' ";
		$sql="select t.*, ef.tag as espacio_envia, ef1.tag as espacio_recibe, u.username as usuario, es.tag as estatus from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.estatus_general_id=1 and cestatus_traspaso_id=1 $and  order by fecha_envio desc limit $per_page offset $offset";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}

	function get_traspaso_entrada_pdf($id,$estatus=1)
	{
		//Objeto de la clase
		$obj = new Traspaso();
		$sql="select e.*, p.descripcion AS producto, es.tag as estatus, e.traspaso_id from salidas as e LEFT JOIN cproductos as p ON p.id=e.cproductos_id  left join traspasos as t on t.id=e.traspaso_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where e.traspaso_id='$id' and e.estatus_general_id='1'  order by producto ";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

		function get_traspasos_recibidos_tienda_count($espacio){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and ef1.id='$espacio' ";
		$sql="select t.id from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.estatus_general_id=1 and cestatus_traspaso_id=2 $and order by fecha_envio desc";
		$l->query($sql);
		if($l->c_rows>0)
			return $l->c_rows;
		else
			return false;
	}

	function get_traspasos_recibidos_tienda($espacio, $offset, $per_page){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and ef1.id='$espacio' ";

		$sql="select t.*, ef.tag as espacio_envia, ef1.tag as espacio_recibe, u.username as usuario, es.tag as estatus from traspasos as t  left join espacios_fisicos as ef on ef.id=t.espacio_fisico_envia_id  left join espacios_fisicos as ef1 on ef1.id=t.espacio_fisico_recibe_id  left join usuarios as u on u.id=t.usuario_id left join cestatus_traspasos as es on es.id=t.cestatus_traspaso_id where t.estatus_general_id=1 and cestatus_traspaso_id=2 $and order by fecha_envio  desc limit $per_page offset $offset";

		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}

	
}
