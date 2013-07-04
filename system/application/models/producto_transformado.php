<?php

/**
 * Producto_transformado Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Producto_transformado extends DataMapper {

	var $table= "productos_transformados";



	function  Producto_transformados($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Producto_transformado
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_productos_transformados()
	{
		// Create a temporary user object
		$e = new Producto_transformado();
		//Buscar en la base de datos
		$e->where('estatus_general_id','1');
		$e->order_by('id');
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}
	/**
	 * get_Producto_transformado
	 *
	 * Obtiene los datos de Producción a partir de su id.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_producto_transformado($id)
	{
		// Create a temporary user object
		$u = new Producto_transformado();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_ultimo_precio_salida($id)
	{
		$query=$this->db->query("select costo_unitario from salidas where cproductos_id=$id and estatus_general_id=1 and costo_unitario is not null and ctipo_salida_id=1 order by fecha desc limit 1");
		$n=$query->num_rows();
		if($n>0){
			foreach($query->result() as $row) {
				$precio=$row->costo_unitario;
			}
			return $precio;
		} else {
			return false;
		}
	}

	function get_ultimo_precio_entrada($id)
	{
		$query=$this->db->query("select costo_unitario from entradas where cproductos_id=$id and estatus_general_id=1 and costo_unitario is not null and ctipo_entrada=1 order by fecha desc limit 1");
		$n=$query->num_rows();
		if($n>0){
			foreach($query->result() as $row) {
				$precio=$row->costo_unitario;
			}
			return $precio;
		} else {
			return false;
		}
	}

}
