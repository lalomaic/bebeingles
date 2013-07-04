<?php
/**
 * Modelo para Escribir el menu en la IGU fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramírez SARS
 * @link    	
 */
class Nomina_calculos extends Model{
	function Menu() {
		parent::Model();	
		$this->load->model( 'prenomina' );
		$this->load->model( 'submodulo' );
		$this->load->model( 'accion' );
	}
	
  function obtener_empleados($preid){
	$this->load->model( 'prenomina_detalle' );
	$this->load->model( 'empleado' );
	//Paso 1 Obtener los empleados y datos principales
	$prenomina=$this->prenomina->get_by_id($preid);
	$p_det=new Prenomina_detalle();
	$p_det->where('prenominas_id', $preid)->get();
	foreach ($p_det as $k) {
	  $dias_asistencia = 0;
	  $dias_asistencia = $prenomina->dias_semana - $k->dias_faltas;
	  $empl=new Empleado();
	  $empl->get_by_id($k->empleado_id);
	  if ($empl->importe_infonavit>0)
		  $importe = $empl->importe_infonavit;
	  else
		  $importe = 0;
	  $colect[$k->empleado_id]['empleado_id'] =$k->empleado_id; 
	  $colect[$k->empleado_id]['prenomina_detalle_id'] =$k->id; 
	  $colect[$k->empleado_id]['empleado'] ="$empl->nombre $empl->apaterno $empl->amaterno"; 
	  $colect[$k->empleado_id]['salario'] =$empl->salario; 
	  $colect[$k->empleado_id]['puesto'] =$empl->puesto_id; 
	  $colect[$k->empleado_id]['comision_id'] =$empl->comision_id; 
	  $colect[$k->empleado_id]['importe'] =$importe; 
	  $colect[$k->empleado_id]['dias_lab'] =$dias_asistencia; 
	  $colect[$k->empleado_id]['horas_extra'] =$k->horas_extra; 
	  
	  //Incorporar el valor de las horas extra
	  if($k->horas_extra<=9){
		$valor_he=$k->horas_extra*$empl->salario*2/8;
	  } else 
		$valor_he=$k->horas_extra*$empl->salario*2/8;
	  $colect[$k->empleado_id]['valor_horas_extra'] =$valor_he; 
	  
	 
	  //Paso 2 Calcular infonavit
	  $infonavit=$this->calc_infonavit($empl);
	  $colect[$k->empleado_id]['descuento_infonavit'] =$infonavit; 
	  

	  //Paso 3 Calcular comisiones
	  $comisiones=$this->calc_comisiones($prenomina->fecha_inicial, $prenomina->fecha_final, $empl);
	  if(!is_array($comisiones)){
		$colect[$k->empleado_id]['comision'] =$comisiones; 
		$colect[$k->empleado_id]['pares'] =0; 
	  } else {
		$colect[$k->empleado_id]['comision'] =$comisiones['comision']; 
		$colect[$k->empleado_id]['pares'] =$comisiones['pares']; 
	  }
	  
	  //Paso 4 Calcular Descuentos Programados
	  $descuentos=$this->calc_descuentos($empl,$prenomina);
	  $colect[$k->empleado_id]['descuentos_programados'] =$descuentos; 
	 }

	 return $colect;

  }
  
  /** Calcular el infonavit de un empleado
	Parametros :
	Valor_descuento_infonavit: $empl->valor_descuento_infonavit
	Zona de Empleado: $empl->smg_zona_id
	Salario minimo f(smg_zona_id, anio_actual): Tabla csalarios_minimos
	Valor constante bimestral por dia: 2(30.4)
	Return: descuento infonavit semanal
	*/
function calc_infonavit($empl,$dias_descuento=7){
       $this->load->model("salario_minimo");
       $vsm=$empl->valor_descuento_infonavit;
       if($vsm==0){
         return 0;
         exit();
       }
       //Obtener la zona del empleado y las veces de salario minimo
       $zona_id=$empl->smg_zona_id;
       $salario=new Salario_minimo();
       $salario->where('anio', date("Y"))->limit(1)->get();
       $sal_min=$salario->zona_a;
       $fact_desc_dia=(($vsm*$sal_min)+15)/(30.4);
       $descuento_semana=$fact_desc_dia*$dias_descuento;
       return $descuento_semana;

 }
  
  	/** Calcular comisiones de un empleado
	Parametros :
	Periodo de fechas
	Empleado Object DataMapper
	Return: Comision del empleado semanal
	*/

