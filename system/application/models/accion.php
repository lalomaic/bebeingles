<?php

/**
 * Usuario Acciones Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Accion extends DataMapper {

	var $table= "acciones";

	function Accion ($id = NULL)
	{
		parent::DataMapper($id);
	}


	/**
	 * Obtiene la información de una accion
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	submodulo_id
	 */
	function get_accion($id){
		$a= new Accion();
		$a->get_by_id($id);
		if($a->c_rows==1){
			return $a;
		} else {
			return FALSE;
		}
	}
	/**
	 * Obtiene las acciones
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	submodulo_id
	 */
	function get_acciones(){
		$a= new Accion();
		$a->get();
		if($a->c_rows>0){
			return $a;
		} else {
			return FALSE;
		}
	}

	/**
	 * Obtiene el titulo de un submodulo
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	function_controller
	 */
	function get_title($function_controller){
		$a= new Accion();
		$a->where('function_controller', "$function_controller");
		$a->get();
		if($a->c_rows == 1){
			return $a->nombre;
		} else {
			return FALSE;
		}
	}
	/**
	 * Obtiene el id de un submodulo
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	function_controller
	 */
	function get_id($function_controller){
		$a= new Accion();
		$a->where('function_controller', "$function_controller");
		$a->get();
		//echo $function_controller."".$a->c_rows;
		if($a->c_rows == 1){
			//echo $a->id;
			return $a->id;
		} else {
			return FALSE;
		}
	}

	/**
	 * Obtiene las acciones, submodulos  y modulos con etiquetas de todo el sistema
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	none
	 */
	function get_asm(){
		$a= new Accion();
		$sql="select a.*, s.nombre as submodulo, m.nombre as modulo, c.tag as tipo_accion from acciones as a left join submodulos as s on s.id=a.submodulo_id left join modulos as m on m.id=a.modulo_id left join tipo_acciones as c on c.id=a.tipo_accion_id order by modulo_id, submodulo_id, tipo_accion_id, a.nombre";
		$a->query($sql);
		if($a->c_rows > 0){
			return $a;
		} else {
			return FALSE;
		}
	}

}
