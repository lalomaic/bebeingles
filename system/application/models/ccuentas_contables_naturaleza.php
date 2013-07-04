<?php

class Ccuentas_contables_naturaleza extends DataMapper {

    var $table = "ccuentas_contables_naturaleza";

    function Ccuentas_contables_naturaleza ($id=null) {
        parent::DataMapper($id);
    }

    function cuenta_naturaleza_drop() {
        $tipo_naturaleza = new Ccuentas_contables_naturaleza ();
        $tipo_naturaleza->order_by('tag')->get();
        if (!$tipo_naturaleza->exists())
            return array('Sin cuentas registradas');
        foreach ($tipo_naturaleza as $natu)
            $select[$natu->id] = $natu->tag;
        return $select;
    }    
}
