<?php
class Tipo_descuento extends DataMapper {
var $table= "ctipo_descuentos";

	function  Tipo_descuento($id=null){
		parent::DataMapper($id);
	}
	
	function get_tipos_descuentos_list($per_page,$offset){
		$this->order_by('tag')->limit($per_page)->offset($offset)->get();
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
	}
	
	function get_tipos_descuentos_dropd() {
		$this->order_by('tag')->get();
        $array_tienda[0] = "Elija";
        foreach($this as $row)
            $array_tienda[$row->id] = $row->tag;
        return $array_tienda;
    }
	
}
