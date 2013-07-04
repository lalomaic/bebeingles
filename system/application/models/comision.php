<?php

/**
 * Tipo Comision Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link    	
 */
class Comision extends DataMapper {

var $table= "comisiones";
	
	function  Comision($id=null)
	{
		parent::DataMapper($id);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tipo Comision
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_comisiones()
	{
	  // Create a temporary user object
	  $e = new Comision();
	  //Buscar en la base de datos
	  $e->get();
	  if($e->c_rows>0){
	  return $e;
	  } else {
	  return FALSE;
	  }
	}

	  function get_control_deposito($id)
	{
	  // Create a temporary user object
	  $u = new Comision();
	  //Buscar en la base de datos
	  $u->get_by_id($id);
	  if($u->c_rows ==1){
	    return $u;
	  } else {
	    return FALSE;
	  }
	}

	function get_comisiones_list($per_page,$offset,$espacio) {
		$sql="select c.id, c.tag, ef.tag as espacio,c.espacio_fisico_id as ef_id, t.tag as tipo, e.tag as estatus from comisiones as c 
		left join ctipo_comisiones as t on t.id=c.tipo_comision_id  
		left join estatus_general as e on e.id=c.estatus_general_id  
		left join espacios_fisicos as ef on ef.id=c.espacio_fisico_id
		limit $per_page offset $offset";
	  $this->query($sql);
	  if($this->c_rows > 0){
	    return $this;
	  } else {
	    return FALSE;
	  }
	}

	function get_comisiones_dropd() {
		// Obtener espacios para las polizas, excluyendo oficinas
		$this->where('estatus_general_id', 1);
		$this->order_by('tag');
		$this->get();
		$colect[0]="Elija";
		if($this->c_rows > 0){
 			foreach($this->all as $row){
 				$colect[$row->id]=$row->tag;
 			}
		} 
		return $colect;
	}
	
}