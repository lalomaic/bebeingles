<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Pago_validacion extends Model{


	function Pago_validacion()
	{
		parent::Model();
	}

	function validacion_pago(){
		$validation[0]=array('id'=>'proveedor','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione un Proveedor');
		$validation[1]=array('id'=>'facturas','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione una Factura');
		$validation[2]=array('id'=>'formas_pagos','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Forma de Pago');
		$validation[3]=array('id'=>'tipos_pagos','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Tipo de Pago');
		$validation[4]=array('id'=>'cuentas_destino','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Cuenta Bancaria');
		$validation[5]=array('id'=>'monto_pagado','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Monto Pagado');
		$validation[6]=array('id'=>'fecha','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la Fecha');

		return $validation;
	}

	function validacion_forma_pago(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Forma de Pago');
		 
		return $validation;
	}

	function validacion_tipo_pago(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Forma de Pago');
		 
		return $validation;
	}



}
