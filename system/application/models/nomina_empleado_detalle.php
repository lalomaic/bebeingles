<?php

/**
 * Nomina_empleado_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Alma Lilia Núñez González
 * @link
 */
class Nomina_empleado_detalle extends DataMapper {

var $table= "nomina_empleado_detalles";

	function  Nomina_empleado_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
        
        function get_by_nomina_detalle_id($id){
            $pd = new Nomina_empleado_detalle();
            $pd->where("nomina_detalle_id", $id)->get();
            if ($pd->c_rows > 0) {
                return $pd;
            } else {
                return FALSE;
            }
        }
}