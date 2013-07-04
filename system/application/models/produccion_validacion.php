<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Produccion_validacion extends Model{


	function Produccion_validacion()
	{
		parent::Model();
	}

	function validacion_receta(){
		$validation[0]=array('id'=>'nombre','arguments'=> "minLength: 5, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el Nombre de la Receta');
		$validation[1]=array('id'=>'cantidad','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese la Cantidad que se Genera');
		$validation[2]=array('id'=>'prod','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Producto que se Genera');
		$validation[3]=array('id'=>'dias_consumo','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese los Días para Consumo');
		$validation[4]=array('id'=>'descripcion','arguments'=> "minLength: 10, maxLength: 200, invalidEmpty: true,", 'response_false'=>'Ingrese la Descripcion de la Receta');
		return $validation;
	}

	function validacion_produccion(){
		$validation[0]=array('id'=>'receta','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Receta a  Generar');
		$validation[1]=array('id'=>'cantidad_producida','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese la Cantidad de Producción a Generar');
		return $validation;
	}

	function validacion_producto_transformado(){
		$validation[0]=array('id'=>'cantidad','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese la Cantidad a Transformar');
		$validation[1]=array('id'=>'prod1','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Producto a Transformar');
		$validation[2]=array('id'=>'prod2','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Producto Transformado');

		return $validation;
	}
}
