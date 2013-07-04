<?php

/**
 * Prestacion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Alma Lilia Núñez González
 * @link
 */
class Prestacion extends DataMapper {

var $table= "prestaciones";

	function  Prestacion($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
        
	function get_prestaciones(){
		$p = new Prestacion();
		$p->order_by("tag")->get();
		if ($p->c_rows > 0) {
			return $p;
		} else {
			return FALSE;
		}
	}
    
    function get_id_maximo(){
	  $this->select_max('id')->get();
	  return $this->id;
	}

  function get_prestaciones_mtrx() {
	$this->
			order_by('tag')->get();
	$array_tienda = array();
	foreach($this as $espacio)
	  $array_tienda[$espacio->id] = $espacio->tag;

	return $array_tienda;
  }
  
}