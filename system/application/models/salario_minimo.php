<?php
class Salario_minimo extends DataMapper {
var $table= "csalarios_minimos";

	function  Salario_minimo($id=null)
	{
		parent::DataMapper($id);
	}

	
	function get_salarios_minimos_list($per_page,$offset){
		$this->order_by('id desc')->limit($per_page)->offset($offset)->get();
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
	}
	function get_by_anio($anio){
	  $this->where('anio', $anio)->limit(1)->get();
	  if($this->c_rows>0)
		return $this;
	  else
		return false;
	}
	
}
