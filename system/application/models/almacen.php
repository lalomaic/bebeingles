<?php
/**
 * Modelo para Diversas acciones del modulo Almacén fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Almacen extends Model{


	function Almacen()
	{
		parent::Model();
		$this->load->model( 'pr_pedido' );
		$this->load->model( 'producto' );
		$this->load->model("producto_numeracion");
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
	/**Rutina para generar las Existencias del Inventario*/
	function inventario($where, $order_by, $where1){

		//Obtener el Catálogo de productos
		$p= new Producto();
		$sql="select p.id, p.descripcion, p.precio1, m.porcentaje_utilidad,cp.id as n, cp.numero_mm from cproductos as p 
		left join cmarcas_productos as m on m.id=p.cmarca_producto_id  
		left join cproductos_numeracion as cp on cp.cproducto_id=p.id 
		$where1 order by p.descripcion";
		
		//Buscar en la base de datos
		$cp=$p->query($sql);
		if($p->c_rows>0){
	  //Formar el Array con los id
	  $catalogo=array();
	  $tentrada=0;
	  $tsalida=0;
	  foreach($cp->all as $row){
	  	$query=$this->db->query("select count(e1.id) as tentrada,e1.cproducto_numero_id as n from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and e1.cproductos_id='$row->id' GROUP BY e1.cproductos_id,e1.cproducto_numero_id order by 1 ");
	  	foreach($query->result() as $row1) {
	  		$tentrada=$row1->tentrada;
	  		$nte=$row1->n;
	  	}

	  	$query1=$this->db->query("select count(e1.id) as tsalida,e1.cproducto_numero_id as n from salidas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and e1.cproductos_id='$row->id' GROUP BY e1.cproductos_id,e1.cproducto_numero_id order by 1 ");
	  	foreach($query1->result() as $row1) {
	  		$tsalida=$row1->tsalida;
	  		$nts=$row1->n;
	  	
	  	}

	  	if($tsalida>0 or $tentrada>0){
	  		$array1=$this->existencias($row->id, $where);
	  		if($array1['existencias']!=0){
	  			$catalogo[$row->id]['id']=$row->id;
	  			$catalogo[$row->id]['descripcion']=$row->descripcion;
	  			$catalogo[$row->id]['talla']=$row->numero_mm;
	  			$precio_prom=$this->get_precio_promedio($row->id);
	  			if($precio_prom!=false)
	  				$catalogo[$row->id]['saldo']=$precio_prom;
	  			else
	  				$catalogo[$row->id]['saldo']=ceil($row->precio1*(100-$row->porcentaje_utilidad)/100);
	  			$catalogo[$row->id]['entradas']=$array1['entradas'];
	  			$catalogo[$row->id]['salidas']=$array1['salidas'];
	  			$catalogo[$row->id]['existencias']=$array1['existencias'];
	  			//$catalogo[$row->id]['talla']=$array1['existencias'];
	  		}
	  	}
	  	unset($row);
	  }
	  return $catalogo;
		} else {
			return false;
		}
		unset($catalogo); unset($cp);
	}

	function existencias($id, $where, $where_abs1='', $where_abs2=''){
		//Obtener las existencias
		//Entradas
		$result=array();
		$existencias=0;
		$query=$this->db->query("select sum(cantidad) as tentradas from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproductos_id='$id' and e1.estatus_general_id=1");
		$row=$query->row();
		$tentradas=$row->tentradas;

		$query=$this->db->query("select sum(cantidad) as tsalidas from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproductos_id='$id' and s.estatus_general_id=1 ");
		$row=$query->row();
		$tsalidas=$row->tsalidas;
		$existencias=$tentradas-$tsalidas;

		if(strlen($where_abs1)>0){
			$query=$this->db->query("select sum(cantidad) as tentradas from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id  $where_abs1 and cproductos_id='$id' and e1.estatus_general_id=1 ");
			$row=$query->row();
			$te_abs=$row->tentradas;

			$query=$this->db->query("select sum(cantidad) as tsalidas from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id  $where_abs1 and cproductos_id='$id' and s.estatus_general_id=1");
			$row=$query->row();
			$ts_abs=$row->tsalidas;

			$existencias_abs=$te_abs-$ts_abs;
			unset($query); unset($row);
		}
		if(isset($existencias_abs))
			$result['existencias_abs']=$existencias_abs;
		else
			$result['existencias_abs']=0;
		if(strlen($where_abs2)>0){
			$query=$this->db->query("select sum(cantidad) as tentradas from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id  $where_abs2 and cproductos_id='$id' and e1.estatus_general_id=1 ");
			$row=$query->row();
			$te_abs2=$row->tentradas;

			$query=$this->db->query("select sum(cantidad) as tsalidas from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id  $where_abs2 and cproductos_id='$id' and s.estatus_general_id=1");
			$row=$query->row();
			$ts_abs2=$row->tsalidas;

			$existencias_abs2=$te_abs2-$ts_abs2;
			unset($query); unset($row);
		}
		if(isset($existencias_abs2))
			$result['existencias_abs2']=$existencias_abs2;
		else
			$result['existencias_abs2']=0;
		$result['entradas']=$tentradas;
		$result['salidas']=$tsalidas;
		$result['existencias']=$existencias;
		//print_r($result);
		return $result;
		unset($result);
	}

	function existencias_numero($id, $p_num, $where){
		//Obtener las existencias
		//Entradas
		$result=array();
		$existencias=0;
                $apartados=0;
		$query=$this->db->query("select sum(cantidad) as tentradas from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproducto_numero_id='$p_num' and e1.estatus_general_id='1' and cproductos_id='$id'");

		$row=$query->row();
		$tentradas=$row->tentradas;

		$query=$this->db->query("select sum(cantidad) as tsalidas from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproducto_numero_id='$p_num' and cproductos_id='$id' and s.estatus_general_id='1'");
                
		$row=$query->row();
		$tsalidas=$row->tsalidas;
                
                $query=  $this->db->query("select sum(cantidad) as apartados 
from detalle_apartado as dt 
left join espacios_fisicos as ef on ef.id=dt.espacio_fisico_id 
left join empresas as e on e.id=ef.empresas_id 
$where 
and entregado='0'
and dt.cproducto_numero_id=$p_num
and dt.estatus_general_id='1'
and dt.cproducto_id=$id");
                $row=$query->row();
                $apartados=$row->apartados;
              if($row->apartados==''){
                         $apartados=0;            
 }
		$existencias=$tentradas-$tsalidas;
                $disponibles=$existencias-$apartados;
		$result['entradas']=$tentradas;
		$result['salidas']=$tsalidas;
		$result['existencias']=$existencias;
                $result['apartados']=$apartados;
                $result['disponibles']=$disponibles;
		return $result;
	}
	function pre_existencia($producto_id, $espacio){
		//Funcion para saber si hay movimiento de un producto dado en un espacio
		$query=$this->db->query("select id from entradas where cproducto_numero_id=$producto_id and espacios_fisicos_id=$espacio and estatus_general_id=1  limit 1");
		if($query->num_rows()==1)
			return true;
		else {
			$query=$this->db->query("select id from salidas where cproducto_numero_id=$producto_id and espacios_fisicos_id=$espacio and estatus_general_id=1 limit 1");
		if($query->num_rows()==1)
			return true;
		else 
			return false;
		}
	}


	function producto_comprometido($id){
		$comprometido=$this->db->query("select sum(cantidad) as comp from cl_detalle_pedidos as cd left join cl_pedidos as cl on cl.id=cd.cl_pedidos_id where cl.ccl_estatus_pedido_id=2 and cd.estatus_general_id=1 and cd.cproductos_id='$id'");
		if($comprometido->num_rows() > 0){
	  foreach($comprometido->result() as $row) {
	  	$comp=$row->comp;
	  }
	  return $comp;
		} else
	  return 0;
	}

	function get_precio_promedio($id){
		$query=$this->db->query("select costo_unitario from entradas where cproductos_id=$id and estatus_general_id=1 and costo_unitario is not null and ctipo_entrada=2  order by fecha desc limit 3");
		$preciot=0;
		$n=$query->num_rows();
		if($n>0){
			foreach($query->result() as $row){
				$preciot+=$row->costo_unitario;
			}
			$precio_prom=$preciot/$n;
			return $precio_prom;
		} else
			return false;
	}

	function get_dia_semana($d){
		if($d==1){
			$x="Lunes";
		} else if($d==2){
			$x="Martes";
		} else if($d==3){
			$x="Miercoles";
		} else if($d==4){
			$x="Jueves";
		} else if($d==5){
			$x="Viernes";
		} else if($d==6){
			$x="Sábado";
		} else if($d==7){
			$x="Domingo";
		} else {
			$x="Todos";
		}
		return $x;
	}

	function existencias_rep_marcas_comparativo($id, $where, $where_lotes, $espacios){
		//Obtener las existencias

		//Entradas
		$result=array();
		$existencias=0;
		$query=$this->db->query("select sum(cantidad) as tentradas from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproductos_id='$id' and e1.lote_id in ($lotes_str) and e1.estatus_general_id=1");
		$row=$query->row();
		$tentradas=$row->tentradas;


		$query=$this->db->query("select sum(cantidad) as tsalidas from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join empresas as e on e.id=ef.empresas_id $where and cproductos_id='$id' and s.lote_id in ($lotes_str) and s.estatus_general_id=1");
		$row=$query->row();
		$tsalidas=$row->tsalidas;
		$existencias=$tentradas-$tsalidas;
		$result['entradas']=$tentradas;
		$result['salidas']=$tsalidas;
		$result['existencias']=$existencias;

		//	print_r($result);
		return $result;
	}

	function inventario_ejecutivo($where, $order_by){
		//Obtener el Catálogo de marcas
		$p= new Marca_producto();
		//$sql="select p.id, p.descripcion, p.precio1 from cproductos as p  $where1 order by p.descripcion";
		$sql="select id, tag  from cmarcas_productos  as p  order by p.tag asc";
		//Buscar en la base de datos
		$cp=$p->query($sql);
		if($p->c_rows>0){
			//Formar el Array con los id
			$catalogo=array();
			$tentrada=0;
			$tsalida=0;
			foreach($cp->all as $row) {
				$query=$this->db->query("select sum(e1.cantidad) as tentrada, sum(p.precio1 * e1.cantidad) as precio_venta from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join cproductos as p on p.id=e1.cproductos_id left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id  $where and cmarca_producto_id='$row->id' and e1.estatus_general_id=1 ");
				foreach($query->result() as $row1) {
					$tentrada=$row1->tentrada;
					$precio_venta_entrada=$row1->precio_venta;
				}
				$query1=$this->db->query("select sum(s.cantidad) as tsalida, sum(p.precio1 * s.cantidad) as precio_venta from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id $where and cmarca_producto_id='$row->id' and s.estatus_general_id=1 ");
				foreach($query1->result() as $row1) {
					$tsalida=$row1->tsalida;
					$precio_venta_salida=$row1->precio_venta;
				}

				if($tsalida>0 or $tentrada>0){
					$catalogo[$row->id]['id']=$row->id;
					$catalogo[$row->id]['descripcion']=$row->tag;
					$catalogo[$row->id]['entradas']=$tentrada;
					$catalogo[$row->id]['salidas']=$tsalida;
					$catalogo[$row->id]['existencias']=$tentrada-$tsalida;
					$catalogo[$row->id]['precio_venta']=abs($precio_venta_entrada-$precio_venta_salida);
					//				if($precio_venta_salida>$precio_venta_entrada and $tentrada>0)
						//						show_error("select sum(s.cantidad) as tsalida, sum(p.precio1 * s.cantidad) as precio_venta from salidas as s left join espacios_fisicos as ef on ef.id=s.espacios_fisicos_id left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id $where and cmarca_producto_id='$row->id' and s.estatus_general_id=1 <br/> select sum(e1.cantidad) as tentrada, sum(p.precio1 * e1.cantidad) as precio_venta from entradas as e1 left join espacios_fisicos as ef on ef.id=e1.espacios_fisicos_id left join cproductos as p on p.id=e1.cproductos_id left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id  $where and cmarca_producto_id='$row->id' and e1.estatus_general_id=1 ");
				}
				unset($row);
			}
			return $catalogo;
		} else {
			return false;
		}
		unset($catalogo); unset($cp);
	}

	function get_pagos_entre_tiendas_periodo($fecha1a, $fecha2a){
		//Fecha
		$this->load->model("entrada");
		if($fecha1a=="" and strlen($fecha2a)>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2a=="" and strlen($fecha1a)>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1a);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2a);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		//Periodo
		$espacios=$this->espacio_fisico->get_espacios_tiendas();
		$periodo_mtrx=array(); $n=0;
		foreach($espacios->all as $row){
			foreach($espacios->all as $envia){
				$e=new Entrada();
				$sql="select sum(e.cantidad) as cantidad, sum(e.costo_total) as costo_total from traspasos_tiendas as tt left join entradas as e on e.id=tt.entrada_id where espacio_fisico_recibe_id=$row->id and espacio_fisico_id=$envia->id and fecha_entrada>='$fecha1' and fecha_entrada<'$fecha2' and entrada_id>0 and e.estatus_general_id=1 and deuda_tiendas=0";
				$e->query($sql);
				//  				if($row->id==4 and $envia->id==17)
					//  				echo $sql."<br/>";
				//Acumulado
				$e1=new Entrada();
				$sql1="select sum(e.costo_total) as costo_total from traspasos_tiendas as tt left join entradas as e on e.id=tt.entrada_id where espacio_fisico_recibe_id=$row->id and espacio_fisico_id=$envia->id  and fecha_entrada<'$fecha1' and entrada_id>0 and e.estatus_general_id=1 and deuda_tiendas=0";
				$e1->query($sql1);

				if($e1->costo_total>0 or $e->costo_total>0){
					$periodo_mtrx[$n]['recibe']=$row->id;
					$periodo_mtrx[$n]['envia']=$envia->id;
					$periodo_mtrx[$n]['acumulado']=$e1->costo_total;
					$periodo_mtrx[$n]['costo_total']=$e->costo_total;
					$n+=1; unset($e); unset($e1);
				}
			}
		}
		return $periodo_mtrx;
	}

	function get_listado_deuda_tiendas_trans($envia_id, $recibe_id, $fecha1){
		$e1=new Entrada();
		$sql1="select e.fecha, e.id, costo_total from traspasos_tiendas as tt left join entradas as e on e.id=tt.entrada_id where espacio_fisico_recibe_id=$recibe_id and espacio_fisico_id=$envia_id  and fecha_entrada<'$fecha1' and entrada_id>0 and e.estatus_general_id=1 and deuda_tiendas=0 order by e.fecha asc";
		$e1->query($sql1);
		if($e->c_rows>0)
			return $e;
		else
			return FALSE;
	}

	function get_meses_dropd(){
		$meses[0]="Elija";
		$meses[1]="Enero";
		$meses[2]="Febrero";
		$meses[3]="Marzo";
		$meses[4]="Abril";
		$meses[5]="Mayo";
		$meses[6]="Junio";
		$meses[7]="Julio";
		$meses[8]="Agosto";
		$meses[9]="Septiembre";
		$meses[10]="Octubre";
		$meses[11]="Noviembre";
		$meses[12]="Diciembre";
		return $meses;
	}
	function get_anios_dropd(){
		$ai=2011;
		$aa=date("Y");
		$dif=$aa - $ai;
		if($dif>0){
			for($x=0;$x<=$dif;$x++){
				$anio[$ai+$x]=$ai+$x;
			}
		} else
			$anio[$ai]=$ai;
		return $anio;
	}

	function buscar_producto_existencia($id,$p_num, $where){
		//Obtener los pn_id del producto papa
		//$numeracion=$this->producto_numeracion->get_numeracion_by_producto($id);
            $numeracion=$this->producto_numeracion->get_numeracion_by_producto($id,$p_num);
		if($numeracion!=false){
			foreach($numeracion->all as $row){
				$colect[$row->id]['talla']="# ". ($row->talla);
                                $colect[$row->id]['tag']=($row->tag);
				$existencia=$this->existencias_numero($id,$p_num, $where);
				$colect[$row->id]['existencias']=$existencia['existencias'];
                                $colect[$row->id]['apartados']=$existencia['apartados'];
                                $colect[$row->id]['disponibles']=$existencia['disponibles'];
			}
			return $colect;
		} else
			return false;
		//Luego obtener la existencia de cada uno de ellos
	}
       /** Funcion para calcular salidas para traspasos en base a producto y espacio fisico**/
    function salidas_almacen_acc_lotes($cproductos_id, $espacio_fisico_envia_id, $cantidad){
		//Obtener las entradas para generar las salidas
		$q=new Entrada();
		$q->where('cproductos_id', $cproductos_id)->where('espacios_fisicos_id',$espacio_fisico_envia_id)->where('estatus_general_id',1)->order_by("id asc")->get();
		$colect=array(); $cantidad_bucle=$cantidad;
		if($q->c_rows>0){
			foreach($q->all as $row){
				$restante=$row->cantidad-$row->salidas;
				if($cantidad_bucle>0){
					if($cantidad_bucle<=$restante){
						$colect[$row->id]['cantidad']=$cantidad_bucle;
						$colect[$row->id]['lote']=$row->lote;
						$colect[$row->id]['salidas']=$row->salidas+$cantidad_bucle;
						$cantidad_bucle=0;
						break;
					} else if($cantidad_bucle>$restante){
						$colect[$row->id]['cantidad']=$restante;
						$colect[$row->id]['lote']=$row->lote;
						$colect[$row->id]['salidas']=$row->cantidad;
						$cantidad_bucle-=$restante;
					}
				}
			}
			return $colect;
		} else
			return false;
	}     
        
        
        
        
	function get_costo_promedio($id) {
		$query = $this->db->query("select costo_unitario from entradas where cproductos_id=$id and estatus_general_id=1 and costo_unitario is not null and ctipo_entrada=2  order by fecha desc limit 3");
		$preciot = 0;
		$n = $query->num_rows();
		if ($n > 0) {
			foreach ($query->result() as $row) {
				$preciot+=$row->costo_unitario;
			}
			$precio_prom = $preciot / $n;
			return $precio_prom;
		} else {
			$query = $this->db->query("select p.precio1, m.porcentaje_utilidad from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id where p.id = $id");
			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					return ceil($row->precio1 * (100 - $row->porcentaje_utilidad) / 100);
				}
			}else
				return 0;
		}
	}

}
