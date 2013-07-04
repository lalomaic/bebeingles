<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Almacen_validacion extends Model{


	function Almacen_validacion()
	{
		parent::Model();
	}

	function validacion_tipo_entrada(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el Tipo de Entrada');

		return $validation;
	}

	function validacion_tipo_salida(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el Tipo de Entrada');

		return $validation;
	}

	function validacion_entrada_compra(){
		$validation[0]=array('id'=>'proveedores','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Proveedor');
		$validation[1]=array('id'=>'fecha','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha de la factura');
		$validation[2]=array('id'=>'folio_factura','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese el Folio de la factura');
		$validation[3]=array('id'=>'monto_total','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'El monto total debe ser un valor numerico');

		return $validation;
	}
	function validacion_salida_venta(){
		$validation[0]=array('id'=>'clientes','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Cliente');
		$validation[1]=array('id'=>'fecha','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha de la factura');
		$validation[2]=array('id'=>'folio_factura','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese el Folio de la factura');
		$validation[3]=array('id'=>'monto_total','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'El monto total debe ser un valor numerico');

		return $validation;
	}

}
