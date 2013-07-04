<?php
class Descuento_programado extends DataMapper {
var $table= "descuentos_programados";

	function  Descuento_programado($id=null){
		parent::DataMapper($id);
	}
	
	function get_descuentos_programados_list($per_page,$offset){
		$sql="select dp.*, e.nombre, e.apaterno,e.amaterno, t.tag as tipo, e1.tag as estatus, u.username as usuario from descuentos_programados as dp left join empleados as e on e.id=dp.empleado_id left join ctipo_descuentos as t on t.id=ctipo_descuento_id left join cestatus_descuentos as e1 on e1.id=dp.estatus_descuento_id left join usuarios as u on u.id=dp.usuario_id order by id desc limit $per_page offset $offset";
		$this->query($sql);
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
	}
	
	function get_descuentos_programados_dropd() {
		$this->order_by('tag')->get();
        $array_tienda[0] = "Elija";
        foreach($this as $row)
            $array_tienda[$row->id] = $row->tag;
        return $array_tienda;
    }
    
    
 function get_descuentos($empleado){
$sql="select cd.tag,deuda_total, sum(np.monto) as monto from descuentos_programados as dp
left join nomina_empleado_detalles as np on np.referencia_id=dp.id
left join ctipo_descuentos as cd on cd.id=dp.ctipo_descuento_id
 where dp.empleado_id=$empleado and tipo_detalle_nomina_id=5 and np.empleado_id=$empleado
group by dp.empleado_id , tipo_detalle_nomina_id , np.empleado_id,dp.deuda_total,cd.tag";
		$this->query($sql);
		
			return $this;
		
	}    
    
    
    function get_descuento_programado_detalle($id){
		$sql="select dp.*, t.tag as tipo from descuentos_programados as dp  left join ctipo_descuentos as t on t.id=ctipo_descuento_id  where id=$id";
		$this->query($sql);
		if($this->c_rows > 0)
			return $this;
		else 
			return FALSE;
	}
	
}
