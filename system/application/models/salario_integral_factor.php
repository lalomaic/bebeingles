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
class Salario_integral_factor extends DataMapper {

    var $table = "cfactores_vacaciones_salarios";

    function Salario_integral_factor($id = null) {
        parent::DataMapper($id);
    }
    
    function get_factor_by_anos($anos) {
        $vac = new Salario_integral_factor();
        $vac->where("anos", $anos)->get();
        if($vac->c_rows > 0) {
            return $vac->factor_integracion;
        } else {
            return false;
        }
    }
}
