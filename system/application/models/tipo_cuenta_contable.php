<?php

class Tipo_cuenta_contable extends DataMapper {

    var $table = "ctipo_cuentas_contables";
    var $has_many = array(
        'cuentas_contables' => array(
            'class' => 'cuenta_contable',
            'other_field' => 'ctipo_cuenta_contable'
        )
    );

    function Tipo_cuenta_bancaria($id=null) {
        parent::DataMapper($id);
    }

    function get_cuentas_drop() {
        $tipos_cuenta = new Tipo_cuenta_contable();
        $tipos_cuenta->order_by('tag')->get();
        if (!$tipos_cuenta->exists())
            return array('Sin cuentas registradas');
        foreach ($tipos_cuenta as $tipo)
            $select[$tipo->id] = $tipo->tag;
        return $select;
    }
    
    function get_series_drop() {
        $sql = "SELECT DISTINCT LEFT(catalogo_anterior, 1) AS serie FROM ctipo_cuentas_contables ORDER BY serie";
        $tipos = new Cuenta_contable();
        $tipos->query($sql);
        $array = array();
        foreach($tipos->all as $tipo)
            $array[$tipo->serie] = $tipo->serie;
        return $array;
    }
    
    function get_tipos_cuentas_by_serie($serie) {
        $sql = "SELECT id, tag FROM ctipo_cuentas_contables WHERE LEFT(catalogo_anterior, 1) = '$serie' ORDER BY RIGHT(catalogo_anterior, 1)";
        $tipos = new Cuenta_contable();
        $tipos->query($sql);
        $array_id = array();
        $array_tags = array();
        foreach($tipos->all as $tipo) {
            $array_id[] = $tipo->id;
            $array_tags[$tipo->id] = $tipo->tag;
        }
        return array($array_id, $array_tags);
    }
}
