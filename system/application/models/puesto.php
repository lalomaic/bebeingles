<?php

/**
 * Puesto Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link    	
 */
class Puesto extends DataMapper {

    var $table = "puestos";
    var $has_many = array(
        'empleado' => array(
            'class' => 'empleado',
            'other_field' => 'puesto'
        )
    );    

    function Puesto($id = null) {
        parent::DataMapper($id);
    }

    // --------------------------------------------------------------------
    function get_puesto($id) {
        // Create a temporary user object
        $e = new Puesto();
        //Buscar en la base de datos
        $e->get_by_id($id);
        if ($e->c_rows > 0) {
            return $e;
        } else {
            return FALSE;
        }
    }

    /**
     * Puesto
     *
     * Authenticates a user for logging in.
     *
     * @access	public
     * @return	bool
     */
    function get_puestos() {
        // Create a temporary user object
        $e = new Puesto();
        $e->where('estatus_general_id', "1");

        //Buscar en la base de datos
        $e->get();
        if ($e->c_rows > 0) {
            return $e;
        } else {
            return FALSE;
        }
    }

    /**
     * Obtener Listado de Puestos
     *
     * .
     *
     * @access	public
     * @return	array
     */
    function get_puestos_list($offset, $per_page) {
        // Create a temporary user object
        $u = new Puesto();
        $sql = "select * from puestos order by id limit $per_page offset $offset";
        //Buscar en la base de datos
        $u->query($sql);
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_puestos_pdf() {
        //Objeto de la clase
        $obj = new Municipio();
        $sql = "SELECT * FROM puestos";
        $obj->query($sql);
        if ($obj->c_rows > 0)
            return $obj;
        else
            return false;
    }

    function get_puestos_dropd() {
        $puestos = new Puesto();
        $puestos->where('estatus_general_id', 1)->
                order_by('tag')->get();
        $array_puestos = array();
        foreach($puestos as $puesto)
            $array_puestos[$puesto->id] = $puesto->tag;
        return $array_puestos;
    }
}
