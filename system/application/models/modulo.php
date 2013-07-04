<?php

/**
 * Usuario Acciones Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @link
 */
class Modulo extends DataMapper {

	var $table= "modulos";

	function Modulo($id = NULL)
	{
		parent::DataMapper($id);
	}

	/**
	 * Obtiene la informaciÃ³n de un modulo
	 * @category	Models
	 * @author  	Salvador Salgado RamÃ­re SARS
	 * @arguments	moduloid
	 */
	function get_modulo($moduloid){
		$m= new Modulo();
		$m->get_by_id($moduloid);
		if( $m->c_rows == 1 ){
			return $m;
		} else {
			return FALSE;
		}

	}
	/**
	 * Obtiene el Id del modulo
	 * @category	Models
	 * @author  	Salvador Salgado RamÃ­re SARS
	 * @arguments	ruta
	 */

	function get_modulo_id($ruta){

		$m= new Modulo();
		$m->select('id');
		$m->where('ruta',$ruta)->get();
		if($m->c_rows==1){
			return $m->id;
		} else {
			return FALSE;
		}
	}

	function get_modulo_name($ruta){

		$m= new Modulo();
		$m->select('nombre');
		$m->where('ruta',$ruta)->get();
		if($m->c_rows==1){
			return $m->nombre;
		} else {
			return FALSE;
		}
	}
	/**
	 * Obtiene el Todos los modulo registrados para el menu
	 * @category	Models
	 * @author  	Salvador Salgado RamÃ­re SARS
	 * @arguments	ruta
	 */

	function get_tmodulos(){
		$m= new Modulo();
		$m->get();
		return $m->c_rows-1;
	}

	/**
	 * Obtiene el Todos los modulo
	 * @category	Models
	 * @author  	Salvador Salgado RamÃ­re SARS
	 * @arguments	ruta
	 */

	function get_modulos(){
		$m= new Modulo();
		$m->get();
		if($m->c_rows>0){
			return $m;
		} else {
			return FALSE;
		}
	}


}
