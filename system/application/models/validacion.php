<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Validacion extends Model{


	function Validacion()
	{
		parent::Model();
	}

	function validacion_usuario(){
		$validation[0]=array('id'=>'empresa','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la empresa contratante');
		$validation[1]=array('id'=>'espacio','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la ubicación del empleado');
		$validation[2]=array('id'=>'grupo','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el grupo al que pertenece el empleado');
		$validation[3]=array('id'=>'puesto','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el puesto del empleado');
		$validation[4]=array('id'=>'nombre','arguments'=> "minLength: 20, maxLength: 100,", 'response_false'=>'Ingrese el nombre del empleado');
		$validation[5]=array('id'=>'email','arguments'=> "format: 'email',invalidEmpty: true,", 'response_false'=>'Ingrese un correo electrónico valido');

		return $validation;
	}



}
