<?php

/**
 * Modelo para generar Validacion en Javascript
 *
 *
 * @category	Models
 * @author  	Salvador Salgado Ram?re SARS
 * @link
 */
class Ventas_validacion extends Model{


	function Ventas_validacion()
	{
		parent::Model();
	}

	function validacion_cl_pedido(){
		$validation[0]=array('id'=>'cliente','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione el Cliente');
		$validation[1]=array('id'=>'cobro','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la forma de pago');
		$validation[2]=array('id'=>'fecha_entrega','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha tentativa de entrega');
		$validation[3]=array('id'=>'fecha_pago','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha tentativa de pago');

		return $validation;
	}

	function validacion_poliza_diario(){
		$validation[0]=array('id'=>'concepto','arguments'=> "invalidEmpty: true,", 'response_false'=>'Defina el Concepto Global de la Póliza de Diario');
		$validation[1]=array('id'=>'cobro','arguments'=> "minValue: '1',", 'response_false'=>'Seleccione la forma de pago');
		$validation[2]=array('id'=>'fecha','arguments'=> "invalidEmpty: true,", 'response_false'=>'Ingrese la fecha de la póliza de diario');

		return $validation;
	}
        
        
         function validacion_cliente(){
             
		$validation[0]=array('id'=>'razon_social','arguments'=> "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false'=>'Ingrese la razón social');
		$validation[1]=array('id'=>'rfc','arguments'=> "minLength: 12, maxLength: 20, invalidEmpty: true,", 'response_false'=>'Ingrese el Registro Federal de Contribuyentes (RFC)');
		
                $validation[2]=array('id'=>'calle','arguments'=> "minLength: 1, maxLength: 80, invalidEmpty: true,", 'response_false'=>'Ingrese la calle');
		$validation[3]=array('id'=>'numero_exterior','arguments'=> "minLength: 1, maxLength: 5, invalidEmpty: true,", 'response_false'=>'Ingrese el numero exterior');
		
		$validation[4]=array('id'=>'colonia','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese la Colonia');
		$validation[5]=array('id'=>'codigo_postal','arguments'=> "minLength: 1, maxLength: 5, invalidEmpty: true,", 'response_false'=>'Ingrese codigo Postal');
                $validation[6]=array('id'=>'localidad','arguments'=> "minLength: 1, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre de la localidad');
		$validation[7]=array('id'=>'municipio','arguments'=> "minLength: 4, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Seleccione el Municipio');
                $validation[8]=array('id'=>'estado','arguments'=> "minLength: 5, maxLength: 30, invalidEmpty: true,", 'response_false'=>'Seleccione el Estado');
		
               	$validation[10]=array('id'=>'email','arguments'=> "format: 'email',invalidEmpty: true,", 'response_false'=>'Ingrese un correo electrónico valido');
                $validation[11]=array('id'=>'limite_credito','arguments'=> "format:'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese el limite de Credito');
                $validation[12]=array('id'=>'dias_credito','arguments'=> "format:'decimal', invalidEmpty: true,", 'response_false'=>'Ingrese los dias de Credito');
                $validation[13]=array('id'=>'nombre_corto','arguments'=> "minLength: 4, maxLength: 40, invalidEmpty: true,", 'response_false'=>'Ingrese el nombre corto');
                $validation[14]=array('id'=>'domicilio','arguments'=> "minLength: 1, maxLength: 100, invalidEmpty: true,", 'response_false'=>'Ingrese el domicilio');
                return $validation;
	} 

}
