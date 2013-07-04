<?php

/**
 * Prenomina_detalle Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Alma Lilia Núñez González
 * @link
 */
class Prenomina_detalle extends DataMapper {

var $table= "prenominas_detalles";

	function  Prenomina_detalle($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
        
        function get_prenomina_detalle_by_prenomina_id($pre_id){
            $pd = new Prenomina_detalle();
            $pd->where("prenominas_id", $pre_id)->get();
            if ($pd->c_rows > 0) {
                return $pd;
            } else {
                return FALSE;
            }
        }
}