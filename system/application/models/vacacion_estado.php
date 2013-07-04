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
class Vacacion_estado extends DataMapper {

    var $table = "vacaciones_estados";

    function Vacacion_estado($id = null) {
        parent::DataMapper($id);
    }
}
