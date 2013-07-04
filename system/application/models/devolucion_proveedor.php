<?php
/**
 * Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	DPR
 * @link
 */
class Devolucion_proveedor extends DataMapper {

	var $table = "devolucion_proveedor";
	
	// Relacion con proveedor para consultar datos directos con datamapper
 var $has_one = array('proveedor');
	

	function Devolucion_proveedor($id=null) {
				parent::DataMapper($id);
	}
	
    function detalles_devolucion($id) {
        	$d = new Devolucion_proveedor();
        	$sql = "select s.cantidad, p.descripcion,p.precio_compra, num.codigo_barras
	                from salidas as s 
	                join cproductos as p on s.cproductos_id = p.id
	                join cproductos_numeracion as num on s.cproducto_numero_id = num.id
 	                where devolucion_proveedor_id = $id";
		      //Buscar en la base de datos
		      return $d->query($sql);


    }



}
