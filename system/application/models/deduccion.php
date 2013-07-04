<?php

/**
 * Deduccion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Alma Lilia Núñez González
 * @link
 */
class Deduccion extends DataMapper {

var $table= "deducciones";

	function  Deduccion($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
        
        function get_deducciones(){
            $p = new Deduccion();
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

  function get_deducciones_mtrx() {
	$this->
			order_by('tag')->get();
	$array_tienda = array();
	foreach($this as $espacio)
	  $array_tienda[$espacio->id] = $espacio->tag;

	return $array_tienda;
  }
  
  
}