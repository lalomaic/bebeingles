<?php
class Tienda_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Tienda_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_css('autocomplete.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("almacen");
		$this->load->model("arqueo");
		$this->load->model("arqueo_detalle");
		$this->load->model("tipo_espacio");
		$this->load->model("estado");
		$this->load->model("municipio");
		$this->load->model("grupo");
		$this->load->model("puesto");
		$this->load->model("nota_remision");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['espacio_fisico_id']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['espacio_fisico_id']);
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}
	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id;
		$main_view=false;
		$data['empresa_id']=$empresa_id;
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
			if ($subfuncion=="rep_remisiones"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['espacios']=$this->espacio_fisico->get_espacios_f();
			} else if ($subfuncion=='rep_ventas_empleado_suc'){
				//Ventas de la sucursal por empleado
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
			} else if ($subfuncion == 'busqueda_producto') {

				$data['principal'] = $ruta . "/" . $subfuncion;
				//Obtener Datos
				$f = new Familia_producto();
				$sf = new Subfamilia_producto();
				$data['familias'] = $f->select('id,tag')->get()->all;
				if (count($data['familias']) == 0)
					show_error('No existen familias');
				$data['subfamilias'] = $sf->select('id,tag')->order_by('tag')->get()->all;
				if (count($data['subfamilias']) == 0)
					show_error('No existen subfamilias');
			}

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
			} else {
				redirect(base_url()."index.php/inicio/logout");
			}
		}
	}

	function rep_remisiones_pdf(){
		global $ruta, $subfuncion;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$filtro = array();
		if((int)$_POST['espaciof']>0)
			$filtro[] = "n.espacio_fisico_id = ".(int)$_POST['espaciof'];
		if (count($filtro))
			$where = " where n.estatus_general_id<3 and ".implode(" and ", $filtro)." ";
		else
			$where = "";

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
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "(n.fecha >= '$fecha1' and n.fecha <= '$fecha2')";
			else
				$filtro[] = "(n.fecha >= '$fecha2' and n.fecha <= '$fecha1')";
		}
		elseif($f1)
		$filtro[] = "n.fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "n.fecha = '$fecha2'";
		if (count($filtro))
			$where = " where ".implode(" and ", $filtro)." ";
		else
			$where = "";

		$order_by = "order by n.id";
		//die(var_dump($order_clause));
		$data['notas']=$this->nota_remision->get_notas_remision_pdf($where,$order_by);
		if($data['notas']==false) show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_remisiones_pdf", $data);
		//Enviarselo al view para general el PDF
	}

	function rep_existencia_pdf(){
		$id=$this->uri->segment(4);
		$data['title']="Alta de Existencia Física";
		$data['arqueo']=$this->arqueo->get_arqueo_pdf($id, $GLOBALS['espacio_fisico_id']);
		if($data['arqueo']==false)
			show_error("El nÃºmero de arqueo no pertenece a la sucursal de su cuenta");
		$data['arqueo_detalles']=$this->arqueo_detalle->get_arqueo_detalles_by_parent($id);
		if($data['arqueo_detalles']==false)
			show_error("El arqueo no contiene conceptos");
		$this->load->view("tienda/rep_existencia_pdf", $data);


	}

	function rep_pedido_traspaso(){
		$id=$this->uri->segment(4);
                if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de traspaso no existe '.$id);

		$data['title']="Traspaso de Mercancía";
                $this->load->model("lote");
		$data['generales']=$this->lote->get_traspaso_por_id($id);
                
                ////$this->traspaso->get_traspaso_pdf($id);
		if($data['generales']==false)
			show_error('El Pedido de Traspaso no existe');
                $envia_id=$data['generales']->espacio_fisico_envia_id;
                $recibe_id=$data['generales']->espacio_fisico_recibe_id;
                $data["espacio_envia"] = $this->espacio_fisico->get_espacios_f_tag($envia_id);
                $data["espacio_recibe"] = $this->espacio_fisico->get_espacios_f_tag($recibe_id);
                $data["usuario"] = strtoupper($GLOBALS['username']);
		$data['detalles'] = $this->lote->get_traspaso_salidas($id);
		if($data['generales']==false){
			show_error("Ha fallado el ingreso de mercancia por traspaso, favor de rectificar dicho pedido desde el listado de traspasos.");			
		}
		$this->load->view("tienda/traspasos_reportes/traspaso", $data);
	}

	function rep_ventas_empleado_suc_pdf(){
		//Procesar las fechas
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if(isset($_POST['fecha1'])==false or strlen($_POST['fecha1'])==0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy));
			$data['fecha1']=date("d m Y", strtotime($hoy));;
			$data['fecha2']=$data['fecha1'];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) );
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$this->load->model("nota_remision");
		$data['espacio']=$this->espacio_fisico->get_by_id($GLOBALS['espacio_fisico_id']);
		$data['espacio_nombre']=$data['espacio']->tag;
		$data['ventas_empleados']=$this->nota_remision->get_ventas_empleados($fecha1,$fecha2, $GLOBALS['espacio_fisico_id']);
		$this->load->view("tienda/rep_ventas_empleado_suc_pdf", $data);
	}
	function busqueda_producto_pdf(){
		$pid = $_POST['producto_id'];
                $p_num=$_POST['producto_m'];
		$tiendas = $this->espacio_fisico->get_espacios_tiendas();
		$detalles = array();
		$i = 0;
		foreach ($tiendas as $tienda){
			$where = "WHERE e.id = '".$GLOBALS['empresa_id']."' AND ef.id = '$tienda->id' AND fecha <= '".date("Y-m-d H:i:s")."'";
			$existencias = $this->almacen->buscar_producto_existencia($pid,$p_num, $where);
			//             print_r($existencias)."<br/>";
			if($existencias!=false){
				foreach($existencias as $k=>$v){
					if($v['existencias'] > 0){
					        $detalles[$i]['tienda'] = $tienda->tag;
						$detalles[$i]['existencias'] = $v['existencias'];
                                                $detalles[$i]['disponibles']=$v['disponibles'];
                                                $detalles[$i]['apartados']=$v['apartados'];
						$detalles[$i]['tag'] = $v['tag'];
                                                $detalles[$i]['talla'] = $v['talla'];
						$i++;
					}
				}
			}
		}
		$data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id'])->razon_social;
		if(count($detalles)==0)
			show_error("No hay existencia registrada de ese producto en otras tiendas");
		$data['detalles'] = $detalles;
		$this->load->view("tienda/busqueda_producto_pdf", $data);
	}

}
?>