  function calc_comisiones($fecha1, $fecha2, $empl){
	$this->load->model("comision");
	$comision_id=$empl->comision_id;
	//Obtener los datos de la comision y el tipo de comision
	$com=new Comision();
	$com->get_by_id($comision_id);
	if($com->tipo_comision_id==1){
	  //Porcentaje de Venta
	  $porcentaje_comision=$com->criterio1;
	  $query=$this->db->query("select (sum(n.cobro_efectivo) + sum(n.cobro_electronico)) as venta_total from nota_remision as n where n.estatus_general_id='1' and espacios_fisicos_id=$empl->espacio_fisico_id and fecha>='$fecha1' and fecha<'$fecha2' ");
	  $resul=$query->row();
	  $comision_venta=$resul->venta_total*$porcentaje_comision/100;
	  return $comision_venta;
	  exit();  
	}
	else if($com->tipo_comision_id==2){
	  //Meta de Venta 
	  $meta_venta=$com->criterio1;
	  $comision_par=$com->criterio2;
	  $query=$this->db->query("select (sum(n.cobro_efectivo) + sum(n.cobro_electronico)) as venta_total from nota_remision as n where n.estatus_general_id='1' and espacios_fisicos_id=$empl->espacio_fisico_id and fecha>='$fecha1' and fecha<'$fecha2'");
	  $resul=$query->row();
	  if($resul->venta_total>=$meta_venta){
		//Si aplica la comision y encontrar el numero de pares vendidos
		$datos=$this->db->query("select sum(s.cantidad) as pares from salidas as s where espacios_fisicos_id='$empl->espacio_fisico_id' and fecha>='$fecha1' and fecha<'$fecha2' and s.estatus_general_id=1 and ctipo_salida_id=1 ");
		$pares=$datos->row();
		$comision=$pares->pares*$comision_par;
		$regreso['pares']=$pares->pares;
		$regreso['comision']=$comision;

		return $regreso;
	  } else 
		return 0;
		
	  exit();
	}
	else if($com->tipo_comision_id==3){
	 //Rango por Venta
	  //X-Y-Valor
	  $rangos=$com->criterio1;
	  $comision_par=$com->criterio2;
	  //Obtener los pares vendidos por el empleado en el periodo de tiempo
	  $sql="select sum(s.costo_total) as costo_total from salidas as s left join nota_remision as n on n.numero_remision_interno=s.numero_remision_id  where s.espacios_fisicos_id=$empl->espacio_fisico_id and n.estatus_general_id='1' and s.estatus_general_id='1' and ctipo_salida_id='1' and n.empleado_id=$empl->id and n.fecha>='$fecha1' and n.fecha<'$fecha2' ";
	  $datos=$this->db->query($sql);
	  $resul=$datos->row();
	  $vendido=$resul->costo_total;
	  $matriz=explode("&", $rangos);
	  $comision_par=0;
	  foreach($matriz as $lin){
		$det=explode("-", $lin);
//		  echo "----$pares_vendidos  & {$det[0]} {$det[1]} {$det[2]} <br/>";
		if($vendido>=$det[0] and $vendido<=$det[1]){
		  $comision_par=$det[2];
		  break;
		}
	  }
	  $regreso['pares']=$vendido;
	 
	  $regreso['comision']=$vendido*$comision_par/100;
	  return $regreso;
	} else
	  return 0;
  }
  
   	/** Calcular los descuentos programados del empleado
	Parametros :
	Prenomina id
	Empleado Object DataMapper
	Return: Descuento
	*/

  function calc_descuentos($empl,$prenomina){
	//Buscar descuentos_programados del empleado que aun esten en proceso
	$this->load->model("descuento_programado");
	$mp=new Descuento_programado();
	$mp->select('sum(monto_descuento_semanal) as total')->where('empleado_id', $empl->id)->where('estatus_descuento_id', 1)->where('fecha_inicio <=', $prenomina->fecha_final)->get();	
	//Regresar valor del descuento total
// 	exit();
	if($mp->total>0)
	  return ceil($mp->total);
	else
	  return 0;
  }
 
  function obtener_detalle_empleado_nomina($empleado_id, $nomina_detalle_id){
	  //Obtener los rubros del detalle del empleado en base al campo nomina_detalle_id
	  $this->load->model(array("comision", "nomina_empleado_detalle", "descuento_programado", "prestacion", "deduccion"));
	  $colect=array();
	  $ned=new Nomina_empleado_detalle();
	  //Sueldo Base = Salario diario * Dias Laborados
	  $ned->where('nomina_detalle_id', $nomina_detalle_id)->where('estatus_general_id', 1)->get();
	  if($ned->c_rows >0 ){
		foreach($ned->all as $row){
		  $r=0;$p1=0;$d1=0;
// 		  echo "$row->empleado_id - $row->tipo_detalle_nomina_id<br/>";
		  switch($row->tipo_detalle_nomina_id) {
			case 1:
				$colect['sueldo']=$row->monto;
				 break;
			  
			case 2:
				$colect['comision']=$row->monto;
				 break;
				
			case 3:
				$colect['descuento_infonavit']=$row->monto;
				 break;

			case 4:
				$colect['horas_extra']=$row->monto;
				 break;

			case 5:
				$mp=new Descuento_programado();
				$sql="select dp.id, t.tag as tipo from descuentos_programados as dp  left join ctipo_descuentos as t on t.id=ctipo_descuento_id  where dp.id=$row->referencia_id";
				$mp->query($sql);
				$colect['descuento_prog'][$r]['monto']=$row->monto;
				$colect['descuento_prog'][$r]['tipo']=$mp->tipo;
				$r+=1;
				 break;
				
			case 6:
				$p=new Prestacion($row->referencia_id);
				$colect['prestacion'][$p1]['monto']=$row->monto;
				$colect['prestacion'][$p1]['tipo']=$p->tag;
				$p1+=1;
				break;
				 
			case 7:
				$d=new Deduccion($row->referencia_id);
				$colect['deduccion'][$d1]['monto']=$row->monto;
				$colect['deduccion'][$d1]['tipo']=$d->tag;
				$d1+=1;
				break;
		  } //Fin del switch
		} //Fin del foreach
		return $colect;
	  } // Fin clausula if	  
	} //Fin function
	
 
}