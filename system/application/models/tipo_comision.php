<?php

/**
 * Tipo Tipo_comision Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link    	
 */
class Tipo_comision extends DataMapper {

var $table= "ctipo_comisiones";
	
	function  Tipo_comision($id=null)
	{
		parent::DataMapper($id);

	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tipo Tipo_comision
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_tipo_comisiones() {
		$this->get();
		if($this->c_rows>0)
			return $this;
		else 
			return FALSE;
	}

    function get_tipos_comisiones_dropd() {
        $this->
                where('estatus_general_id', 1)->get();
        $array_tienda[0] = "Elija";
        foreach($this as $row)
            $array_tienda[$row->id] = $row->tag;
        return $array_tienda;
    }
}