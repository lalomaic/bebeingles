<?php

/**
 * Modelo para Escribir el menu en la IGU fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Diversos extends Model{


	function Diversos()
	{
		parent::Model();
		$this->load->model( 'pr_pedido' );
	}
	/**Obtener el No de Lote para un Pedido*/
	function get_no_lote(){
		$pr=new Pr_pedido();
		$pr->select('no_lote');
		$pr->select_max('no_lote');
		$pr->get();
		if(is_numeric($pr->no_lote)==false){
			$pr->no_lote=1;
		}
		if($pr->c_rows>0){
			return $pr->no_lote;
		} else {
			return FALSE;
		}
	}

	function precios_sucursal($producto_str, $cmarca_id, $efisicos){
		//1 Obtener precios de lista
		if(strlen($producto_str)>0){
			$where = " and p.descripcion like '%$producto_str%' ";
		} else {
			$where = " and p.cmarca_producto_id ='$cmarca_id' ";
		}
		$productos=$this->db->query("select id, descripcion, precio1, cfamilia_id from cproductos as p where estatus_general_id=1 $where order by descripcion");
		//2 Espacios contemplados
		$espacios=implode(',', $efisicos);
		$espacios_fisicos=$this->db->query("select id, tag, descuento from espacios_fisicos where id in ($espacios) order by tag");

		//3 Indicar si existen promociones para el producto o marca
		$promociones=$this->db->query("select pr.*, p.descripcion from promociones  as pr left join cproductos as p  on pr.cproducto_id=p.id where p.estatus_general_id=1 $where order by p.descripcion");

		//4 Precios Sucursales
		$precios_sucursal=$this->db->query("select ps.*, p.descripcion from precios_sucursales as ps left join cproductos as p on ps.cproducto_id=p.id where p.estatus_general_id=1 $where order by descripcion");

		/** integrar los datos de los querys en una sola matriz asociativa por cada producto*/
		$prod_mtr=array();

		foreach($productos->result() as $row){
			//			echo $row->id."<br/>";
			$prod_mtr[$row->id]['id']=$row->id;
			$prod_mtr[$row->id]['descripcion']=$row->descripcion;
			$prod_mtr[$row->id]['cfamilia_id']=$row->cfamilia_id;
			$prod_mtr[$row->id]['precio_lista']=round($row->precio1,0);

			//Agregar el descuento por espacio_fisico
			foreach($espacios_fisicos->result() as $esp){
				$prod_mtr[$row->id]['descuento_espacio_'.$esp->id]=round($esp->descuento,0);
				if($promociones->num_rows()>0){
					//Buscar en las promociones
					foreach($promociones->result() as $prom){
						$prom_esp=explode(",", $prom->espacios_fisicos);
						//Validar si el espacio del bucle y el producto coinciden agregar una entrada
						if(array_key_exists($esp->id, $prom_esp) and $row->id==$prom->cproducto_id){
							$prod_mtr[$row->id]['promocion_espacio_'.$esp->id]=$prom->precio_venta;
						} else
							$prod_mtr[$row->id]['promocion_espacio_'.$esp->id]=0;
					}
				} else
					$prod_mtr[$row->id]['promocion_espacio_'.$esp->id]=0;
				//Incorporar los precios por sucursal
				if($precios_sucursal->num_rows()>0){
					foreach($precios_sucursal->result() as $pre_suc){
						if($pre_suc->espacios_fisicos_id==$esp->id and $row->id==$pre_suc->cproducto_id){
							$prod_mtr[$row->id]['dif_precio_espacio_'.$esp->id]=$pre_suc->diferencia_pesos;
							$prod_mtr[$row->id]['precio_espacio_'.$esp->id]=round(($row->precio1+$pre_suc->diferencia_pesos),0);
						}
					}
				} else
					$prod_mtr[$row->id]['precio_espacio_'.$esp->id]=0;
			}
		}
		if(count($prod_mtr)==0){
			$datos['prod_mtr']=false;
			$datos['espacios_fisicos']=false;
		} else {
			$datos['prod_mtr']=$prod_mtr;
			$datos['espacios_fisicos']=$espacios_fisicos;
		}
		return $datos;
	}
}
