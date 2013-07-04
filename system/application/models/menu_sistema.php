<?php

class Menu_sistema extends DataMapper {

    var $table = "menu_sistema";

    function Menu_sistema($id = null) {
        parent::DataMapper($id);
    }

    function get_menu_usuario($usuario_id){
        $m = new Menu_sistema();
        $sql = "select m.*, (p.estatus_id = 1) as permiso, p.id as pid
                from menu_sistema as m
                left join usuarios_permisos_pv as p on p.menu_sistema_id = m.id and p.usuarios_id = $usuario_id
                order by m.id";
        $m->query($sql);
        return $m;
        
    }
}
