<?php

/**
 * Empleado Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Diego J. Pérez Ruiz
 * @link
 */
class Vacacion_factor extends DataMapper {

    var $table = "cfactores_vacaciones_salarios";

    function Vacacion_factor($id = null) {
        parent::DataMapper($id);
    }
    
    function get_dias_by_anos($anos) {
        $vac = new Vacacion_factor();
        $vac->where("anos", $anos)->get();
        if($vac->c_rows > 0) {
            return $vac->dias_vacaciones;
        } else {
            return false;
        }
    }
    
    function get_id_by_anos($anos) {
        $vac = new Vacacion_factor();
        $vac->where("anos", $anos)->get();
        if($vac->c_rows > 0) {
            return $vac->id;
        } else {
            return false;
        }
    }
}
