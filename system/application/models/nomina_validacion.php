<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link    	
 */
class Nomina_validacion extends Model{


	function Nomina_validacion()
	{
		parent::Model();	
	}

	function validacion_empleado(){
		$validation[0]=array('id'=>'nombre','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		$validation[1]=array('id'=>'apaterno','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el apellido paterno');
		$validation[2]=array('id'=>'amaterno','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el apellido materno');
		$validation[3]=array('id'=>'curp','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese la CURP');
		$validation[4]=array('id'=>'num_seguro','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el numero de seguro social');
		$validation[5]=array('id'=>'rfc','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el RFC');
		$validation[6]=array('id'=>'telefono','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el telefono');
		$validation[7]=array('id'=>'domicilio','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio');

		return $validation;
	}
	
	function validacion_horario()
		{
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		return $validation;
		}
		
	function validacion_nomina()
		{
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		return $validation;
		}

	function validacion_puesto()
		{
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		return $validation;
		}
		
	function validacion_prestacion()
		{
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		return $validation;
		}
		
	function validacion_deduccion()
		{
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre');
		return $validation;
		}

}
