<?php
class Smg_zona extends DataMapper {
var $table= "csmg_zonas";

	function  Smg_zona($id=null)
	{
		parent::DataMapper($id);
	}
	function get_zonas_dropd() {
		$this->order_by('tag')->get();
        $array_tienda[0] = "Elija";
        foreach($this as $row)
            $array_tienda[$row->id] = $row->tag;
        return $array_tienda;
    }

	
}
