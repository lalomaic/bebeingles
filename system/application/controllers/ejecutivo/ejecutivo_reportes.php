<?php
class Ejecutivo_reportes extends Controller {
	function Ejecutivo_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		$this->assetlibpro->add_css('autocomplete.css');
		$this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("graficas");
		$this->load->model("almacen");
		$this->load->model("subfamilia_producto");
		$this->load->model("entrada");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}


	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title("$subfuncion");
		$accion_id=$this->accion->get_id("$subfuncion");
		$row=$this->usuario->get_usuario($usuarioid);
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;
		$data['ruta']=$ruta;
		$data['controller']=$controller;
		$data['funcion']=$funcion;
		$data['subfuncion']=$subfuncion;
		$data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);

		//Validacion del arreglo del menu, usuarioid y permisos especificos
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE){
			$main_view=true;
			if ($subfuncion=="bitacora_ventas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/bitacora_ventas";
				$data['funcion']=$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				// 				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
				//$this->load->view("almacen/rep_productos", $data);
			} else if($subfuncion=="rep_comision_prod"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				# seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$f=new Familia_producto();
				$data['familias']=$f->select('id,tag,clave')->get()->all;
				if(count($data['familias'])==0)
					show_error('No existen familias');
			} else if($subfuncion=="rep_ventas_detalle"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				# seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$f=new Familia_producto();
				$data['familias']=$f->select('id,tag,clave')->get()->all;
				if(count($data['familias'])==0)
					show_error('No existen familias');
				$data['subfamilias']=$this->subfamilia_producto->get();

			} else if ($subfuncion=="hora_ventas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/hora_ventas";
				$data['funcion']=$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['espaciosf']=$this->espacio_fisico->get_espacios_tiendas();
			} else if ($subfuncion=="tickets_semana"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/tickets_semana";
				$data['funcion']=$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['espaciosf']=$this->espacio_fisico->get_espacios_tiendas();
			} else if ($subfuncion == "rep_top_productos") {
				//Cargar los datos para el formulario
				$data['frames'] = 1;
				$data['principal'] = $ruta . "/" . $subfuncion;
				$data['funcion'] = $subfuncion;
				$data['espaciosf'] = $this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("$ruta/hora_ventas", $data);
			}  else if ($subfuncion == "rep_ejecutivo_compras") {
				//Cargar los datos para el formulario
				$data['frames'] = 1;
				$data['principal'] = $ruta . "/" . $subfuncion;
				$data['funcion'] = $subfuncion;
				$data['espacios'] = $this->espacio_fisico->get_espacios_almacenes();
				//$this->load->view("$ruta/hora_ventas", $data);
			} else if ($subfuncion=="rep_gastos_tiendas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/rep_gastos_tiendas";
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if($subfuncion=="rep_ventas_empleado"){
                        $data['principal']=$ruta."/".$subfuncion;
                        
                            
                        }else if ($subfuncion=="rep_ventas_gerencia") {
                            $data['principal']=$ruta."/".$subfuncion;
                            $this->load->model("espacio_fisico");
                            $data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
                            //$data['espacios']="Todas";
                        }else if ($subfuncion=="rep_ventas_marcas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if ($subfuncion=="rep_comparativo_periodos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
			} else if ($subfuncion=="rep_resurtidos_marcas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if ($subfuncion=="rep_comparativo_marcas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if ($subfuncion=="rep_comparativo_compras"){
				$this->load->model("temporada_producto");
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				$data['temporada']=$this->temporada_producto->get_temporadas_mtrx();
				//$this->load->view("almacen/rep_productos", $data);
			}  else if ($subfuncion=="rep_comparativo_traspasos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if ($subfuncion=="rep_comparativo_estacionado"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				//$this->load->view("almacen/rep_productos", $data);
			} else if ($subfuncion=="rep_rentabilidad"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				//$this->load->view("almacen/rep_productos", $data);
			}
			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
				unset($data);
			} else {
				redirect(base_url()."index.php/inicio/logout");
			}
		}
	} // end method formulario ##################

	function rep_graficas_ventas_ub() {  // BEGIN method rep_productos_pdf
		global $ruta;
		$filtro=array();
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if (count($filtro))
			$where = " ".implode(" and ", $filtro)." ";
		else
			$where = "";

		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2 = $fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$fecha2=date("Y-m-d");
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2=$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];

		}
		$where .=" n.fecha>='$fecha1' and n.fecha<='$fecha2' ";
		$where_g =" where g.fecha>='$fecha1' and g.fecha<='$fecha2' ";
		$where_o =" where oe.fecha>='$fecha1' and oe.fecha<='$fecha2' ";
		$where_c =" where c.fecha_corte>='$fecha1' and c.fecha_corte<='$fecha2' ";
		$where_s =" where s.fecha>='$fecha1' and s.fecha<='$fecha2 23:59:00' ";

		//Datos Grafica General
		$sql_general="select distinct(n.espacios_fisicos_id) as espacio_id, sum(n.importe_total) as venta_total, sum(cobro_vales) as devolucion, sum(n.importe_total) - sum(cobro_vales) as venta_des, ef.tag as tag from nota_remision as n left join espacios_fisicos as ef on ef.id=n.espacios_fisicos_id where n.estatus_general_id='1' and $where group by n.espacios_fisicos_id, tag order by venta_des desc ";

		$global=$this->db->query($sql_general);
		if($global->num_rows()==0) show_error('No hay registros que cumplan los criterios.');
		$etiquetas=array();
		$valoresg=array();
		$r=1;
		//echo $sql_general;
		foreach($global->result() as $row){
			if($row->tag!=''){
				$colect[$row->espacio_id]['ventas']=round($row->venta_total-$row->devolucion,0);

				//Pares vendidos
				$datos=$this->db->query("select sum(s.cantidad) as pares from salidas as s $where_s  and espacios_fisicos_id='$row->espacio_id' and s.estatus_general_id=1 and ctipo_salida_id=1");
				if($datos->num_rows()==1)
					$pares=$datos->row();
				else
					$pares->pares=0;
				//Devoluciones
				$dev=$this->db->query("select sum(d.cantidad) as devoluciones from devoluciones as d where fecha_devolucion>='$fecha1' and fecha_devolucion<='$fecha2' and espacio_fisico_id='$row->espacio_id' and estatus_general_id=1 ");
				if($dev->num_rows()==1){
					$pares_dev=$dev->row();
					$pares->pares-=$pares_dev->devoluciones;
				}

				//Gastos
				$datos=$this->db->query("select sum(g.monto) as monto from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id $where_g and g.espacios_fisicos_id='$row->espacio_id' and g.estatus_general_id=1 and ctipo_gasto_id in (1,2) ");
				if($datos->num_rows()==1)
					$t=$datos->row();
				else
					$t->monto=0;
				//Fiscal
				$datos1=$this->db->query("select sum(c.venta_fiscal) as monto from cortes_diarios as c  $where_c and espacios_fisicos_id='$row->espacio_id'");

				if($datos1->num_rows()==1)
					$t1=$datos1->row();
				else
					$t1->monto=0;

				//Otros Egresos
				$datos=$this->db->query("select sum(oe.monto) as monto from otros_egresos_detalles as oe left join cotros_egresos as co on co.id=oe.cotros_egresos_id $where_o and espacios_fisicos_id='$row->espacio_id' and oe.estatus_general_id=1");
				if($datos->num_rows()==1)
					$otros=$datos->row();
				else
					$otros->monto=0;


				$colect[$row->espacio_id]['tag']=$row->tag;
				$colect[$row->espacio_id]['venta']=$row->venta_total-$row->devolucion;
				$colect[$row->espacio_id]['gastos']=$t->monto;
				$colect[$row->espacio_id]['pares']=$pares->pares;
				$colect[$row->espacio_id]['fiscal']=$t1->monto;
				$colect[$row->espacio_id]['otros']=$otros->monto;
				$colect[$row->espacio_id]['efectivo']=$row->venta_total-$row->devolucion-$t->monto-$t1->monto-$otros->monto;

				//Gráfica
				$valoresg[$r]=round($row->venta_total-$row->devolucion-$t->monto-$t1->monto,0);
				if($valoresg[$r]<0)
					$valoresg[$r]=1;
				$etiquetas[$r]=$row->tag;
				$r+=1;
				unset($row);
			}
		}
		//print_r($valoresg);
		$title="Grafica de Ventas Globales periodo: {$_POST['fecha1']} a {$_POST['fecha2']}";
		$data['title']=$title;
		$this->graficas->ventas_globales($valoresg, $etiquetas, "Ventas Globales");
		$data['global']=$colect;
		$this->load->view("$ruta/rep_graficas_ventas_ub", $data);
		unset($data);
	} // END method rep_productos_pdf #########################################################

	function rep_comision_prod_pdf() { // BEGIN method rep_comision_prod_pdf
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		global $ruta;
		$periodo1='';
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if($fecha1 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		if($fecha2 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		$f2 = false;
		$fentrada='fecha';
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$periodo1 = "(left($fentrada,10) >= '$fecha1' and left($fentrada,10) <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$periodo1 = "(left($fentrada,10) >= '$fecha2' and left($fentrada,10) <= '$fecha1')";
			else
				$periodo1 = "$fentrada LIKE '$fecha1%'";
		}
		elseif($f1)
		$periodo1 = "$fentrada LIKE '$fecha1%'";
		elseif($f2)
		$periodo1 = "$fentrada LIKE '$fecha2%'";
		# si va a llevar rango, agregar el "AND" que va en el "WHERE"
		if($f1 || $f2)
			$periodo1='AND '.$periodo1;
		# obtener la comision
		$comision=abs((float)$this->input->post('comision'));
		if($comision > 100)
			show_error('El porcentaje debe estar entre 0 y 100');
		# obtener los espacios fisicos
		$espacios=array();
		$familias=array();
		$subfamilias=array();
		$esp_ids=array();
		$this->db->select("id,tag as nombre")->order_by("id,nombre");
		if((int)$this->input->post('espacio')>0)
			$this->db->where('id',(int)$this->input->post('espacio'));
		else
			show_error('No se ha seleccionado un espacio fisico');
		$result=$this->db->get('espacios_fisicos')->result();
		foreach($result as $row)
		{
			$espacios[$row->id]=$row->nombre;
			$esp_ids[]=$row->id;
		}
		# las familias
		$res=$this->db->select("id,tag")->get('cproductos_familias')->result();
		if(count($res)==0)
			show_error('No hay familias');
		foreach($res as $row)
		{
			$familias[$row->id]=$row->tag;
		}
		# ahora las subfamilias
		$res=$this->db->select("id,tag")->get('cproductos_subfamilias')->result();
		if(count($res)==0)
			show_error('No hay subfamilias');
		foreach($res as $row)
		{
			$subfamilias[$row->id]=$row->tag;
		}
		$bloques=array();
		# Estructura:
		# Espacio > Familia > Subfamilia > Producto
		# construir la estructura
		$productos=array();
		$iniciales=array();
		# ahora los productos
		$this->db->select("id,descripcion ,presentacion,comision_venta");
		if($comision==0)# si la comision no es establecida para el reporte, excluir productos con 0 comision
			$this->db->where("comision_venta >",'0');
		if((int)$_POST['familia'] > 0)
			$this->db->where("cfamilia_id",$_POST['familia']);
		if((int)$this->input->post('subfamilia') > 0)
			$this->db->where("csubfamilia_id",$_POST['subfamilia']);
		if((int)$this->input->post('producto') > 0)
			$this->db->where("id",$_POST['producto']);
		$this->db->order_by('descripcion ASC, presentacion ASC');
		$res=$this->db->get('cproductos')->result();
		if(count($res)==0)
			show_error('No hay productos');
		foreach($res as $row)
		{
			$productos[$row->id]=array(
					'descripcion'=>$row->descripcion,
					'presentacion'=>$row->presentacion,
					'comision'=>$row->comision_venta,
					'comision'=>($comision > 0)?($comision):($row->comision_venta)
			);
			$prod_ids[]=$row->id;
		}
		foreach($prod_ids as $pid)
		{
			# obtener los datos de entradas/salidas
			$sql="SELECT SUM(cantidad) AS cantidad, AVG(costo_unitario) AS costo_prom, SUM(costo_total) AS costo_total FROM salidas WHERE (espacios_fisicos_id in (".implode(',',$esp_ids).") AND ctipo_salida_id = '1' AND estatus_general_id = '1' AND cproductos_id = '".$pid."' $periodo1)";
			$result=$this->db->query($sql)->result();
			if (count($result) > 0)
			{
				foreach($result as $row)
				{
					if((float)$row->cantidad > 0)
						$bloques[$esp_ids[0]][$pid][]=array('cantidad'=>$row->cantidad,'costo'=>$row->costo_prom,'total'=>$row->costo_total,'comision'=>(float)$productos[$pid]['comision'],'comision_total'=>((float)$productos[$pid]['comision']/100)*((float)$row->costo_total));
				}
			}
		}
		# checar que haya datos
		if(count($bloques)==0)
			show_error('No hay registros que cumplan los criterios.');
		$vista=array();
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$vista['periodo']=$fecha1.' a '.$fecha2;
		}
		else
			$vista['periodo']='A LA FECHA '.date("d-m-Y");
		$vista['bloques']=$bloques;
		$vista['espacios']=$espacios;
		$vista['familias']=$familias;
		$vista['subfamilias']=$subfamilias;
		$vista['productos']=$productos;
		$vista['title'] = $this->input->post('title');
		if($comision > 0){
			$vista['comision']=$comision;
		} else {
			$vista['comision']=0;
		}
		$this->load->view("$ruta/rep_comision_prod_pdf", $vista);
		unset($data);
	} // END method rep_comision_prod_pdf #########################################################

	function rep_ventas_detalle_pdf() { // BEGIN method rep_ventas_detalle_pdf
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		global $ruta;
		$periodo1='';
		if(isset($_POST['fecha1'])==false) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			//		   $fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]));
		}
		//Procesar las fechas para tener semanas completas
		$dia_semana1=date("w", strtotime($fecha1));
		$dia_semana2=date("w", strtotime($fecha2));
		$fecha1_mod=date("Y-m-d", strtotime($fecha1)-(($dia_semana1-1) * 24 * 60 * 60));
		$fecha2_mod=date("Y-m-d", strtotime($fecha2)+((7-$dia_semana2) * 24 * 60 * 60) + (24* 3600));
		// 		echo $dia_semana1."<br/>";
		// 		echo $fecha2_mod."<br/>";
		//Agrupar por meses y semanas los periodos parciales de todo el periodo
		$dias_totales=(strtotime($fecha2_mod)-strtotime($fecha1_mod))/(24 * 3600);
		$semanas_rango=$dias_totales/7;
		//Generar array con los rangos de las semanas
		$semanas=array();
		$fecha_i=$fecha1_mod;
		for($c=0;$c<$semanas_rango;$c++){
			$semanas[$c]['fecha1']="'$fecha_i'";
			$fecha_f=(strtotime($fecha_i))+(7*24*3600);
			$semanas[$c]['fecha2']="'".date("Y-m-d", $fecha_f)."'";
			$fecha_i=date("Y-m-d", $fecha_f);
		}
		//print_r(array_values($semanas));

		$where =" where s.ctipo_salida_id=1 and s.estatus_general_id=1 ";

		$data['fecha1']=$fecha1_mod;
		$data['fecha2']=$fecha2_mod;
		# obtener los espacios fisicos
		$espacios=array();
		$familias=array();
		$subfamilias=array();
		$esp_ids=array();
		//Empresas
		if((int)$this->input->post('empresa')>0)
			$where.='and e.empresas_id='. $this->input->post('empresa'). " ";
		else
			$where.='and e.empresas_id>0 ';
		//Espacios
		if((int)$this->input->post('espacio')>0)
			$where.='and s.espacios_fisicos_id='. $this->input->post('espacio'). " ";
		else if ($this->input->post('espacio')==0)
			$where.='and s.espacios_fisicos_id>0 ';
		else
			$where.='and s.espacios_fisicos_id>0 ';
		//			show_error('No se ha seleccionado un espacio fisico');
		//Familias
		if((int)$this->input->post('familia')>0)
			$where.='and p.cfamilia_id ='. $this->input->post('familia'). " ";
		//SubFamilias
		if((int)$this->input->post('subfamilia')>0)
			$where.='and p.csubfamilia_id='. $this->input->post('subfamilia'). " ";
		if((int)$this->input->post('cmarca_id')>0)
			$where.='and p.cmarca_producto_id='. $this->input->post('cmarca_id'). " ";
		//Obtener el catalogo de productos usados operativamente
		$join="left join espacios_fisicos as e on e.id=s.espacios_fisicos_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_subfamilias as sf on sf.id=p.csubfamilia_id left join cproductos_familias as f on f.id=p.cfamilia_id left join cmarcas_productos as cm on cm.id=p.cmarca_producto_id";
		$order=" order by p.cfamilia_id,f.tag, sf.tag, p.descripcion";
		$productos=$this->db->query("select distinct(s.cproductos_id), p.descripcion, p.cfamilia_id, f.tag as familia, sf.tag as subfamilia, cm.tag as marca from salidas as s $join $where  group by s.cproductos_id, p.descripcion, p.cfamilia_id,f.tag, sf.tag, cm.tag  $order ");
		if($productos->num_rows()>0){
			//Armar la matriz central
			$r=1;
			$grafica_familias=array();
			foreach($productos->result() as $row){
				$in[$r-1]=$row->cproductos_id;
				$r+=1;
				$matriz[$row->cproductos_id]['producto_id']=$row->cproductos_id;
				$matriz[$row->cproductos_id]['descripcion']=$row->descripcion." ";
				$matriz[$row->cproductos_id]['familia']=$row->familia;
				$matriz[$row->cproductos_id]['cfamilia_id']=$row->cfamilia_id;
				$matriz[$row->cproductos_id]['subfamilia']=$row->subfamilia;
				for($c=1;$c<=count($semanas);$c++){
					$matriz[$row->cproductos_id]["semana".$c."_cantidad"]=0;
					$matriz[$row->cproductos_id]["semana".$c."_costo_total"]=0;
				}
			}
		} else {
			show_error("No hay productos en la Sucursal y periodo de tiempo seleccionado");
		}
		$in_where=implode(", ", $in);
		//echo $in_where;
		for($d=1;$d<count($semanas);$d++){
			$where1="and s.fecha >=". $semanas[$d]['fecha1'] ." and s.fecha <". $semanas[$d]['fecha2']." ";
			$order=" order by espacio, familia, subfamilia, p.descripcion";
			$resultados=$this->db->query("select distinct(s.espacios_fisicos_id), s.cproductos_id, sum(s.cantidad) as cantidad, sum(s.costo_total) as costo_total, p.cfamilia_id, f.tag as familia, e.tag as espacio, sf.tag as subfamilia, p.descripcion from salidas as s $join $where $where1 and s.cproductos_id in($in_where) group by s.espacios_fisicos_id, s.cproductos_id,p.cfamilia_id, f.tag,e.tag,sf.tag, p.descripcion $order ");
			//echo $resultados->num_rows()."<br/>";
			foreach($resultados->result() as $row){
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["espacio"]=$row->espacio;
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["producto_id"]=$matriz[$row->cproductos_id]["producto_id"];
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["descripcion"]=$matriz[$row->cproductos_id]["descripcion"];
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["familia"]=$matriz[$row->cproductos_id]["familia"];
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["cfamilia_id"]=$matriz[$row->cproductos_id]["cfamilia_id"];
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["subfamilia"]=$matriz[$row->cproductos_id]["subfamilia"];
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["semana".$d."_cantidad"]=$row->cantidad;
				$matriz_e[$row->espacios_fisicos_id][$row->cproductos_id]["semana".$d."_costo_total"]=$row->costo_total;
				if(isset($grafica_familias['semana'.$d][$row->cfamilia_id])==true){
					$grafica_familias['semana'.$d][$row->cfamilia_id]['value'] +=$row->costo_total;
				} else {
					$grafica_familias['semana'.$d][$row->cfamilia_id]['tag']=utf8_decode($row->familia);
					$grafica_familias['semana'.$d][$row->cfamilia_id]['value'] =$row->costo_total;
				}

			}
		}
		$etiquetas="";
		foreach($grafica_familias as $k=>$v){
			foreach($v as $k1=>$v1){
				if(isset($valores[$k1])==false){
					$valores[$k1]=$v1['value'];
					$etiquetas[$k1]="\"".$v1['tag']."\"";
				} else
					$valores[$k1].=",".round($v1['value'],0);
			}
		}
		$title="Gráfica de Comportamiento de Ventas por semana: $fecha1 al $fecha2";
		$data['title']=$title;
		$this->graficas->ventas_familias($valores, $etiquetas, $title);
		$data['datos']=$matriz_e;
		$data['semanas']=$semanas;
		$this->load->view("$ruta/rep_ventas_detalle_pdf", $data);
		unset($data);
	} // END method rep_ventas_detalle_pdf #########################################################

	//Autor: Andrés Pacheco
	//Fecha de Creación: 12-08-2010
	//Uso Principal: recibe datos del formulario y hace una consulta a la base
	//de datos con los filtros datos, regresa las ventas agrupadas por horas
	//en un  periodo dado.
	function rep_hora_ventas() {
		global $ruta;
		//Obtener los datos
		$filtro = array();
		if((int)$_POST['espaciof']>0){
			$filtro[] = "nota_remision.espacio_fisico_id = ".(int)$_POST['espaciof'];
			$espacio_obj=$this->espacio_fisico->get_by_id($_POST['espaciof']);
			$data['tienda']=$espacio_obj->tag;
		} else
			$data['tienda']='TODAS';
		if (count($filtro))
			$where_ini = implode(" and ", $filtro)." ";
		else
			$where_ini = "";

		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if($fecha1 != false) {
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}

		if($fecha2 != false) {
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}

		$f1 = false;
		$f2 = false;
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "fecha >= '$fecha1' and fecha <= '$fecha2'";
			else
				$filtro[] = "fecha >= '$fecha2' and fecha <= '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "fecha = '$fecha2'";

		if (count($filtro))
			$where = " ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$sql_general = "SELECT sum(importe_total) as venta, count(*) as tickets,
		CASE WHEN date_part('hour', hora)< 8
		THEN date_part('hour', hora)+12
		ELSE date_part('hour', hora)
		END as h,
		e.tag, round(count(*)/((date '$fecha2' - date '$fecha1')+1), 2) as prom, count(distinct(fecha)) as dias, (sum(importe_total)/count(distinct(fecha))) as promv
		from nota_remision
		left join espacios_fisicos as e on e.id=nota_remision.espacio_fisico_id
		where $where and nota_remision.estatus_general_id = '1'
		GROUP BY  date_part('hour', nota_remision.hora), e.tag order by h";
		//echo $sql_general;
		$global=$this->db->query($sql_general);
		if($global->num_rows()==0) show_error('No hay registros que cumplan los criterios.');
		$etiquetas=array();
		$valoresg=array();
		$r=0;

		foreach($global->result() as $row){
			if($row->tag!='') {
				$valoresg[$r]=$row->prom;
				$etiquetas[$r]=$row->h.":00 - ".($row->h+1).":00";
				$r+=1;
			}
		}

		$title="Gráfica de Tickets periodo: $fecha1 a $fecha2";
		$data['title']=$title;
		$this->graficas->tickets_hora($valoresg, $etiquetas, "Tickets Promedio");
		$data['global']=$global;
		$this->load->view("$ruta/rep_graficas_horas", $data);
		unset($data);
	} // END

	//Autor: Andres Pacheco
	//Fecha de Creacion: 12-08-2010
	//Uso Principal: recibe datos del formulario y hace una consulta a la base
	//de datos con los filtros datos, regresa las ventas agrupadas por dias
	//en un  periodo dado.
	function rep_tickets_semana() {
		global $ruta;
		//Obtener los datos
		$filtro = array();
		if((int)$_POST['espaciof']>0){
			$filtro[] = "nota_remision.espacio_fisico_id = ".(int)$_POST['espaciof'];
			$espacio_obj=$this->espacio_fisico->get_by_id($_POST['espaciof']);
			$data['tienda']=$espacio_obj->tag;
		} else
			$data['tienda']='TODAS';

		if (count($filtro))
			$where_ini = implode(" and ", $filtro)." ";
		else
			$where_ini = "";

		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if($fecha1 != false) {
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}

		if($fecha2 != false) {
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}

		$f1 = false;
		$f2 = false;
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "fecha >= '$fecha1' and fecha <= '$fecha2'";
			else
				$filtro[] = "fecha >= '$fecha2' and fecha <= '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "fecha = '$fecha2'";

		if (count($filtro))
			$where = " ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$sql_general = "SELECT distinct(EXTRACT(DOW FROM fecha)) as dian,sum(importe_total) as venta, count(*) as tickets, e.tag,
		UPPER(CASE WHEN EXTRACT(DOW FROM fecha) = 0 THEN 'Lunes'
		WHEN EXTRACT(DOW FROM fecha) = 1 THEN 'Martes'
		WHEN EXTRACT(DOW FROM fecha) = 2 THEN 'Miercoles'
		WHEN EXTRACT(DOW FROM fecha) = 3 THEN 'Jueves'
		WHEN EXTRACT(DOW FROM fecha) = 4 THEN 'Viernes'
		WHEN EXTRACT(DOW FROM fecha) = 5 THEN 'Sabado'
		WHEN EXTRACT(DOW FROM fecha) = 6 THEN 'Domingo'
		ELSE 'NUUL_DIA' END) AS dia,
		round(count(*)/count(distinct(fecha)), 2) as prom,
		(sum(importe_total)/count(distinct(fecha))) as promv,
		count(distinct(fecha)) as dias
		from nota_remision
		left join espacios_fisicos as e on e.id=nota_remision.espacio_fisico_id
		where $where and nota_remision.estatus_general_id = '1'
		GROUP BY EXTRACT(DOW FROM fecha), e.tag order by EXTRACT(DOW FROM fecha) asc";
		//                        echo $sql_general; return;
		$global=$this->db->query($sql_general);
		if($global->num_rows()==0) show_error('No hay registros que cumplan los criterios.');
		$etiquetas=array();
		$valoresg=array();
		$r=0; $dia_anterior=-1;
		foreach($global->result() as $row){
			if(isset($valoresg[$r])==false)
				$valoresg[$r]=0;
			//                    if($row->dia!=$dia_anterior){
			//                            $dia_anterior=$row->dia;
			//                    } else {
			$valoresg[$r]+=$row->tickets;
			$etiquetas[$r]=$row->dia;
			$r+=1;
			//		    }
		}
		// 		unset($valoresg[1]);
		// 		unset($etiquetas[1]);

		$title="Gráfica de Tickets periodo: $fecha1 a $fecha2";
		$data['title']=$title;
		$this->graficas->tickets_dia($valoresg, $etiquetas, "Tickets Promedio");
		$data['global']=$global;
		$this->load->view("$ruta/rep_graficas_dias", $data);
		unset($data);
	} // END

	function rep_top_productos_pdf() { // BEGIN method kardex_pdf
		global $ruta;
		//echo "<pre>";print_r($_POST);echo "</pre>";die();
		$fecha1 = $this->input->post('fecha1');
		$fecha1a = $fecha1;
		$fecha2 = $this->input->post('fecha2');
		$fecha2a = $fecha2;
		$title = "Reporte de ventas del período: " . $fecha1a . " al " . $fecha2a;

		//Fecha
		if ($_POST['fecha1'] == "" and strlen($_POST['fecha2']) > 0) {
			$hoy = date("Y-m-d");
			$fecha1 = date("Y-m-d", strtotime($hoy));
			$fecha = explode(" ", $_POST['fecha2']);
			$fecha2a = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
		} else if ($_POST['fecha2'] == "" and strlen($_POST['fecha1']) > 0) {
			$hoy = date("Y-m-d");
			$fecha2 = date("Y-m-d", strtotime($hoy) + (24 * 60 * 60));
			$fecha = explode(" ", $_POST['fecha1']);
			$fecha1 = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
		} else {
			$fecha = explode(" ", $_POST['fecha1']);
			$fecha1 = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
			$fecha = explode(" ", $_POST['fecha2']);
			$fecha2a = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
			$fecha2 = date("Y-m-d", strtotime($fecha[2] . "-" . $fecha[1] . "-" . $fecha[0]) + (24 * 60 * 60));
		}


		$where = " s.fecha >= '$fecha1' and s.fecha < '$fecha2'";
		$where2 = "AND n.fecha >= '$fecha1' AND n.fecha < '$fecha2'";

		$espacio = $_POST['espaciof'];
		$marca = $this->input->post('marca_drop');
		$marca_id = $this->input->post('cmarca_id');

		if ($espacio != '0') {
			$where .= " AND s.espacios_fisicos_id = '" . $espacio . "'";
			$where2 .= " AND n.espacio_fisico_id = '$espacio'";
		}

		if ($marca_id > 0) {
			$where .= " AND p.cmarca_producto_id = '$marca_id'";
			$where2 .= " and p.cmarca_producto_id = '$marca_id'";
		}

		$where .= " AND s.estatus_general_id = '1' AND ctipo_salida_id = '1' AND s.id_ubicacion_local IS NOT NULL";

		$group_by = "pn.id, f.tag, s1.tag, p.descripcion, p.id, pn.numero_mm, p.precio1";

		$sql = "SELECT
		pn.id AS cproducto_numero_id,
		p.id AS cproducto_id,
		p.precio1 AS precio1,
		f.tag AS familia,
		s1.tag AS subfamilia,
		p.descripcion AS descripcion,
		pn.numero_mm AS numero,
		SUM(s.cantidad) AS cantidad,
		SUM(s.costo_total) AS monto
		FROM cproductos_numeracion AS pn
		LEFT JOIN salidas as s ON s.cproducto_numero_id = pn.id
		LEFT JOIN cproductos AS p ON p.id = pn.cproducto_id
		LEFT JOIN cproductos_subfamilias AS s1 ON s1.id = p.csubfamilia_id
		LEFT JOIN cproductos_familias AS f ON f.id = p.cfamilia_id
		WHERE $where group by $group_by ORDER BY cantidad DESC, descripcion";
		//        echo $sql; return;
		$global = $this->db->query($sql);
		if ($global->num_rows() == 0)
			show_error('No hay registros que cumplan los criterios.');

		$devo = 0;
		$sql2 = "select
		n.cobro_vales as cobro
		from
		nota_remision as n
		left join
		salidas as s on s.numero_remision_id = n.numero_remision_interno
		and s.espacios_fisicos_id = n.espacio_fisico_id
		and s.costo_total = n.cobro_vales
		left join
		cproductos as p on p.id = s.cproductos_id
		where
		n.cobro_vales > '0'
		and
		n.estatus_general_id = '1'
		$where2
		group by
		n.id, n.cobro_vales
		order by
		n.cobro_vales";
		$descu = $this->db->query($sql2);
		if ($descu->num_rows() > 0){
			foreach ($descu->result() as $row)
				$devo += $row->cobro;
		}
		$tabla = array();
		$vista = array();
		$vista['global'] = $global;
		$vista['title'] = $title;
		$vista['descuento'] = $devo;
		$this->load->view("$ruta/rep_top_productos_pdf", $vista);
		unset($data);
	}
	function rep_gastos_tiendas() { // BEGIN method rep_gastos_tiendas_pdf
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$espacio = $this->input->post('espacio');
		$tipo_ejecutivo = $this->input->post('tipo');

		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where g.fecha>='$fecha1' and g.fecha<'$fecha2' ";

		if($espacio>0) {
			$where.=" and g.espacios_fisicos_id='$espacio'";
			$espacio=$this->espacio_fisico->get_by_id($espacio);
			$nombre_espacio=$espacio->tag;
		} else {
			$nombre_espacio="Todas las Tiendas";
			$espacio=$this->espacio_fisico->get_espacios_tiendas_oficinas();
		}
		if($tipo_ejecutivo=='ejecutivo'){
			$datos=$this->db->query("select distinct(g.cgastos_id) as cgastos_id, sum(g.monto) as monto, cg.tag as concepto,cg.id from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id $where and g.estatus_general_id=1  group by g.cgastos_id, cg.tag,cg.id order by cg.id ");

			if($datos->num_rows()==0)
				show_error("No existen registros de gastos en el periodo seleccionado");
			$etiquetas=array();
			$valoresg=array();
			$r=1;
			//echo $sql_general;
			foreach($datos->result() as $row){
				if($row->concepto!=''){
					$valoresg[$r]=round($row->monto,0);
					$etiquetas[$r]=$row->concepto;
					$r+=1;
				}
			}
			//print_r($valoresg);
			$title="Grafica de Gastos en $nombre_espacio - periodo: {$_POST['fecha1']} a {$_POST['fecha2']}";
			$data['title']=$title;
			$this->graficas->gastos_globales($valoresg, $etiquetas, "Gastos Globales");
			$data['datos']=$datos;
			$this->load->view("$ruta/rep_gastos_tiendas_pdf", $data);

		} else if($tipo_ejecutivo=='detalle') {
			//Gastos a detalle por tienda, En base al Espacio fisico agrupar cada uno de los conceptos de gasto por sucursal
			foreach($espacio->all as $row){
				$datos[$row->id]=$this->db->query("select distinct(g.cgastos_id) as cgastos_id, sum(g.monto) as monto, cg.tag as concepto,cg.id from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id where g.estatus_general_id=1  and g.fecha>='$fecha1' and g.fecha<'$fecha2' and g.espacios_fisicos_id=$row->id group by g.cgastos_id, cg.tag, cg.id order by cg.id ");
				if($datos[$row->id]->num_rows()==0)
					unset($datos[$row->id]);
			}
			$data['espacio']=$espacio;
			$title="Reporte de Gastos en $nombre_espacio - periodo: {$_POST['fecha1']} a {$_POST['fecha2']}";
			$data['title']=$title;
			$data['datos']=$datos;
			$this->load->view("$ruta/rep_gastos_tiendas_detalle_pdf", $data);


		} else if($tipo_ejecutivo=='desglosado') {
			//Gastos a detalle por tienda, En base al Espacio fisico agrupar cada uno de los conceptos de gasto por sucursal
			foreach($espacio->all as $row){
				$datos[$row->id]=$this->db->query("select g.id as gasto_id, g.fecha, g.cgastos_id, g.concepto, g.monto, cg.tag as tipo_gasto, cg.id, u.username as usuario from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id left join usuarios as u on u.id=g.usuario_id where g.estatus_general_id=1 and g.fecha>='$fecha1' and g.fecha<'$fecha2' and g.espacios_fisicos_id=$row->id  order by cg.tag, g.fecha asc ");
				if($datos[$row->id]->num_rows()==0)
					unset($datos[$row->id]);
			}
			$data['espacio']=$espacio;
			$title="Reporte de Gastos en $nombre_espacio - periodo: {$_POST['fecha1']} a {$_POST['fecha2']}";
			$data['title']=$title;
			$data['datos']=$datos;
			$this->load->view("$ruta/rep_gastos_tiendas_desglosado_pdf", $data);
		}

		unset($data);
	} // END method rep_productos_pdf #########################################################

	function rep_ventas_marcas_pdf() { // BEGIN method rep_productos_pdf
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$espacio = $this->input->post('espacio');
		$tipo_ejecutivo = $this->input->post('tipo');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where s.fecha>='$fecha1' and s.fecha<'$fecha2' ";
		$where_d =" where d.fecha_devolucion>='$fecha1' and d.fecha_devolucion<'$fecha2' ";

		if($tipo_ejecutivo=='ejecutivo') {

			if($espacio>0) {
				$where.=" and s.espacios_fisicos_id='$espacio'";
				$where_d.=" and d.espacio_fisico_id='$espacio'";
				$espacio=$this->espacio_fisico->get_by_id($espacio);
				$nombre_espacio=$espacio->tag;
			} else {
				$nombre_espacio="Todas las Tiendas";
				//Obtener espacios fisicos cuando son TODOS
				$espacios_f=$this->espacio_fisico->get_espacios_tiendas();
				foreach($espacios_f->all as $list){
					$sucursales_a[]=$list->id;
				}
				$where .= "and s.espacios_fisicos_id in (".implode(", ",$sucursales_a).") ";
				$where_d .= "and d.espacio_fisico_id in (".implode(", ",$sucursales_a).") ";

			}

			$datos=$this->db->query("select distinct(p.cmarca_producto_id) as cmarca_id, sum(s.costo_total) as costo_total, sum(s.cantidad) as pares, m.tag as marca from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id $where group by p.cmarca_producto_id, m.tag order by pares desc");

			if($datos->num_rows()==0)
				show_error("No existen registros de gastos en el periodo seleccionado");

			$devoluciones=$this->db->query("select sum(d.cantidad) as cantidad, sum(d.costo_unitario) as costo_unitario from devoluciones as d $where_d");
			$data['dev']=$devoluciones;
			$title="Reporte Ejecutivo de Ventas por Marcas en $nombre_espacio";
			$data['title']=$title;
			$data['datos']=$datos;
			$data['orientation']="P";
			$this->load->view("$ruta/rep_ventas_marcas_pdf", $data);

		} else {
			//Primero obtener los espacios fisicos
			$nombre_espacio="Todas las Tiendas";
			//Obtener espacios fisicos cuando son TODOS
			$espacios_f=$this->espacio_fisico->get_espacios_tiendas();
			$s=0; $matriz_p=array();
			foreach($espacios_f->all as $list) {
				$sucursales_id[$s]=$list->id;
				$sucursales_tag[$s]=$list->tag;
				//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
				$datos=$this->db->query("select distinct(p.cmarca_producto_id) as cmarca_id, sum(s.costo_total) as costo_total, sum(s.cantidad) as pares, m.tag as marca from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id $where and s.espacios_fisicos_id='$list->id' group by p.cmarca_producto_id, m.tag order by m.tag");
				foreach($datos->result() as $renglon){
					//Asignar los datos a la matriz
					$matriz_p[$renglon->cmarca_id]['tag']=$renglon->marca;
					$matriz_p[$renglon->cmarca_id]['costo_'.$s]=$renglon->costo_total;
					$matriz_p[$renglon->cmarca_id]['pares_'.$s]=$renglon->pares;
					unset($renglon);
				}
				$s+=1;
			}
			//print_r($matriz_p);
			$title="Reporte Comparativo de Ventas por Marcas en Sucursales";
			$data['title']=$title;
			$data['sucursales_tag']=$sucursales_tag;
			$data['sucursales_id']=$sucursales_id;
			$data['datos']=$matriz_p;
			$data['orientation']="P";
			$this->load->view("$ruta/rep_ventas_marcas_comparativo_pdf", $data);
		}

		unset($data);
	} // END method rep_productos_pdf #########################################################

	function rep_resurtidos_marcas_pdf() { // BEGIN method rep_productos_pdf
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$espacio = $this->input->post('espacio');
		$marca_id = $this->input->post('cmarca_id');
		$marca = $this->input->post('marca_drop');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;

		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where s.fecha>='$fecha1' and s.fecha<'$fecha2' ";
		if($espacio>0) {
			//$where.=" and s.espacios_fisicos_id='$espacio'";
			$espacios_f=$this->espacio_fisico->get_by_id($espacio);
			if($espacios_f==false)
				echo "Error con la sucursal";
			//$nombre_espacio=$espacio->tag;
		} else {
			//Primero obtener los espacios fisicos
			$nombre_espacio="Todas las Tiendas";
			//Obtener espacios fisicos cuando son TODOS
			$espacios_f=$this->espacio_fisico->get_espacios_tiendas();
		}

		$s=0; $matriz_p=array();
		foreach($espacios_f->all as $list) {
			$sucursales_id[$s]=$list->id;
			$sucursales_tag[$list->id]=$list->tag;

			//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
			$datos=$this->db->query("select distinct(s.cproducto_numero_id) as numero_id, sum(s.cantidad) as pares, s.cproductos_id, p.descripcion, pn.numero_mm, s.espacios_fisicos_id from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id $where and s.espacios_fisicos_id='$list->id' and s.estatus_general_id=1 and ctipo_salida_id=1 and p.cmarca_producto_id='$marca_id' group by s.cproducto_numero_id, s.cproductos_id, p.descripcion, pn.numero_mm, s.espacios_fisicos_id order by p.descripcion, pn.numero_mm");

			if($datos->num_rows()>0){
				foreach($datos->result() as $renglon){
					//Asignar los datos a la matriz
					// 				$matriz_p[$renglon->espacios_fisicos_id]['tag']=$list->tag;
					$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
					$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['producto_id']=$renglon->cproductos_id;
					$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['numero_'.$renglon->numero_mm]=$renglon->pares;

					//Obtener existencias
					$where1=" where p.id = $renglon->cproductos_id";
					$where_i=" where ef.id =$list->id ";
					$inventario=$this->almacen->inventario($where_i, '',$where1);
					$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['existencia_'.$renglon->numero_mm]=$inventario[$renglon->cproductos_id]['existencias'];
					if(isset($sucursales_min[$list->id])==false)
						$sucursales_min[$list->id]=$renglon->numero_mm;
					if($sucursales_min[$list->id] > $renglon->numero_mm)
						$sucursales_min[$list->id]=$renglon->numero_mm;

					if(isset($sucursales_max[$list->id])==false)
						$sucursales_max[$list->id]=$renglon->numero_mm;
					//Guardar el min y max por espacio fisico
					if ($sucursales_max[$list->id] < $renglon->numero_mm)
						$sucursales_max[$list->id]=$renglon->numero_mm;
					unset($renglon);
				}
				$s+=1;
			} else {
				$sucursales_min[$list->id]=0;
				$sucursales_max[$list->id]=0;
			}
		}
		//print_r($matriz_p);
		$title="Reporte de Resurtidos por Marca: '$marca' ";
		$data['title']=$title;
		$data['sucursales_tag']=$sucursales_tag;
		$data['sucursales_min']=$sucursales_min;
		$data['sucursales_max']=$sucursales_max;
		$data['sucursales_id']=$sucursales_id;
		$data['datos']=$matriz_p;
		$data['orientation']="L";
		$this->load->view("$ruta/rep_resurtidos_marcas_pdf", $data);
		unset($data);
	} // END method rep_productos_pdf #########################################################

	function rep_rentabilidad_pdf() { // BEGIN method rep_productos_pdf
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where_g =" where g.fecha>='$fecha1' and g.fecha<'$fecha2' ";
		$where_c =" where c.fecha_corte>='$fecha1' and c.fecha_corte<'$fecha2' ";
		$where_s =" where s.fecha>='$fecha1' and s.fecha<'$fecha2' ";

		$espacios=$this->espacio_fisico->get_espacios_tiendas(); $r=1;
		$etiquetas=array();
		$valoresg=array();
		$this->load->model("nota_remision");
		$this->load->model("salida");
		$colect=array();
		foreach($espacios->all as $row){
			//Obtener las Ventas con devoluciones
			$compra=$this->salida->get_monto_compra_marca($row->id, $where_s);
			$ventas=$this->nota_remision->get_ventas_espacios($fecha1,$fecha2,$row->id);
			$datos=$this->db->query("select sum(g.monto) as monto from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id $where_g and espacios_fisicos_id='$row->id' and g.estatus_general_id=1 ");
			if($datos->num_rows()==1)
				$t=$datos->row();
			else
				$t->monto=0;
			$colect[$row->id]['nombre']=$row->tag;
			// 			echo 1;
			if($ventas==false){
				$ventas->venta_total=0;
				$ventas->devolucion=0;
			}

			//Fiscal
			$datos1=$this->db->query("select sum(c.venta_fiscal) as monto from cortes_diarios as c  $where_c and espacios_fisicos_id='$row->id'");

			if($datos1->num_rows()==1)
				$t1=$datos1->row();
			else
				$t1->monto=0;

			$colect[$row->id]['ventas']=$ventas->venta_total - $ventas->devolucion;
			$colect[$row->id]['gastos']=$t->monto;
			$colect[$row->id]['fiscal']=$t1->monto;
			$colect[$row->id]['compra']=$compra;
			$colect[$row->id]['efectivo']=$ventas->venta_total-$ventas->devolucion-$t->monto-$compra;
			//Gráfica
			$valoresg[$r]=round($colect[$row->id]['efectivo'],0);
			$etiquetas[$r]=$row->tag;
			$r+=1;
			//print_r($colect);
		}

		//print_r($valoresg);
		$data['datos']=$colect;
		$title="Grafica de Rentabilidad - periodo: {$_POST['fecha1']} a {$_POST['fecha2']}";
		$data['title']=$title;
		$this->graficas->rentabilidad($valoresg, $etiquetas, "Rentabilidad");
		$this->load->view("$ruta/rep_rentabilidad_pdf", $data);
		// print_r($colect);
		unset($data);
	} // END method rep_productos_pdf #########################################################

	function rep_comparativo_marcas_pdf(){
		global $ruta;
		//Obtener los datos
		$marca_id = $this->input->post('cmarca_id');
		$marca = $this->input->post('marca_drop');
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['marca']=$marca;
		$data['marca_id']=$marca_id;
		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}

		//Obtener los espacios fisicos seleccionados
		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$t]=$_POST['chk'.$x];
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos_tag[$t]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$count_espacios=count($efisicos);
		//obtener el id de espacio fisico de cada almacen de tienda seleccionado en los criterios
		if($count_espacios==0)
			show_error("Seleccione una Sucursal al menos");
		$espacios_mtrx=implode(',', $efisicos);
		$almacenes=$this->db->query("select id from espacios_fisicos where espacio_fisico_matriz_id in ($espacios_mtrx) order by id");
		foreach($almacenes->result() as $alm){
			$almacenes_mtrx[]=$alm->id;
		}
		$almacenes_str=implode(',', $almacenes_mtrx);
		//Obtener los lotes que fueron comprados en las fechas antes señaladas
		$where_lotes=" where fecha>='$fecha1' and fecha<'$fecha2' ";
		$lotes=$this->db->query("select distinct(e1.lote_id) as lote_id from entradas as e1 $where_lotes and espacios_fisicos_id in ($almacenes_str) and e1.estatus_general_id=1");
		$lotes_mtrx=array();
		if($lotes->num_rows()>0){
			foreach($lotes->result() as $row){
				if($row->lote_id!="")
					$lotes_mtrx[]=$row->lote_id;
			}
			$lotes_str=implode(',', $lotes_mtrx);
		} else {
			show_error("No hubo movimientos con los criterios seleccionados");
		}
		//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
		$matriz_p=array();
		$datos=$this->db->query("select distinct(e.cproductos_id) as cproductos_id, p.descripcion  from entradas as e left join cproductos as p on p.id=e.cproductos_id where lote_id in ($lotes_str) and espacios_fisicos_id in ($almacenes_str) and e.estatus_general_id='1' and cmarca_producto_id='$marca_id' order by descripcion ");

		$where_cat=" where cmarca_producto_id= '$marca_id'";
		if($datos->num_rows()>0){
			foreach($datos->result() as $renglon){
				$bandera=3*$count_espacios;
				//Recorrer cada producto para asignarlo a los espacios fisicos
				$matriz_p[$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
				foreach($efisicos as $k=>$v){
					$mtr_numeros=array();
					$where_i=" where ef.id='$v' and fecha>='$fecha1' and fecha<'$fecha2' ";

					$existencia=$this->almacen->existencias($renglon->cproductos_id,$where_i);
					if($existencia['entradas']==''){
						$existencia['entradas']=0;
						// 						$bandera-=1;
					}
					if($existencia['salidas']=='' or $existencia['salidas']<5){
						$existencia['salidas']=0;
						$bandera-=3;
					}
					if($existencia['existencias']==0){
						// 						$bandera-=1;
					}

					$matriz_p[$renglon->cproductos_id]['compra_'.$v]=$existencia['entradas'];
					$matriz_p[$renglon->cproductos_id]['venta_'.$v]=$existencia['salidas'];
					$matriz_p[$renglon->cproductos_id]['existencia_'.$v]=$existencia['existencias'];
					unset($existencia);
					// 					//Obtener la numeracion dentro del espacio fisico que presenta movimientos
					$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm from salidas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm order by numero_mm");
					if($numeros->num_rows()>0){
						foreach($numeros->result() as $numero){
							$mtr_numeros[$numero->numero_mm]=$numero->numero_mm/10;
						}
					} else
						$mtr_numeros[0]=0;
					$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm from entradas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm");
					if($numeros->num_rows()>0){
						foreach($numeros->result() as $numero){
							$mtr_numeros[$numero->numero_mm]=$numero->numero_mm/10;
						}
					} else
						$mtr_numeros[0]=0;
					if(count($mtr_numeros)>1 and isset($mtr_numeros[0]))
						unset($mtr_numeros[0]);
					$matriz_p[$renglon->cproductos_id]['numeros_'.$v]=implode(", ",$mtr_numeros);
				}
				if($bandera<=0)
					unset($matriz_p[$renglon->cproductos_id]);
				unset($renglon);
			}
		} else
			show_error("No hay datos para los criterios seleccionados");
		$data['espacios']=$efisicos;
		$data['espacios_tag']=$efisicos_tag;
		$data['datos']=$matriz_p;
		$data['ruta']=$ruta;
		$data['title']="Reporte Comparativo de Marcas en Sucursales";
		//print_r($matriz_p);
		$this->load->view("$ruta/rep_comparativo_marcas_html", $data);
	}

	/** REPORTE ESPACIAL */
	function rep_comparativo_compras_pdf(){
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$cantidad_minima = $this->input->post('cantidad_minima');
		$fecha2 = $this->input->post('fecha2');
		$temporada = $this->input->post('temporada');

		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['marca']="Varias";
		//Fecha
		if($fecha1=="" and strlen($fecha2)>0) {
			$hoy=date("Y-m-d");
			$fecha1a=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2=="" and strlen($fecha1)>0) {
			$hoy=date("Y-m-d");
			$fecha2a=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2);
			//			$fecha2_prev=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where e.fecha>='$fecha1a' and e.fecha<'$fecha2a'";
		//Obtener los espacios fisicos seleccionados
		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$t]=$_POST['chk'.$x];
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos_tag[$t]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$listado_espacios=implode(", ", $efisicos);
		$count_espacios=count($efisicos);

		if($temporada>0){
			$where .=" and p.cproductos_temporada_id=$temporada ";
		}

		//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
		$matriz_p=array();
		$sql1="select distinct(e.cproductos_id) as cproductos_id, p.descripcion, p.ruta_foto, m.proveedor_id  from entradas as e left join cproductos as p on p.id=e.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id $where and e.espacios_fisicos_id in ($listado_espacios) and e.estatus_general_id=1 and ctipo_entrada=2 and p.cfamilia_id!=5 group by e.cproductos_id, p.descripcion, m.proveedor_id, p.ruta_foto order by p.descripcion ";
		$datos=$this->db->query($sql1);

		//echo $count_espacios."<br/>";
		if($datos->num_rows()>0){
			foreach($datos->result() as $renglon){
				$bandera=3*$count_espacios;
				//Recorrer cada producto para asignarlo a los espacios fisicos
				$matriz_p[$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
				$matriz_p[$renglon->cproductos_id]['ruta_foto']=$renglon->ruta_foto;
				$matriz_p[$renglon->cproductos_id]['proveedor_id']=$renglon->proveedor_id;

				foreach($efisicos as $k=>$v){

					$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";

					$mtr_numeros=array();
					$where_i=" where ef.id='$v' and fecha>='$fecha1a' and fecha<'$fecha2a' ";
					$where_abs1=" where ef.id='$v' and fecha<'$fecha2a' ";
					$where_abs2=" where ef.id='$v' and fecha<'$fecha1a' ";

					$existencia=$this->almacen->existencias($renglon->cproductos_id,$where_i, $where_abs1, $where_abs2);

					if($existencia['entradas']==''){
						$existencia['entradas']=0;
						$bandera-=3;
					}
					if($existencia['salidas']==''){
						$existencia['salidas']=0;
					}
					if($existencia['salidas']<$cantidad_minima){
						$bandera-=3;
					}
					if($existencia['existencias']==0){
						// 						$bandera-=1;
					}
					//					echo "EF:$v pid=$renglon->cproductos_id -> ".$existencia['entradas']."<br/>";
					$matriz_p[$renglon->cproductos_id]['compra_'.$v]=$existencia['entradas'];

					$matriz_p[$renglon->cproductos_id]['venta_'.$v]=$existencia['salidas'];
					// 					$matriz_p[$renglon->cproductos_id]['existencia_'.$v]=$existencia['existencias'];
					$matriz_p[$renglon->cproductos_id]['existencia_abs_'.$v]=$existencia['existencias_abs'];
					$matriz_p[$renglon->cproductos_id]['existencia_abs2_'.$v]=$existencia['existencias_abs2'];
					unset($existencia);
					// 					//Obtener la numeracion dentro del espacio fisico que presenta movimientos
					$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from salidas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad order by numero_mm");
					if($numeros->num_rows()>0){
						foreach($numeros->result() as $numero){
							//$mtr_numeros[$numero->numero_mm]=$numero->numero_mm/10;
							$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";

						}
					} else {
						// 						$mtr_numeros[0]=0;
						$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from entradas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad");

						if($numeros->num_rows()>0){
							foreach($numeros->result() as $numero){
								$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";
								// 							$mtr_numeros_cantidad[$numero->numero_mm]=$numero->pares;
							}
						} else
							$mtr_numeros[0]=0;
						if(count($mtr_numeros)>1 and isset($mtr_numeros[0]))
							unset($mtr_numeros[0]);
					}
					$matriz_p[$renglon->cproductos_id]['numeros_'.$v]=implode(", ",$mtr_numeros);

					/**obtener para cada cproducto_id($renglon->cproductos_id ) y sucursal ($v), la ultima compra, */
					//Paso 1 Obtener el espacio_fisico_id del almacen de leon de la tienda
					$espacio_almacen=$this->espacio_fisico->where('tipo_espacio_id', 1)->where('espacio_fisico_matriz_id',$v)->limit(1)->get();
					if ($espacio_almacen->id>0){
						$espacio_aid=$espacio_almacen->id;

					} else {
						$espacio_aid=$v;
					}
					//Paso 2, Encontrar la entrada de la factura
					$entradas_a=$this->entrada->select('pr_facturas_id as id, date(fecha) as fecha')->where('espacios_fisicos_id', $espacio_aid)->where('cproductos_id', $renglon->cproductos_id)->where('estatus_general_id',1)->where('ctipo_entrada', 1)->order_by("fecha desc")->limit(1)->get();
					if($entradas_a->id>0){
						$factura_prod=$entradas_a->id;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]=$entradas_a->fecha."&Fa: ".$entradas_a->id;

					} else {
						$factura_prod=0;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]="-&0&0";
					}
					unset($espacio_almacen); unset($entradas_a);
						
					/** Seccion para identificar pedidos en proceso de un cproducto_id y sucursal */
					//Paso 1 Encontrar pr_pedidos con los datos anteriores
					$pedidos=$this->db->query("select distinct(pr_pedidos_id) as pr_pedido_id from pr_detalle_pedidos where cproductos_id=$renglon->cproductos_id  group by pr_pedidos_id");
					if($pedidos->num_rows()>0) {
						$pedidos_id=array();
						foreach($pedidos->result() as $row) {
							$pedidos_id[]=$row->pr_pedido_id;
						}
						$list_pedidos=implode(', ',$pedidos_id);
					} else {
						//No hay pedidos para dicho producto
						$list_pedidos="0";
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";
					}
					$where_p =" and p.id in ($list_pedidos) ";
					$pedido_final=$this->db->query("select p.id, date(p.fecha_alta) as fecha_alta, e.tag as estatus, lf.lote_id from pr_pedidos  as p left join cpr_estatus_pedidos as e on e.id=p.cpr_estatus_pedido_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=p.id where espacio_fisico_id=$espacio_aid $where_p and estatus_general_id=1  and cpr_estatus_pedido_id<3 order by fecha_alta desc limit 1");

					/*					if($renglon->cproductos_id==25158 ){
						echo "select p.id, date(p.fecha_alta) as fecha_alta, e.tag as estatus, lf.lote_id from pr_pedidos  as p left join cpr_estatus_pedidos as e on e.id=p.cpr_estatus_pedido_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=p.id where espacio_fisico_id=$espacio_aid $where_p and estatus_general_id=1  and cpr_estatus_pedido_id<3 order by fecha_alta desc limit 1";
					}*/
						
					if($pedido_final->num_rows()>0){
						$pedido_final_row=$pedido_final->row();
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:red;width:100%;'>$pedido_final_row->id -$pedido_final_row->fecha_alta- $pedido_final_row->estatus</div>";


						unset($pedido_final); unset($pedido_final_row); unset($pedidos); unset($pedidos_id);
					} else {
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";

					}
						
					/** Subsección para obtener la fecha de traspaso de salida y de entrada del producto en base al lote y el producto*/
					//Primero encontrar la salida por traspaso
					$st=new Salida();
					$se=new Entrada();

					if($factura_prod>0) {
						$ent=new Entrada();
						$ent->where('pr_facturas_id', $factura_prod)->where('estatus_general_id',1)->where('fecha >=',$fecha1a)->where('fecha <',$fecha2a)->limit(1)->get();
						$lote_id=$ent->lote_id;
						$ent->clear();
						//$st->select("id, date(fecha) as fecha")->where('lote_id', $lote_id)->where('cproductos_id', $renglon->cproductos_id)->where('ctipo_salida_id', 2)->where('estatus_general_id', 1)->limit(1)->get();
							
						$se->select("id, date(fecha) as fecha")->where('lote_id', $lote_id)->where('cproductos_id', $renglon->cproductos_id)->where('ctipo_entrada', 2)->where('estatus_general_id', 1)->where('fecha >=',$fecha1a)->where('fecha <',$fecha2a)->limit(1)->get();

					}
					if($st->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]=$st->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					}
					unset($st);
					//Segundo encontrar la entrada por traspaso
					if($se->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]=$se->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";
					}
				}
				if($bandera<=0)
					unset($matriz_p[$renglon->cproductos_id]);
				unset($renglon);
			}
		} else {
			//echo $sql1;
			show_error("No hay datos para los criterios seleccionados");
		}
		$data['espacios']=$efisicos;
		$data['espacios_tag']=$efisicos_tag;
		$data['datos']=$matriz_p;
		$data['ruta']=$ruta;
		$data['title']="Reporte Comparativo de Movimiento de Compras";
		$this->load->view("$ruta/rep_comparativo_marcas_html_resurtidos", $data);
	}

	function trans_pre_pedidos(){
		//Generar pre pedidos del reporte espacial
		$d=$_POST;
		$filas=$d['filas'];
		unset($d['filas']);
		$espacios_str=$d['espacios'];
		$espacios_mtrx=explode(',',$espacios_str);
		unset($d['espacios']);
		//print_r($d);
		//Limpiar los elementos que no van a generarse
		for($x=1;$x<$filas;$x++){
			if(isset($d["producto_$x"])==false){
				//Producto no seleccionado
				unset($d["cproducto_id$x"]);
				unset($d["proveedor_id$x"]);
				foreach($espacios_mtrx as $ek=>$ev){
					unset($d["resurtido_{$x}_{$ev}"]);
					unset($d["numeracion_{$x}_{$ev}"]);
				}
				//				$filas-=1;
			}
		}
		$ne=count($espacios_mtrx);
		//Crear una matriz con la información de los productos, proveedores y espacios fisicos
		//Por linea son 3 campos + $ne (resurtido) + $ne (numeracion) en caso de que todas las sucursales se tomen en cuenta
		$colect=array();
		for($w=1;$w<$filas;$w++){
			//REcorrer cada Fila
			foreach($espacios_mtrx as $ev){
				//REcorrer cada tienda
				if(isset($d["resurtido_{$w}_{$ev}"])){
					//Esa fila aplica para esa tienda, proceder al llenarlo
					$proveedor=$d["proveedor_id$w"];
					//echo $d["cproducto_id$w"]."<br/>";
					$pid=$d["cproducto_id$w"];
					$colect[$ev][$proveedor][$pid]=$d["numeracion_{$w}_{$ev}"];
				}
			}
		}

		foreach($colect as $esp=>$proveedor){

			foreach($proveedor as $prov=>$productos){
				//Crear un pre pedido a este nivel
				$ped=new Pr_pedido();
				$ped->empresas_id=1;
				//Obtener el espacio fisico del almacen hijo de la tienda para referenciar a el el pedido
				$esp1=$this->espacio_fisico->select("id")->where('espacio_fisico_matriz_id', $esp)->where('tipo_espacio_id', 1)->limit(1)->get();
				$ped->espacio_fisico_id=$esp1->id;
				$ped->cproveedores_id=$prov;
				$ped->fecha_alta=date("Y-m-d");
				$ped->usuario_id=$GLOBALS['usuarioid'];
				$ped->cpr_estatus_pedido_id=5;
				$ped->cpr_forma_pago_id=1;
				$ped->estatus_general_id=1;
				$ped->cpr_forma_pago_id=1;

				if($ped->save()){
					foreach($productos as $pid=>$numeracion){
						$n_mm=array(); $cantidad=array();$pn_id_mtrx=array();
						//Separar cantidad y numeracion del string
						//echo $numeracion."<br/>";
						$numeros1=explode(',', $numeracion); $num_pendiente=array();
						//Obtener del ultimo pr_detalle_pedido de un cproducto_id el precio unitario
						$this->load->model("entrada");
						$precio=$this->entrada->select('costo_unitario')->where('cproductos_id', $pid)->where('estatus_general_id', 1)->where('ctipo_entrada', 1)->order_by('fecha desc')->limit(1)->get();

						foreach($numeros1 as $n_c){
							$guion=strpos($n_c, '-');
							$n_mm=floatval(substr($n_c, 0,$guion))*10;
							$pn_id_obj=$this->db->query("select id from cproductos_numeracion where cproducto_id='$pid' and numero_mm='$n_mm' limit 1");
							if($pn_id_obj->num_rows()==1)
								$pn_obj=$pn_id_obj->row();
							// 							echo "select id from cproductos_numeracion where cproducto_id='$pid' and numero_mm='$n_mm' limit 1<br/>";
							$pn_id_mtrx[]=$pn_obj->id;
							$cantidad[]=floatval(substr($n_c, $guion+1,100));
							$dp=new Pr_detalle_pedido();
							$dp->pr_pedidos_id=$ped->id;
							$dp->cproductos_id=$pid;
							$dp->cproducto_numero_id=$pn_obj->id;
							$dp->cantidad=floatval(substr($n_c, $guion+1,100));
							if($precio->costo_unitario>0){
								$dp->costo_unitario=$precio->costo_unitario;
							} else
								$dp->costo_unitario=0;
								
							$dp->costo_total=0;
							$dp->save();
							//Agregar el cproducto_id y el cproducto_numeracion a un array, para obtener al final  la numeración completa
							$num_pendiente[]=$pn_obj->id;
							$dp->clear();
						}
						//Agregar los numeros restantes
						$excluir=implode(", ",$num_pendiente);
						$restantes=$this->db->query("select id from cproductos_numeracion as pn where cproducto_id=$pid and id not in ($excluir) ");
						if($restantes->num_rows()>0){
							foreach($restantes->result() as $rest){
								//Agregar los detalles para cada detalle
								$dp=new Pr_detalle_pedido();
								$dp->pr_pedidos_id=$ped->id;
								$dp->cproductos_id=$pid;
								$dp->cproducto_numero_id=$rest->id;
								$dp->cantidad=0;
								if($precio->costo_unitario>0){
									$dp->costo_unitario=$precio->costo_unitario;
								} else
									$dp->costo_unitario=0;
									
								$dp->costo_total=0;
								$dp->save();
								$dp->clear();
							}
						}

					}
				}
			}
		}
		echo "<html> <script>alert(\"Se ha registrado la generación de los  Pre Pedidos de Resurtido, ingrese al Listado automatizado de Pre Pedidos.\");</script><h2>Proceso Finalizado</h2></html>";
	}

	function rep_comparativo_traspasos_html(){
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$cantidad_minima = $this->input->post('cantidad_minima');
		$fecha2 = $this->input->post('fecha2');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['marca']="Varias";
		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		}  else if($_POST['fecha2']=="" and $_POST['fecha1']=='') {
			show_error("Por favor elija la fecha de inicio y la fecha final del periodo de tiempo");
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where e.fecha>='$fecha1' and e.fecha<'$fecha2'";
		//Obtener los espacios fisicos seleccionados
		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$t]=$_POST['chk'.$x];
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos_tag[$t]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$listado_espacios=implode(", ", $efisicos);
		$count_espacios=count($efisicos);

		//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
		$matriz_p=array();
		$sql1="select distinct(e.cproductos_id) as cproductos_id, p.descripcion, p.ruta_foto, m.proveedor_id  from entradas as e left join cproductos as p on p.id=e.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id $where and e.espacios_fisicos_id in ($listado_espacios) and e.estatus_general_id=1 and ctipo_entrada=2 group by e.cproductos_id, p.descripcion, m.proveedor_id, p.ruta_foto order by p.descripcion ";
		$datos=$this->db->query($sql1);

		if($datos->num_rows()>0){
			foreach($datos->result() as $renglon){
				$bandera=3*$count_espacios;
				//Recorrer cada producto para asignarlo a los espacios fisicos
				$matriz_p[$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
				$matriz_p[$renglon->cproductos_id]['ruta_foto']=$renglon->ruta_foto;
				$matriz_p[$renglon->cproductos_id]['proveedor_id']=$renglon->proveedor_id;

				foreach($efisicos as $k=>$v){

					$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";

					$mtr_numeros=array();
					$where_i=" where ef.id='$v' and fecha>='$fecha1' and fecha<'$fecha2' ";
					$where_abs1=" where ef.id='$v' and fecha<'$fecha2' ";
					$where_abs2=" where ef.id='$v' and fecha<'$fecha1' ";
						
					$existencia=$this->almacen->existencias($renglon->cproductos_id,$where_i,$where_abs1, $where_abs2);

					if($existencia['entradas']==''){
						$existencia['entradas']=0;
						//$bandera-=3;
					}
					if($existencia['salidas']==''){
						$existencia['salidas']=0;
					}
					if($existencia['salidas']<$cantidad_minima){
						$bandera-=3;
					}
					if($existencia['existencias']==0){
						// 						$bandera-=1;
					}
					//					echo "EF:$v pid=$renglon->cproductos_id -> ".$existencia['entradas']."<br/>";
					$matriz_p[$renglon->cproductos_id]['compra_'.$v]=$existencia['entradas'];

					$matriz_p[$renglon->cproductos_id]['venta_'.$v]=$existencia['salidas'];
					$matriz_p[$renglon->cproductos_id]['existencia_'.$v]=$existencia['existencias'];
					$matriz_p[$renglon->cproductos_id]['existencia_abs2_'.$v]=$existencia['existencias_abs2'];
					unset($existencia);
					// 					//Obtener la numeracion dentro del espacio fisico que presenta movimientos
					$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from salidas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad order by numero_mm");
					//
					if($numeros->num_rows()>0){
						foreach($numeros->result() as $numero){
							//$mtr_numeros[$numero->numero_mm]=$numero->numero_mm/10;
							$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";

						}
					} else {
						// 						$mtr_numeros[0]=0;
						$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from entradas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad");

						if($numeros->num_rows()>0){
							foreach($numeros->result() as $numero){
								$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";
								// 							$mtr_numeros_cantidad[$numero->numero_mm]=$numero->pares;
							}
						} else
							$mtr_numeros[0]=0;
						if(count($mtr_numeros)>1 and isset($mtr_numeros[0]))
							unset($mtr_numeros[0]);
					}
					$matriz_p[$renglon->cproductos_id]['numeros_'.$v]=implode(", ",$mtr_numeros);

					/**obtener para cada cproducto_id($renglon->cproductos_id ) y sucursal ($v), la ultima compra, */
					//Paso 1 Obtener el espacio_fisico_id del almacen de leon de la tienda
					$espacio_almacen=$this->espacio_fisico->where('tipo_espacio_id', 1)->where('espacio_fisico_matriz_id',$v)->limit(1)->get();
					if ($espacio_almacen->id>0){
						$espacio_aid=$espacio_almacen->id;

					} else {
						$espacio_aid=$v;
					}
					//Paso 2, Encontrar la entrada de la factura
					$entradas_a=$this->entrada->select('pr_facturas_id as id, date(fecha) as fecha')->where('espacios_fisicos_id', $espacio_aid)->where('cproductos_id', $renglon->cproductos_id)->where('estatus_general_id',1)->where('ctipo_entrada', 1)->order_by("fecha desc")->limit(1)->get();
					if($entradas_a->id>0){
						$factura_prod=$entradas_a->id;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]=$entradas_a->fecha."&Fa: ".$entradas_a->id;

					} else {
						$factura_prod=0;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]="-&0&0";
					}
					unset($espacio_almacen); unset($entradas_a);
						
					/** Seccion para identificar pedidos en proceso de un cproducto_id y sucursal */
					//Paso 1 Encontrar pr_pedidos con los datos anteriores
					$pedidos=$this->db->query("select distinct(pr_pedidos_id) as pr_pedido_id from pr_detalle_pedidos where cproductos_id=$renglon->cproductos_id  group by pr_pedidos_id");
					if($pedidos->num_rows()>0) {
						$pedidos_id=array();
						foreach($pedidos->result() as $row) {
							$pedidos_id[]=$row->pr_pedido_id;
						}
						$list_pedidos=implode(', ',$pedidos_id);
					} else {
						//No hay pedidos para dicho producto
						$list_pedidos="0";
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";
					}
					$where_p =" and p.id in ($list_pedidos) ";
					$pedido_final=$this->db->query("select p.id, date(p.fecha_alta) as fecha_alta, e.tag as estatus, lf.lote_id from pr_pedidos  as p left join cpr_estatus_pedidos as e on e.id=p.cpr_estatus_pedido_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=p.id where espacio_fisico_id=$espacio_aid $where_p and estatus_general_id=1  and cpr_estatus_pedido_id<3 order by fecha_alta desc limit 1");
						
					if($pedido_final->num_rows()>0){
						$pedido_final_row=$pedido_final->row();
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:red;width:100%;'>$pedido_final_row->id -$pedido_final_row->fecha_alta- $pedido_final_row->estatus</div>";


						unset($pedido_final); unset($pedido_final_row); unset($pedidos); unset($pedidos_id);
					} else {
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";

					}
						
					/** Subsección para obtener la fecha de traspaso de salida y de entrada del producto en base al lote y el producto*/
					//Primero encontrar la salida por traspaso
					$st=new Salida();
					$se=new Entrada();

					if($factura_prod>0) {
						$ent=new Entrada();
						$ent->where('pr_facturas_id', $factura_prod)->where('estatus_general_id',1)->limit(1)->get();
						$lote_id=$ent->lote_id;
						$ent->clear();
						$se->select("id, date(fecha) as fecha")->where('lote_id', $lote_id)->where('cproductos_id', $renglon->cproductos_id)->where('ctipo_entrada', 2)->where('estatus_general_id', 1)->limit(1)->get();
					}
					if($st->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]=$st->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					}
					unset($st);
					//Segundo encontrar la entrada por traspaso
					if($se->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]=$se->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";
					}
				}
				if($bandera<=0)
					unset($matriz_p[$renglon->cproductos_id]);
				unset($renglon);

			}
		} else {
			echo $sql1;
			show_error("No hay datos para los criterios seleccionados");
				
		}
		$data['espacios']=$efisicos;
		$data['espacios_tag']=$efisicos_tag;
		$data['datos']=$matriz_p;
		$data['ruta']=$ruta;
		$data['title']="Reporte Comparativo para Generacion de Pre Traspasos";
		$this->load->view("$ruta/rep_comparativo_traspasos_html", $data);
	}

	function rep_pre_traspaso(){
		$this->load->model("pre_traspaso");
		$this->load->model("pre_traspaso_detalle");
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pre traspaso no existe'.$id);
		$data['title']="Orden de Pre Traspaso";
		$data['generales']=$this->pre_traspaso->get_pre_traspaso_pdf($id);
		if($data['generales']==false)
			show_error('El Pre Traspaso no existe');
		$data['detalles']=$this->pre_traspaso_detalle->get_pre_traspaso_detalles_by_parent_pdf($id);
		if($data['generales']==false)
			show_error('El Pre Traspaso no tiene detalles');
		$this->load->view("ejecutivo/rep_pre_traspaso", $data);
	}

	function rep_comparativo_estacionado_pdf(){
		global $ruta;
		//Obtener los datos
		$marca_id = $this->input->post('cmarca_id');
		$marca = $this->input->post('marca_drop');
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['marca']=$marca;
		$data['marca_id']=$marca_id;
		$cantidad_minima = $this->input->post('cantidad_minima');
		//		$data['marca']="Varias";
		//Fecha
		if($fecha1=="" and strlen($fecha2)>0) {
			$hoy=date("Y-m-d");
			$fecha1a=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2=="" and strlen($fecha1)>0) {
			$hoy=date("Y-m-d");
			$fecha2a=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2);
			//			$fecha2_prev=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where e.fecha>='$fecha1a' and e.fecha<'$fecha2a '";
		//Obtener los espacios fisicos seleccionados
		//Marca
		if($marca_id>0)
			$where.=" and p.cmarca_producto_id=$marca_id ";

		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$t]=$_POST['chk'.$x];
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos_tag[$t]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$listado_espacios=implode(", ", $efisicos);
		$count_espacios=count($efisicos);

		//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
		$matriz_p=array();
		$sql1="select distinct(e.cproductos_id) as cproductos_id, p.descripcion, p.ruta_foto, p.precio1, p.cfamilia_id, m.proveedor_id  from entradas as e left join cproductos as p on p.id=e.cproductos_id left join cmarcas_productos as m on m.id=p.cmarca_producto_id $where and e.espacios_fisicos_id in ($listado_espacios) and e.estatus_general_id=1 and ctipo_entrada=2 and p.cfamilia_id!=5 group by e.cproductos_id, p.descripcion, m.proveedor_id, p.ruta_foto, precio1, cfamilia_id order by p.descripcion ";
		$datos=$this->db->query($sql1);

		/*		$where_cat=" where cmarca_producto_id= '$marca_id'";*/

		//echo $count_espacios."<br/>";
		if($datos->num_rows()>0){
			foreach($datos->result() as $renglon){
				$bandera=3*$count_espacios;
				//Recorrer cada producto para asignarlo a los espacios fisicos
				$matriz_p[$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
				$matriz_p[$renglon->cproductos_id]['precio_venta']=number_format($renglon->precio1,2,".",",");
				$matriz_p[$renglon->cproductos_id]['ruta_foto']=$renglon->ruta_foto;
				$matriz_p[$renglon->cproductos_id]['proveedor_id']=$renglon->proveedor_id;
				$matriz_p[$renglon->cproductos_id]['familia']=$renglon->cfamilia_id;

				//Obtener el ultimo precio de compra de dicho producto
				$p_compra=$this->entrada->get_precio_compra($renglon->cproductos_id);
				if($p_compra!=false)
					$matriz_p[$renglon->cproductos_id]['precio_compra']=number_format($p_compra->costo_unitario,2,".",",");
				else
					$matriz_p[$renglon->cproductos_id]['precio_compra']="-000";

				foreach($efisicos as $k=>$v){

					$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";

					$mtr_numeros=array();
					$where_i=" where ef.id='$v' and fecha>='$fecha1a' and fecha<'$fecha2a' ";
					$where_abs1=" where ef.id='$v' and fecha<'$fecha2a' ";
					$where_abs2=" where ef.id='$v' and fecha<'$fecha1a' ";

					$existencia=$this->almacen->existencias($renglon->cproductos_id,$where_i, $where_abs1, $where_abs2);

					if($existencia['entradas']==''){
						$existencia['entradas']=0;
						//$bandera-=3;
					}
					if($existencia['salidas']==''){
						$existencia['salidas']=0;
					}
					if($existencia['salidas']>$cantidad_minima){
						$bandera-=3*$count_espacios;
					}
					if($existencia['existencias']==0){
						// 						$bandera-=1;
					}
					//					echo "EF:$v pid=$renglon->cproductos_id -> ".$existencia['entradas']."<br/>";
					$matriz_p[$renglon->cproductos_id]['compra_'.$v]=$existencia['entradas'];

					$matriz_p[$renglon->cproductos_id]['venta_'.$v]=$existencia['salidas'];
					// 					$matriz_p[$renglon->cproductos_id]['existencia_'.$v]=$existencia['existencias'];
					$matriz_p[$renglon->cproductos_id]['existencia_abs_'.$v]=$existencia['existencias_abs'];
					$matriz_p[$renglon->cproductos_id]['existencia_abs2_'.$v]=$existencia['existencias_abs2'];
					unset($existencia);
					// 					//Obtener la numeracion dentro del espacio fisico que presenta movimientos
					$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from salidas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad order by numero_mm");
					if($numeros->num_rows()>0){
						foreach($numeros->result() as $numero){
							//$mtr_numeros[$numero->numero_mm]=$numero->numero_mm/10;
							$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";

						}
					} else {
						// 						$mtr_numeros[0]=0;
						$numeros=$this->db->query("select distinct(pn.numero_mm) as numero_mm, sum(s.cantidad) as pares from entradas as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id where s.cproductos_id=$renglon->cproductos_id and s.espacios_fisicos_id=$v group by numero_mm, s.cantidad");

						if($numeros->num_rows()>0){
							foreach($numeros->result() as $numero){
								$mtr_numeros[$numero->numero_mm]=($numero->numero_mm/10)."-".round($numero->pares,0)."";
								// 							$mtr_numeros_cantidad[$numero->numero_mm]=$numero->pares;
							}
						} else
							$mtr_numeros[0]=0;
						if(count($mtr_numeros)>1 and isset($mtr_numeros[0]))
							unset($mtr_numeros[0]);
					}
					$matriz_p[$renglon->cproductos_id]['numeros_'.$v]=implode(", ",$mtr_numeros);

					/**obtener para cada cproducto_id($renglon->cproductos_id ) y sucursal ($v), la ultima compra, */
					//Paso 1 Obtener el espacio_fisico_id del almacen de leon de la tienda
					$espacio_almacen=$this->espacio_fisico->where('tipo_espacio_id', 1)->where('espacio_fisico_matriz_id',$v)->limit(1)->get();
					if ($espacio_almacen->id>0){
						$espacio_aid=$espacio_almacen->id;

					} else {
						$espacio_aid=$v;
					}
					//Paso 2, Encontrar la entrada de la factura
					$entradas_a=$this->entrada->select('pr_facturas_id as id, date(fecha) as fecha')->where('espacios_fisicos_id', $espacio_aid)->where('cproductos_id', $renglon->cproductos_id)->where('estatus_general_id',1)->where('ctipo_entrada', 1)->order_by("fecha desc")->limit(1)->get();
					if($entradas_a->id>0){
						$factura_prod=$entradas_a->id;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]=$entradas_a->fecha."&Fa: ".$entradas_a->id;

					} else {
						$factura_prod=0;
						$matriz_p[$renglon->cproductos_id]['entrada_compra_'.$v]="-&0&0";
					}
					unset($espacio_almacen); unset($entradas_a);
						
					/** Seccion para identificar pedidos en proceso de un cproducto_id y sucursal */
					//Paso 1 Encontrar pr_pedidos con los datos anteriores
					$pedidos=$this->db->query("select distinct(pr_pedidos_id) as pr_pedido_id from pr_detalle_pedidos where cproductos_id=$renglon->cproductos_id  group by pr_pedidos_id");
					if($pedidos->num_rows()>0) {
						$pedidos_id=array();
						foreach($pedidos->result() as $row) {
							$pedidos_id[]=$row->pr_pedido_id;
						}
						$list_pedidos=implode(', ',$pedidos_id);
					} else {
						//No hay pedidos para dicho producto
						$list_pedidos="0";
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";
					}
					$where_p =" and p.id in ($list_pedidos) ";
					$pedido_final=$this->db->query("select p.id, date(p.fecha_alta) as fecha_alta, e.tag as estatus, lf.lote_id from pr_pedidos  as p left join cpr_estatus_pedidos as e on e.id=p.cpr_estatus_pedido_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=p.id where espacio_fisico_id=$espacio_aid $where_p and estatus_general_id=1  and cpr_estatus_pedido_id<3 order by fecha_alta desc limit 1");

					/*					if($renglon->cproductos_id==25158 ){
						echo "select p.id, date(p.fecha_alta) as fecha_alta, e.tag as estatus, lf.lote_id from pr_pedidos  as p left join cpr_estatus_pedidos as e on e.id=p.cpr_estatus_pedido_id left join lotes_pr_facturas as lf on lf.pr_pedido_id=p.id where espacio_fisico_id=$espacio_aid $where_p and estatus_general_id=1  and cpr_estatus_pedido_id<3 order by fecha_alta desc limit 1";
					}*/
						
					if($pedido_final->num_rows()>0){
						$pedido_final_row=$pedido_final->row();
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:red;width:100%;'>$pedido_final_row->id -$pedido_final_row->fecha_alta- $pedido_final_row->estatus</div>";


						unset($pedido_final); unset($pedido_final_row); unset($pedidos); unset($pedidos_id);
					} else {
						$matriz_p[$renglon->cproductos_id]['pr_pedido_'.$v]="<div style='background-color:green;width:100%;'>-</div>";
					}
					/** Subsección para obtener la fecha de traspaso de salida y de entrada del producto en base al lote y el producto*/
					//Primero encontrar la salida por traspaso
					$st=new Salida();
					$se=new Entrada();
					if($factura_prod>0) {
						$ent=new Entrada();
						$ent->where('pr_facturas_id', $factura_prod)->where('estatus_general_id',1)->where('fecha >=',$fecha1a)->where('fecha <',$fecha2a)->limit(1)->get();
						$lote_id=$ent->lote_id;
						$ent->clear();
						$se->select("id, date(fecha) as fecha")->where('lote_id', $lote_id)->where('cproductos_id', $renglon->cproductos_id)->where('ctipo_entrada', 2)->where('estatus_general_id', 1)->where('fecha >=',$fecha1a)->where('fecha <',$fecha2a)->limit(1)->get();
					}
					if($st->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]=$st->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_salida_'.$v]="-";
					}
					unset($st);
					//Segundo encontrar la entrada por traspaso
					if($se->id>0){
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]=$se->fecha;
					} else {
						$matriz_p[$renglon->cproductos_id]['tr_entrada_'.$v]="-";
					}
				}
				if($bandera<=0)
					unset($matriz_p[$renglon->cproductos_id]);
				unset($renglon);
			}
		} else {
			// 			echo $sql1;
			show_error("No hay datos para los criterios seleccionados");
		}
		$data['espacios']=$efisicos;
		$data['espacios_tag']=$efisicos_tag;
		$data['datos']=$matriz_p;
		$data['ruta']=$ruta;
		$data['title']="Reporte Comparativo de Movimiento de Compras";
		$this->load->view("$ruta/rep_comparativo_estacionado_html", $data);
	}

	function rep_comparativo_periodos_pdf() { // BEGIN method rep_productos_pdf
		global $ruta;
		$this->load->model("nota_remision");
		$this->load->model("salida");
		$etiquetas=array();
		$colect=array();
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1'); $fecha2 = $this->input->post('fecha2'); $fecha3 = $this->input->post('fecha3'); 	$fecha4 = $this->input->post('fecha4');
		$data['fecha1']=$fecha1; $data['fecha2']=$fecha2;	$data['fecha3']=$fecha3; $data['fecha4']=$fecha4;

		//Periodo 1
		$fecha=explode(" ", $fecha1);
		$fecha1d=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		$fecha=explode(" ", $_POST['fecha2']);
		$fecha2d=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		$colectador['periodo1']['fecha1']=$fecha1d;
		$colectador['periodo1']['fecha2']=$fecha2d;

		//Periodo 2
		$fecha=explode(" ", $_POST['fecha3']);
		$fecha3d=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		$fecha=explode(" ", $_POST['fecha4']);
		$fecha4d=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		$colectador['periodo2']['fecha1']=$fecha3d;
		$colectador['periodo2']['fecha2']=$fecha4d;

		//Obtener los espacios fisicos seleccionados
		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos[$e->id]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$data['count_espacios']=count($efisicos);

		//Bucle para generar los periodos
		for($k=1;$k<3;$k++){
			$etiquetas[$k-1]="Periodo ".$k;
			//Contadores para las graficas
			$valores_gastos[$k-1]=0; $valores_ventas[$k-1]=0; $valores_compras[$k-1]=0;$valores_pares[$k-1]=0; $valores_utilidad[$k-1]=0;
			// Condicionante para los Gastos
			$where_g =" where g.fecha>='".$colectador['periodo'.$k]['fecha1']."' and g.fecha<'".$colectador['periodo'.$k]['fecha2']."' ";
			//Condicionante para las compras
			$where_s =" where s.fecha>='".$colectador['periodo'.$k]['fecha1']."' and s.fecha<'".$colectador['periodo'.$k]['fecha2']."' ";
			foreach($efisicos as $e_id => $e_tag){

				//Costo
				$compra=$this->salida->get_monto_compra_marca($e_id, $where_s);
				//Ventas
				$ventas=$this->nota_remision->get_ventas_espacios($colectador['periodo'.$k]['fecha1'],$colectador['periodo'.$k]['fecha2'],$e_id);
				//Gastos
				$datos=$this->db->query("select sum(g.monto) as monto from gastos_detalles as g left join cgastos as cg on cg.id=g.cgastos_id $where_g and espacios_fisicos_id='$e_id' and g.estatus_general_id=1 ");

				if($datos->num_rows()==1)
					$t=$datos->row();
				else
					$t->monto=0;
				if($ventas==false){
					$ventas->venta_total=0;
					$ventas->devolucion=0;
				}
				//Pares vendidos
				$datos=$this->db->query("select sum(s.cantidad) as pares from salidas as s $where_s  and espacios_fisicos_id='$e_id' and s.estatus_general_id=1 and ctipo_salida_id=1");
				if($datos->num_rows()==1)
					$pares=$datos->row();
				else
					$pares->pares=0;

				//guardar los datos individuales
				$colectador["periodo$k"][$e_id]['ventas']=round($ventas->venta_total,2) - round($ventas->devolucion,2);
				$colectador["periodo$k"][$e_id]['gastos']=round($t->monto,2);
				$colectador["periodo$k"][$e_id]['pares']=round($pares->pares);
				$colectador["periodo$k"][$e_id]['compra']=round($compra,2);
				$colectador["periodo$k"][$e_id]['utilidad']=$colectador["periodo$k"][$e_id]['ventas']-$colectador["periodo$k"][$e_id]['compra']-$colectador["periodo$k"][$e_id]['gastos'];

				//Obtener totales para las graficas
				$valores_gastos[$k-1]+=$colectador["periodo$k"][$e_id]['gastos'];
				$valores_ventas[$k-1]+=$colectador["periodo$k"][$e_id]['ventas'];
				$valores_compras[$k-1]+=$colectador["periodo$k"][$e_id]['compra'];
				$valores_pares[$k-1]+=$colectador["periodo$k"][$e_id]['pares'];
				$valores_utilidad[$k-1]+=$colectador["periodo$k"][$e_id]['utilidad'];
			}
		}

		//Graficas
		$this->graficas->comparativo($valores_gastos, $etiquetas, "Comparativo Gastos", 'gastos.jpeg');
		$this->graficas->comparativo($valores_ventas, $etiquetas, "Comparativo Ventas", 'ventas.jpeg');
		$this->graficas->comparativo($valores_compras, $etiquetas, "Comparativo Costos", 'compras.jpeg');
		$this->graficas->comparativo($valores_pares, $etiquetas, "Comparativo Pares", 'pares.jpeg');
		$this->graficas->comparativo($valores_utilidad, $etiquetas, "Comparativo Utilidad", 'utilidad.jpeg');
		$data['datos']=$colectador;
		$data['efisicos']=$efisicos;
		$data['title']="Reporte Comparativo de dos periodos";
		$this->load->view("$ruta/rep_comparativo_periodos_pdf", $data);
		unset($data);
	} // END method rep_productos_pdf

	function rep_ejecutivo_compras_pdf(){
		global $ruta;
		//Obtener los datos
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$validacion = $this->input->post('validacion');

		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		//Fecha
		if($fecha1=="" and strlen($fecha2)>0) {
			$hoy=date("Y-m-d");
			$fecha1a=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $fecha2);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($fecha2=="" and strlen($fecha1)>0) {
			$hoy=date("Y-m-d");
			$fecha2a=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $fecha1);
			$fecha1a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2);
			//			$fecha2_prev=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
		}
		$where =" where estatus_general_id=1 and estatus_factura_id<4 and pr.fecha>='$fecha1a' and pr.fecha<'$fecha2a'";
		//Obtener los espacios fisicos seleccionados
		$t=1; $efisicos_tag=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$t]=$_POST['chk'.$x];
				$e=$this->espacio_fisico->get_by_id($_POST['chk'.$x]);
				$efisicos_tag[$e->id]=$e->tag;
				unset($_POST['chk'.$x]);
				$t+=1;
			}
		}
		$listado_espacios=implode(", ", $efisicos);
		$count_espacios=count($efisicos);
		//Clausula para filtrar los espacios fisicos
		//		$where.=" and pr.espacios_fisicos_id in ($listado_espacios) ";

		//Validacion
		if($validacion==0)
			$where.=" and validacion_contable=0 ";
		else if($validacion==1)
			$where.=" and validacion_contable=1 ";
		$colect=array(); $total=0;
		//Obtener los totales por espacio fisico
		foreach($efisicos_tag as $k=>$v){
			$colect[$k]['tag']=$v;
			$colect[$k]['pares']=0;
			$colect[$k]['compra']=0;
			$totales=$this->db->query("select id, monto_total  from pr_facturas as pr $where and espacios_fisicos_id=$k");
			if($totales->num_rows()>0){
				foreach($totales->result() as $row){
					$colect[$k]['compra']+=$row->monto_total;
					$colect[$k]['pares']+=$this->entrada->get_pares_by_pr_factura($row->id);
				}
			}
			$valores_abs[]=$colect[$k]['compra'];
			$total+=$colect[$k]['compra'];
			$etiquetas[]=$v;
		}
		foreach($valores_abs as $t){
			$valores[]=100*$t/$total;
			//echo "%-".round(100*$t/$total,2)."-$total<br/>";
		}

		//print_r($valores_abs);
		$title="Grafica de Ejecutiva de Compras";
		$data['title']=$title;
		$this->graficas->ventas_globales($valores, $etiquetas, $title);
		$data['datos']=$colect;
		$this->load->view("$ruta/rep_ejecutivo_compras_pdf", $data);
		unset($data);
	}
        
        function rep_ventas_gerencia_pdf(){
            $this->load->model("nota_remision");
		$this->load->model("espacio_fisico");
		$espacio = $this->input->post('espacio_fisico_id');
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if(isset($_POST['fecha1'])==false or strlen($_POST['fecha1'])==0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$data['fecha1']=date("d m Y", strtotime($hoy));;
			$data['fecha2']=$data['fecha1'];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$fecha=explode(" ", $_POST['fecha2']);
		$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]));
		if($espacio>0) {
			$data['espacio']=$this->espacio_fisico->get_by_id($espacio);
			$data['espacio_nombre']=$data['espacio']->tag;
                        $data['espacio_id']=$data['espacio']->id;
		} else {
			$data['espacio_nombre']="TODAS";
                        $data['espacio_id']=0;
		}
		
			$data['ventas_empleados']=$this->nota_remision->get_ventas_empleados($fecha1,$fecha2, $espacio);
			$data['fletes']=$this->nota_remision->get_ventas_empleados_flete($fecha1,$fecha2, $espacio);
                        $data['ventas_totales']=$this->nota_remision->get_ventas($fecha1,$fecha2);
			$data['comision_tienda']=$this->nota_remision->get_comision_tienda($espacio);
			$data['abonos']=$this->nota_remision->get_abonos_empleados($fecha1,$fecha2,$espacio);
                        $data['gastos']=$this->nota_remision->get_ventas_gastos_detalles($fecha1,$fecha2,$espacio);
                        $data['abonos_vales']=$this->nota_remision->get_abonos_empleados_vales($fecha1,$fecha2,$espacio);
                        $this->load->view("ejecutivo/rep_ventas_gerencia_pdf", $data);
            
            
        }
        
        
        
        function rep_ventas_empleado_pdf(){
            $this->load->model("nota_remision");
		$espacio = 0;
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if(isset($_POST['fecha1'])==false or strlen($_POST['fecha1'])==0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$data['fecha1']=date("d m Y", strtotime($hoy));;
			$data['fecha2']=$data['fecha1'];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$fecha=explode(" ", $_POST['fecha2']);
		$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]));

			$data['ventas_empleados']=$this->nota_remision->get_ventas_gral_empleados($fecha1,$fecha2, $espacio);
				$data['t_empleado']=$this->nota_remision->get_empleados_total();
			$data['fletes']=$this->nota_remision->get_ventas_empleados_flete($fecha1,$fecha2, $espacio);
    	$data['ventas_totales']=$this->nota_remision->get_ventas($fecha1,$fecha2);
			$data['comision_tienda']=$this->nota_remision->get_comision_tienda($espacio);
			$data['abonos']=$this->nota_remision->get_abonos_empleados($fecha1,$fecha2,$espacio);
    $data['gastos']=$this->nota_remision->get_ventas_gastos_detalles($fecha1,$fecha2,$espacio);
    $data['abonos_vales']=$this->nota_remision->get_abonos_empleados_vales($fecha1,$fecha2,$espacio);
$data['abonos_cancelados']=$this->nota_remision->get_abonos_empleados_cancelados($fecha1,$fecha2,$espacio,$espacio);
     $this->load->view("ejecutivo/rep_ventas_empleado_pdf", $data);
            
            
        }
        
}
?>
