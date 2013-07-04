<?php

class Nomina extends DataMapper {

var $table= "nominas";



	function  Nomina($id=null)
	{
		parent::DataMapper($id);

	}
	function get_prenomina_by_nomina($nomina_id){
	  $sql="select p.*, n.id from nominas as n left join prenominas as p on p.id=n.prenomina_id where n.id=$nomina_id and n.estatus_general_id=1 limit 1";
	  $this->query($sql);
	  if($this->c_rows>0)
		return $this;
	  else
		return false;
	}
	
	function get_nominas_list($per_page,$offset){
		$sql="select p.*, n.id, n.prenomina_id, n.fecha_captura, e.tag as espacio, u.username as usuario from nominas as n left join prenominas as p on n.prenomina_id=p.id left join espacios_fisicos as e on e.id=p.espacio_fisico_id left join usuarios as u on u.id=p.usuario_id where n.estatus_general_id=1 order by n.id desc limit $per_page offset $offset ";
		$this->query($sql);
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
		
	}

	function get_nomina_pdf($id){
		$sql="select p.*, e.*, n.id, n.prenomina_id, n.fecha_captura,  u.username as usuario from nominas as n left join prenominas as p on n.prenomina_id=p.id left join espacios_fisicos as e on e.id=p.espacio_fisico_id left join usuarios as u on u.id=p.usuario_id where n.id=$id";
		$this->query($sql);
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
		
	}

	
}
