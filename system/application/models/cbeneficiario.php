<?php

/**
 * Modelo para Diversas acciones del modulo Almac�n fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram�re SARS
 * @link    	
 */
class Cbeneficiario extends DataMapper {

    var $table= "cbeneficiario";

    function Cbeneficiario($id = NULL) {
        parent::DataMapper($id);
    }
}