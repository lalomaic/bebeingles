<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Admin_validacion extends Model{


	function Admin_validacion()
	{
		parent::Model();
	}

	function validacion_usuario(){
		$validation[0]=array('id'=>'empresa','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la empresa contratante');
		$validation[1]=array('id'=>'espacio','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la ubicación del empleado');
		$validation[2]=array('id'=>'grupo','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el grupo al que pertenece el empleado');
		$validation[3]=array('id'=>'puesto','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el puesto del empleado');
		$validation[4]=array('id'=>'nombre','arguments'=> "minLength: 10, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del empleado');
		$validation[5]=array('id'=>'telefono','arguments'=> "format: 'email',invalidEmpty: true,", 'response_false'=>'Ingrese un número telefónico');
		$validation[6]=array('id'=>'email','arguments'=> "format: 'email',invalidEmpty: true,", 'response_false'=>'Ingrese un correo electrónico valido');

		return $validation;
	}

	function validacion_espacio_f(){
		$validation[0]=array('id'=>'empresa','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la empresa');
		$validation[1]=array('id'=>'tipo_espacio','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el tipo de local');
		$validation[2]=array('id'=>'tag','arguments'=> "minLength: 3, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del local');
		$validation[3]=array('id'=>'estado','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Estado');
		$validation[4]=array('id'=>'municipio','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Municipio');
		$validation[5]=array('id'=>'localidad','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre de la localidad');
		$validation[6]=array('id'=>'clave','arguments'=> "minLength: 3, maxLength: 10, invalidEmpty: true,", 'response_false'=>'Ingrese la clave del local');
		$validation[7]=array('id'=>'domicilio','arguments'=> "minLength: 20, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio');
		$validation[8]=array('id'=>'telefono','arguments'=> "minLength: 7, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el telefono');

		return $validation;
	}

	function validacion_estado(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del estado');

		return $validation;
	}

	function validacion_municipio(){
		$validation[0]=array('id'=>'estado','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Estado');
		$validation[1]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del municipio');

		return $validation;
	}

	function validacion_empresa(){
		$validation[0]=array('id'=>'razon_social','arguments'=> "minLength: 5, maxLength: 70, invalidEmpty: true,", 'response_false'=>'Ingrese la razón social');
		$validation[1]=array('id'=>'rfc','arguments'=> "minLength: 1, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese el Registro Federal de Contribuyentes (RFC)');
		$validation[2]=array('id'=>'domicilio_fiscal','arguments'=> "minLength: 1, maxLength: 80, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio fiscal');
		$validation[3]=array('id'=>'telefonos','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese los teléfonos');
		$validation[4]=array('id'=>'ciudad','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre de la ciudad');
		$validation[5]=array('id'=>'estado','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del estado');
		$validation[6]=array('id'=>'pais','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del país');

		return $validation;
	}

	function validacion_grupo(){
		$validation[0]=array('id'=>'nombre','arguments'=> "minLength: 5, maxLength: 40, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del grupo');
		$validation[1]=array('id'=>'descripcion','arguments'=> "minLength: 5, maxLength: 40, invalidEmpty: true,", 'response_false'=>'Ingrese la descripción del grupo');

		return $validation;
	}

	function validacion_puesto(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 40, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del puesto');

		return $validation;
	}

       function validacion_cliente(){
		$validation[0]=array('id'=>'razon_social','arguments'=> "minLength: 5, maxLength: 70, invalidEmpty: true,", 'response_false'=>'Ingrese la razón social');
		$validation[1]=array('id'=>'rfc','arguments'=> "minLength: 15, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese el Registro Federal de Contribuyentes (RFC)');
		$validation[2]=array('id'=>'curp','arguments'=> "minLength: 1, invalidEmpty: true,", 'response_false'=>'Ingrese la CURP');
                $validation[3]=array('id'=>'calle','arguments'=> "minLength: 15, maxLength: 80, invalidEmpty: true,", 'response_false'=>'Ingrese la calle');
		$validation[4]=array('id'=>'numero_exterior','arguments'=> "minLength: 1, maxLength: 5, invalidEmpty: true,", 'response_false'=>'Ingrese el numero exterior');
		$validation[5]=array('id'=>'numero_interior','arguments'=> "minLength: 1, maxLength: 5, invalidEmpty: true,", 'response_false'=>'Ingrese el numero interior');
		$validation[6]=array('id'=>'colonia','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Colonia');
		$validation[7]=array('id'=>'codigo_postal','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese codigo Postal');
                $validation[8]=array('id'=>'localidad','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre de la localidad');
		$validation[9]=array('id'=>'municipio','arguments'=> "minLength: 7, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Seleccione el Municipio');
                $validation[10]=array('id'=>'estado','arguments'=> "minLength: 7, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Seleccione el Estado');
		$validation[11]=array('id'=>'lada','arguments'=> "minLength: 2, maxLength: 6, invalidEmpty: true,", 'response_false'=>'Ingrese la lada');
                $validation[12]=array('id'=>'telefono','arguments'=> "minLength: 7, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el telefono');
                $validation[14]=array('id'=>'fax','arguments'=> "minLength:1, maxLength:12, invalidEmpty: true,", 'response_false'=>'Ingrese Fax');
               	$validation[15]=array('id'=>'email','arguments'=> "format: 'email',invalidEmpty: true,", 'response_false'=>'Ingrese un correo electrónico valido');
                $validation[16]=array('id'=>'limite_credito','arguments'=> "format:'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el limite de Credito');
                $validation[17]=array('id'=>'dias_credito','arguments'=> "format:'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese los dias de Credito');
                $validation[18]=array('id'=>'nombre_corto','arguments'=> "minLength: 5, maxLength: 40, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre corto');
                $validation[19]=array('id'=>'domicilio','arguments'=> "minLength: 20, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio');
                return $validation;
	} 
        
        
}
