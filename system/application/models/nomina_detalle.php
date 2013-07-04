<?php

class Nomina_detalle extends DataMapper {

var $table= "nomina_detalles";

	function  Nomina_detalle($id=null)
	{
		parent::DataMapper($id);

	}
	
  function get_detalles_by_nomina_id($nomina_id){
	$sql="select e.*, p.*, d.*, p1.dias_semana  from nomina_detalles as d left join empleados as e on e.id=d.empleado_id  left join prenominas_detalles as p on p.id=d.prenomina_detalle_id left join prenominas as p1 on p1.id=p.prenominas_id where d.nomina_id=$nomina_id order by e.apaterno, e.amaterno, e.nombre";
	$this->query($sql);
	if($this->c_rows>0)
	  return $this;
	else
	  return false;
  }
}
