<?php

/**
 * Empleado Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Empleado extends DataMapper {

    var $table = "empleados";
    var $has_one = array(
        'puesto' => array(
            'class' => 'puesto',
            'other_field' => 'empleado'
        ),
        'espacio_fisico' => array(
            'class' => 'espacio_fisico',
            'other_field' => 'empleado'
        ),
        'estatus_general' => array(
            'class' => 'estatus_general',
            'other_field' => 'empleado'
        )
    );

    function Empleado($id = null) {
        parent::DataMapper($id);
    }

    // --------------------------------------------------------------------

    /**
     * empleado_modulos
     *
     * Obtiene un empleado a partir del hash de la cookie para .
     *
     * @access	public
     * @return	array
     */
    function get_empleado_detalles($user_hash) {
        // Create a temporary user object
        $u = new Empleado();
        //Buscar en la base de datos
        $u->select('id, nombre, apaterno, amaterno, empresas_id, grupo_id, puesto_id, espacio_fisico_id');
        $u->where("user_data", $user_hash);
        $u->get();
        if ($u->c_rows == 1) {
            return $u;
        } else {
//echo "no";
            return FALSE;
        }
    }

    /**
     * get_empleado_md5
     *
     * Obtiene el id de empleado a partir del hash de la cookie.
     *
     * @access	public
     * @return	array
     */
    function get_empleado($id) {
        // Create a temporary user object
        $u = new Empleado();
        //Buscar en la base de datos
        $u->get_by_id($id);
        if ($u->c_rows == 1) {
            return $u;
        } else {
            return FALSE;
        }
    }

    /**
     * Obtener Listado de Empleados de una empresa que son vendedores
     *
     * .
     *
     * @access	public
     * @return	array
     * */
    function get_vendedores($empresa_id, $espacio_fisico) {
        // Create a temporary user object
        $u = new Empleado();
        $u->select("id, nombre,apaterno, amaterno");
        $u->where("empresas_id", $empresa_id);
        $u->where("espacio_fisico_id", $espacio_fisico);
        $u->where("puesto_id", '4');
        $u->where("estatus_general_id", '1');
        $u->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    /**
     * Obtener Listado de Empleados, id, username, nombre, empresa, ubicacion, email para pdf
     *
     * .
     *
     * @access	public
     * @return	array
     */
    function get_empleados_pdf($where, $order) {
        // Create a temporary user object
        $u = new Empleado();
        $sql = "select u.*, e.razon_social as empresa, ef.tag as espacio_fisico, p1.tag as puesto from empleados as u left join empresas as e on e.id=u.empresas_id left join espacios_fisicos as ef on ef.id=u.espacio_fisico_id left join puestos as p1 on p1.id=u.puesto_id $where $order";
        //Buscar en la base de datos
        $u->query($sql);
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_empleado_by_key($key) {
        // Create a temporary user object
        $u = new Empleado();
        $u->where('llave_electronica', "$key");
        //Buscar en la base de datos
        $u->get();
        if ($u->c_rows == 1) {
            return $u->id;
        } else {
            return FALSE;
        }
    }

    function get_empleados_by_espacio_f($espacio_id) {
        $u = new Empleado();
        $u->where('espacio_fisico_id', $espacio_id)
                ->where('estatus_general_id', 1)
                ->order_by("nombre");
        $u->get();
       
            return $u;
        
    }

    //Metodos para listado
    function get_empleados_list($limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }
    
    
    function get_empleado_list2($id) {
        $u = new Empleado();
        $u->where('estatus_general_id', 1);
         $u->where('id', $id);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->order_by('nombre')
             
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    } 
    	function get_espacio_empleado($id)
	{
		// Create a temporary user object
		$u = new Empleado();
		$sql="select *,ef.razon_social from empleados as em
left join espacios_fisicos as ef on ef.id=em.espacio_fisico_id
where 
em.id=$id ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
    

    function get_empleados_count_list($act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->count();
    }

    //Metodos para listado con filtrado    
    function get_empleados_list_by_nombre($nombre, $limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->ilike('nombre', $nombre)
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
     
            return $u;
       
    }

    function get_empleados_count_list_by_nombre($nombre,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->ilike('nombre', $nombre)->count();
    }

    function get_empleados_list_by_apaterno($apaterno, $limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->ilike('apaterno', $apaterno)
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_empleados_count_list_by_apaterno($apaterno,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->ilike('apaterno', $apaterno)->count();
    }

    function get_empleados_list_by_amaterno($amaterno, $limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->ilike('amaterno', $amaterno)
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_empleados_count_list_by_amaterno($amaterno,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->ilike('amaterno', $amaterno)->count();
    }

    function get_empleados_list_by_puestoid($puesto_id, $limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->where('puesto_id', $puesto_id)
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_empleados_count_list_by_puestoid($puesto_id,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->where('puesto_id', $puesto_id)->count();
    }

    function get_empleados_list_by_espacioid($espacio_id, $limit, $offset,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        $u->include_related("puesto", "tag")
                ->include_related("espacio_fisico", "tag")
                ->include_related("estatus_general", "tag")
                ->where('espacio_fisico_id', $espacio_id)
                ->order_by('nombre')
                ->limit($limit)
                ->offset($offset)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

    function get_empleados_count_list_by_espacioid($espacio_id,$act=1) {
        $u = new Empleado();
        $u->where('estatus_general_id', $act);
        return $u->where('espacio_fisico_id', $espacio_id)->count();
    }

    function get_empleados_activos() {
        $u = new Empleado();
        $u->where('estatus_general_id', 1)
                ->get();
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }
    //END Metodos para listado con filtrado
}
