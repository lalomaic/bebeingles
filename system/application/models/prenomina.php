<?php

/**
 * Prenomina Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Alma Lilia Núñez González
 * @link
 */
class Prenomina extends DataMapper {

var $table= "prenominas";

	function  Prenomina($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------
        function get_prenomina_by_periodo($f_inicial, $f_final){
            $p = new Prenomina();
            $p->where('fecha_inicial', $f_inicial);
            $p->where('fecha_final', $f_final);
            $p->get();
            if($p->c_rows > 0){
                return $p;
            } else {
                return FALSE;
            }
        }
        
        function get_prenomina_by_periodo_tienda($f_inicial, $f_final, $espacio){
            $p = new Prenomina();
            $p->where('fecha_inicial', $f_inicial);
            $p->where('fecha_final', $f_final);
            $p->where('espacio_fisico_id', $espacio);
            $p->get();
            if($p->c_rows > 0){
                return $p;
            } else {
                return FALSE;
            }
        }
	function get_prenominas_list($per_page,$offset){
		$sql="select p.*, e.tag as espacio, u.username as usuario from prenominas as p left join espacios_fisicos as e on e.id=p.espacio_fisico_id left join usuarios as u on u.id=p.usuario_id where p.estatus_general_id=1 order by id desc limit $per_page offset $offset ";
		$this->query($sql);
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
	}
     
}