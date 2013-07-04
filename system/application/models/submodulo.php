<?php

/**
 * Usuario Acciones Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado RamÃÂ­re SARS
 * @link
 */
class Submodulo extends DataMapper {

	var $table= "submodulos";

	function Submodulo($id = NULL)
	{
		parent::DataMapper($id);
	}


	/**
	 * Obtiene la informaciÃÂ³n de un submodulo
	 * @category	Models
	 * @author  	Salvador Salgado RamÃÂ­re SARS
	 * @arguments	submodulo_id
	 */
	function get_submodulo($id){
		$s= new Submodulo();
		$s->get_by_id($id);
		if($s->c_rows==1){
			return $s;
		} else {
			return FALSE;
		}
	}

}