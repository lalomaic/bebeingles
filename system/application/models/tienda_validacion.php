<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Tienda_validacion extends Model{


	function Tienda_validacion()
	{
		parent::Model();
	}

	function validacion_transferencia(){
		$validation[0]=array('id'=>'proveedores','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el proveedor');
		$validation[1]=array('id'=>'fecha_entrega','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha tentativa de entrega');
		$validation[2]=array('id'=>'fecha_pago','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha tentativa de pago');
		$validation[3]=array('id'=>'pago','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la forma de pago');

		return $validation;
	}

	function validacion_deposito(){
		$validation[0]=array('id'=>'cuenta','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la cuenta bancaria del depósito');
		$validation[1]=array('id'=>'fecha_venta','arguments'=> "minLength: 3, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese la fecha de venta a la que hace referencia el depósito');
		$validation[2]=array('id'=>'cantidad','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la cantidad depósitada');

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
		$validation[1]=array('id'=>'rfc','arguments'=> "minLength: 15, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese el Registro Federal de Contribuyentes (RFC)');
		$validation[2]=array('id'=>'domicilio_fiscal','arguments'=> "minLength: 15, maxLength: 80, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio fiscal');
		$validation[3]=array('id'=>'telefonos','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese los teléfonos');
		$validation[4]=array('id'=>'ciudad','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre de la ciudad');
		$validation[5]=array('id'=>'estado','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del estado');
		$validation[6]=array('id'=>'pais','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre del país');

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

}
