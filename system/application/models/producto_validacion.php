<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Producto_validacion extends Model{


	function Producto_validacion()
	{
		parent::Model();
	}

	function validacion_familia_producto(){
		$validation[0]=array('id'=>'clave','arguments'=> "minLength: 3, maxLength: 6, invalidEmpty: true,", 'response_false'=>'Ingrese la Clave');
		$validation[1]=array('id'=>'tag','arguments'=> "minLength: 3, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el Nombre de la Familia de Productos');
		$validation[2]=array('id'=>'estatus','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Estatus General de la Familia de Productos');

		return $validation;
	}

	function validacion_subfamilia_producto(){
		$validation[0]=array('id'=>'clave','arguments'=> "minLength: 3, maxLength: 6, invalidEmpty: true,", 'response_false'=>'Ingrese la Clave');
		$validation[1]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el Nombre de la Subfamilia de Productos');
		$validation[2]=array('id'=>'familia_productos','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Familia de Productos');
		$validation[3]=array('id'=>'estatus','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Estatus General de la Subfamilia de Productos');

		return $validation;
	}

	function validacion_marca(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 2, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Marca del Producto');

		return $validation;
	}

	function validacion_unidad_m(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Unidad de Medida');

		return $validation;
	}

	function validacion_producto(){
		$validation[0]=array('id'=>'familias','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Familia de Productos');
		$validation[1]=array('id'=>'subfamilia_productos','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Subfamilia de Productos');
		$validation[2]=array('id'=>'marca_productos','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la  Marca de Productos');
		$validation[3]=array('id'=>'unidades_medidas','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la Unidad de Medida');
		$validation[4]=array('id'=>'clave','arguments'=> "minLength: 3, maxLength: 10, invalidEmpty: true,", 'response_false'=>'Ingrese la Clave del Producto Máximo 10 caracteres');
		$validation[5]=array('id'=>'descripcion','arguments'=> "minLength: 5, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese la Descripcion del Producto');
		$validation[6]=array('id'=>'presentacion','arguments'=> "minLength: 3, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Presentacion del Producto');
		$validation[7]=array('id'=>'comision_venta','arguments'=> "format: 'decimal', minLength: 1, maxLength: 4, invalidEmpty: true,", 'response_false'=>'Ingrese la Comision por Venta del Producto');
		$validation[8]=array('id'=>'precio1','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Precio No. 1 del Producto');
		$validation[9]=array('id'=>'precio2','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Precio No. 2 del Producto');
		$validation[10]=array('id'=>'precio3','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Precio No. 3 del Producto');
		$validation[11]=array('id'=>'precio4','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Precio No. 4 del Producto');
		$validation[12]=array('id'=>'precio5','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el Precio No. 5 del Producto');
		$validation[13]=array('id'=>'tasa_impuesto','arguments'=> "format: 'decimal', minLength: 1, maxLength: 4,invalidEmpty: true,", 'response_false'=>'Ingrese la Tasa de Impuesto del Producto');
		$validation[14]=array('id'=>'vida_media','arguments'=> "format: 'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese los Dias de Caducidad del Producto');

		return $validation;
	}
	function validacion_material(){
		$validation[0]=array('id'=>'tag','arguments'=> "minLength: 3, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el Material de Producto');
		return $validation;
	}
	//agregue este por si acaso validacion del color
	function validacion_color(){
		$validation[0]=array('id'=>'tag','arguments'=>"minLength: 3, maxLength: 254, invalidEmpty: true,",'response_false'=>'Ingrese el color');
		return $validation;
	}


}
