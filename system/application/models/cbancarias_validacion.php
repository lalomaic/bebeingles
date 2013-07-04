<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Cbancarias_validacion extends Model {

    function Cbancarias_validacion() {
        parent::Model();
    }

    function validacion_alta_cbancarias() {
        $validation[0] = array('id' => 'banco', 'arguments' => "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false' => 'Ingrese el Nombre del Banco');
        $validation[1] = array('id' => 'nombre_sucursal', 'arguments' => "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false' => 'Ingrese Nombre de la Sucursal');
        $validation[2] = array('id' => 'numero_sucursal', 'arguments' => "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false' => 'Ingrese el Numero de Sucursal');
        $validation[3] = array('id' => 'numero_cuenta', 'arguments' => "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false' => 'Ingrese numero de cuenta');
        $validation[4] = array('id' => 'clabe', 'arguments' => "minLength: 3, maxLength: 70, invalidEmpty: true,", 'response_false' => 'Ingrese la clave Bancaria');
        $validation[5] = array('id' => 'ctipo_cuenta_id', 'arguments' => "minValue: '1',", 'response_false' => 'Seleccione el Tipo de Pago');
        return $validation;
    }

    function validacion_comision_bancarias() {
        $validation[0] = array('id' => 'debito', 'arguments' => "format:'decimal', minLength: 1, maxLength: 6, invalidEmpty: true,", 'response_false' => 'Ingrese el Debito');
        $validation[1] = array('id' => 'credito_0m', 'arguments' => "format: 'decimal', invalidEmpty: true,", 'response_false' => 'Ingrese la Cantidad de credito a 0 meses');
        $validation[2] = array('id' => 'credito_3m', 'arguments' => "format: 'decimal', invalidEmpty: true,", 'response_false' => 'Ingrese la Cantidad de credito a 3 meses');
        $validation[3] = array('id' => 'credito_6m', 'arguments' => "format: 'decimal', invalidEmpty: true,", 'response_false' => 'Ingrese la Cantidad de credito a 6 meses');
        $validation[4] = array('id' => 'credito_9m', 'arguments' => "format: 'decimal', invalidEmpty: true,", 'response_false' => 'Ingrese la Cantidad de credito a 9 meses');
        $validation[5] = array('id' => 'credito_12m', 'arguments' => "format: 'decimal', invalidEmpty: true,", 'response_false' => 'Ingrese la Cantidad de credito a 12 meses');
        $validation[6] = array('id' => 'banco_id', 'arguments' => "minValue: '1',", 'response_false' => 'Selecciona Algun Banco');

        return $validation;
    }

}

?>
