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
class Vacacion extends DataMapper {

    var $table = "vacaciones";

    function Vacacion($id = null) {
        parent::DataMapper($id);
    }

    function registrado($ano, $empleado_id) {
        return $this->where("ano", $ano)->
                where("empleado_id", $empleado_id)->
                get()->c_rows > 0? true: false;
    }

}
