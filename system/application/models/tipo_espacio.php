<?php

/**
 * Tipo Espacio Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tipo_espacio extends DataMapper {

	var $table= "tipos_espacios";


	function  Tipo_espacio($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Tipo_espacio
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_tipos_espacios()
	{
		// Create a temporary user object
		$e = new Tipo_espacio();

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_tipos_espacios_mtrx()
	{
		// Obtener espacios para las polizas, excluyendo oficinas
		$ef = new Tipo_espacio();
		$ef->order_by('tag');
		$ef->get();
		if($ef->c_rows>0){
			$colect[0]="TODOS";
			foreach($ef->all as $row){
				$colect[$row->id]=$row->tag;
			}
			return $colect;
		} else
			return 0;
	}

}
