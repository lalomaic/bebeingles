<?php
class Arqueos_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Arqueos_c()
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
		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		$this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("estatus_general");
		$this->load->model("producto");
		$this->load->model("arqueo");
		$this->load->model("arqueo_detalle");
		//	  $this->load->model("ctipo_ajuste_detalle");
		$this->load->model("stock");
		$this->load->model("stock_detalle");
		$this->load->model("espacio_stock");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['empresa_id']=$row->empresas_id;
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
	  //Inicio del Bloque Alta Familia
	  if($subfuncion=="alta_ajuste_inventario"){
	  	if($this->uri->segment(5)=="subir_inventario"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if($id>0){
	  			$data['id']=$id;
	  			$data['arqueo']=$this->arqueo->get_by_id($id);
	  			//Mostrar formulario
	  			$this->load->view('supervision/subir_inventario', $data);
	  			//redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No se pudo subir el archivo del inventario";
	  		}
	  	} else {
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		//Obtener Datos
	  		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
	  	}
	  } else if ($subfuncion == "alta_inventario_parcial") {
	  	$this->load->model('arqueo_parcial');
	  	if ($this->uri->segment(5) == "subir_inventario") {
	  		$id = $this->uri->segment(6);
	  		$main_view = false;
	  		if ($id > 0) {
	  			$data['id'] = $id;
	  			$data['arqueo'] = $this->arqueo_parcial->get_by_id($id);
	  			//Mostrar formulario
	  			$this->load->view('supervision/subir_inventario_parcial', $data);
	  			//redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view = true;
	  			//Definir la vista
	  			$data['principal'] = "error";
	  			$data['error_field'] = "No se pudo subir el archivo del inventario";
	  		}
	  	} else {
	  		$data['principal'] = "$ruta/$subfuncion";
			// Obtener Espacios
	  		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
	  	}
	  }  else if($subfuncion=="list_ajuste_inventario"){
				if($this->uri->segment(5)=="editar_ajuste_inventario"){
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_ajuste_inventario";
					//Obtener Datos
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					//$data['productos']=$this->producto->get_cproductos_detalles();
					$data['arqueo']=$this->arqueo->get_arqueo($id);
					$data['arqueo_detalles']=$this->arqueo_detalle->get_arqueo_detalles_by_parent($id);
					//$data['tipos_ajuste']=$this->ctipo_ajuste_detalle->get_ctipos_ajuste_detalles();
					if($data['arqueo_detalles']!=false){
						$data['rows']=count($data['arqueo_detalles']->all);
					} else {
						$data['rows']=0;
						show_error("No hay movimiento de productos en dicha ubicación");
					}
				} else if($this->uri->segment(5)=="cancelar_ajuste_inventario"){
					$id=$this->uri->segment(6);
					$main_view=true;

					if(substr(decbin($data['permisos']), 2, 1)==1){
						//Paso No 1. Obtener los detalles de los arqueos
						$query=$this->db->query("select * from arqueo_detalles where arqueo_id=$id  and transaccion_id>0");
						if($query->num_rows()>0){
							foreach($query->result() as $row){
								//Paso No. 2 Deshabilitar los registros de las tablas entradas y salidas
								if($row->ctipo_ajuste_detalle_id==3 ){
									//Entradas
									$this->db->query("update entradas set estatus_general_id=2 where id=$row->transaccion_id" );
								}
								if($row->ctipo_ajuste_detalle_id==4){
									//Salida
									$this->db->query("update salidas set estatus_general_id=2 where id=$row->transaccion_id" );
								}
								unset($row);
							}
							$this->db->query("update arqueo_detalles  set estatus_general_id=2 where arqueo_id=$id" );
						}

						//Paso no 3. Cancelar el arqueo y sus detalles
						$u=new Arqueo();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();

						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Ajuste de Inventario";
					}
				} else  {
					//Cargar los datos para el listado
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_ajuste_inventario/";
					$config['per_page'] = '20';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}

					$u1=new Arqueo();
					$u1->get();
					/*	      foreach($u1->all as $row){
					 $ad=$this->arqueo_detalle->get_arqueo_detalles_by_parent($row->id);
					if($ad==false){
					$this->db->query("update arqueos set estatus_general_id=2 where id=$row->id");
					}
					}*/
					$total_registros=$u1->get()->c_rows;
					$data['arqueos']=$this->arqueo->get_arqueos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
	  } //Final del Listado de Arqueos
	  else if ($subfuncion == "list_ajuste_parcial_inventario") {
	  	if ($this->uri->segment(5) == "editar_ajuste_inventario") {
	  		$id = $this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal'] = $ruta . "/editar_ajuste_inventario";
	  		//Obtener Datos
	  		$data['empresa'] = $this->empresa->get_empresa($empresa_id);
	  		//$data['productos']=$this->producto->get_cproductos_detalles();
	  		$data['arqueo'] = $this->arqueo->get_arqueo($id);
	  		$data['arqueo_detalles'] = $this->arqueo_detalle->get_arqueo_detalles_by_parent($id);
	  		$data['tipos_ajuste'] = $this->ctipo_ajuste_detalle->get_ctipos_ajuste_detalles();
	  		if ($data['arqueo_detalles'] != false) {
	  			$data['rows'] = count($data['arqueo_detalles']->all);
	  		} else {
	  			$data['rows'] = 0;
	  			show_error("No hay movimiento de productos en dicha ubicación");
	  		}
	  	} else if ($this->uri->segment(5) == "cancelar_ajuste_inventario") {
	  		$id = $this->uri->segment(6);
	  		$main_view = true;

	  		if (substr(decbin($data['permisos']), 2, 1) == 1) {
	  			//Paso No 1. Obtener los detalles de los arqueos
	  			$this->db->query("update arqueo_parcial_detalles  set estatus_general_id=2 where arqueo_parcial_id=$id");

	  			//Paso no 3. Cancelar el arqueo y sus detalles
	  			$u = new Arqueo_parcial();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id = 2;
	  			$u->save();

	  			redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
	  		} else {
	  			$main_view = true;
	  			//Definir la vista
	  			$data['principal'] = "error";
	  			$data['error_field'] = "No tiene permisos para deshabilitar el Ajuste de Inventario";
	  		}
	  	} else {
	  		//Cargar los datos para el listado
	  		//Definir la vista
	  		$data['principal'] = $ruta . "/" . $subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_ajuste_parcial_inventario/";
	  		$config['per_page'] = '20';
	  		$page = $this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if ($page > 0) {
	  			$offset = $page;
	  		} else if ($page == '') {
	  			$offset = 0;
	  		} else {
	  			$offset = 0;
	  		}

	  		$u1 = new Arqueo_parcial();
	  		$u1->get();
	  		/* 	      foreach($u1->all as $row){
	  		 $ad=$this->arqueo_detalle->get_arqueo_detalles_by_parent($row->id);
	  		if($ad==false){
	  		$this->db->query("update arqueos set estatus_general_id=2 where id=$row->id");
	  		}
	  		} */
	  		$total_registros = $u1->get()->c_rows;
	  		$this->load->model('arqueo_parcial');
	  		$data['arqueos'] = $this->arqueo_parcial->get_arqueos_list($offset, $config['per_page']);
	  		$data['total_registros'] = $total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}
	  }
	  //Inicio del Bloque Alta Subfamilia
	  else if($subfuncion=="alta_stock"){
	  	if($this->uri->segment(5)=='agregar_detalles_stock'){
	  		$data['title']="Agregar detalles a la Plantilla de Stock";
	  		$id=$this->uri->segment(6);
	  		if($id<=0)
	  			show_error("La plantilla de Stock no esta referenciada adecuadamente");
	  		$data['productos1']=$this->producto->get_cproductos_stock();
	  		$n=0;
	  		foreach($data['productos1']->all as $row){
	  			$productos[$n]['id']=$row->id;
	  			$productos[$n]['descripcion']=$row->descripcion." ".$row->unidad_medida;
	  			$productos[$n]['cfamilia_id']=$row->cfamilia_id;
	  			$productos[$n]['familia']=$row->familia;
	  			$n+=1;
	  		}
	  		$data['productos']=$productos;
	  		$data['plantilla']=$this->stock->get_stock($id);
	  		$data['principal']=$ruta."/agregar_detalles_stock";
	  	} else if($this->uri->segment(5)=='agregar_espacios'){
	  		$data['title']="Relacionar sucursales a la Plantilla de Stock";
	  		$id=$this->uri->segment(6);
	  		if($id<=0)
	  			show_error("La plantilla de Stock no esta referenciada adecuadamente");
	  		$data['espacios']=$this->espacio_fisico->get_espacios_by_empresa_id($GLOBALS['empresa_id']);
	  		$data['plantilla']=$this->stock->get_stock($id);
	  		$data['principal']=$ruta."/agregar_espacios";
	  	} else {
	  		$data['principal']=$ruta."/".$subfuncion;
	  	}
	  } else if($subfuncion=="list_stock"){
	  	if($this->uri->segment(5)=="editar_stock"){
	  		$id=$this->uri->segment(6);
	  		if(is_numeric($id)==false)
	  			show_error("No existe tal plantilla de Stock, intente de nuevo");
	  		$data['plantilla']=$this->stock->get_stock($id);
	  		$data['principal']=$ruta."/editar_stock";
	  	} else if ($this->uri->segment(5)=='editar_detalles_stock'){
	  		$id=$this->uri->segment(6);
	  		if($id<=0)
	  			show_error("La plantilla de Stock no esta referenciada adecuadamente");
	  		$data['plantilla']=$this->stock->get_stock($id);
	  		$data['productos0']=$this->producto->get_cproductos_stock();
	  		$data['productos1']=$this->stock_detalle->get_stock_detalle_by_stock_id($id);
	  		if($data['productos1']==false){
	  			$data['title']="Agregar detalles a la Plantilla de Stock: ";
	  			$data['productos1']=$this->producto->get_cproductos_stock();
	  			$data['principal']=$ruta."/agregar_detalles_stock";
	  		} else {
	  			$data['title']="Editar detalles de la Plantilla de Stock";
	  			$data['principal']=$ruta."/editar_detalles_stock";
	  		}
	  		$n=0;
	  		foreach($data['productos1']->all as $row){
	  			if(isset($row->stock_detalle_id)==true)
	  				$productos[$n]['d_id']=$row->stock_detalle_id;
	  			$productos[$n]['id']=$row->id;
	  			$productos[$n]['descripcion']=$row->descripcion." ".$row->unidad_medida;
	  			$productos[$n]['cfamilia_id']=$row->cfamilia_id;
	  			if(isset($row->cantidad)==true)
	  				$productos[$n]['cantidad']=$row->cantidad;
	  			$productos[$n]['familia']=$row->familia;
	  			$n+=1;
	  		}
	  		$data['productos']=$productos;
	  	} else if ($this->uri->segment(5)=='editar_espacios'){
	  		$data['title']="Editar las relaciones de las sucursales a la Plantilla de Stock";
	  		$id=$this->uri->segment(6);
	  		if($id<=0)
	  			show_error("La plantilla de Stock no esta referenciada adecuadamente");
	  		$data['espacios']=$this->espacio_fisico->get_espacios_by_empresa_id($GLOBALS['empresa_id']);
	  		$data['espacio_s']=$this->espacio_stock->get_espacio_stock_by_plantilla($id);
	  		$espacios_array=array();
	  		if($data['espacio_s']==true){

	  			foreach($data['espacio_s']->all as $linea){
	  				$espacios_array[$linea->espacio_fisico_id]=$linea->id;
	  			}
	  		} else {
	  			//$espacios_array=false;
	  		}
	  		$data['espacios_array']=$espacios_array;
	  		$data['plantilla']=$this->stock->get_stock($id);
	  		$data['principal']=$ruta."/editar_espacios";
	  	} else {
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_stock/";
	  		$config['per_page'] = '15';
	  		$page=$this->uri->segment(5);

	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0)
	  			$offset=$page;
	  		else if ($page=='')
	  			$offset=0;
	  		else
	  			$offset=0;

	  		$u1=new Stock();
	  		$u1->get();
	  		$total_registros=$u1->c_rows;
	  		$data['stocks']=$this->stock->get_stocks_list($offset, $config['per_page'], $GLOBALS['empresa_id']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}

	  } //Final del Bloque List stock
	  else if($subfuncion=='list_general_salidas_traspasos') {
				$data['paginacion']=true;
				$this->load->model('traspaso_tienda');
				if($this->uri->segment(5)=="cancelar_traspaso"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Traspaso_tienda();
						$u->get_by_id($id);
						$this->db->query("update salidas set estatus_general_id=2 where id=$u->salida_id");
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					}
				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_id']=$espacio_id=$this->input->post('espacio_fisico_id');
					$data['espacio_tag']=$espacio_tag=$this->input->post('espacio_drop');
					$data['espacio_rid']=$espacio_rid=$this->input->post('espacio_fisico_rid');
					$data['espacio_rtag']=$espacio_rtag=$this->input->post('espacio_rdrop');
					$data['descripcion']=$descripcion=strtoupper($this->input->post('descripcion'));

					// 				$data['gastos_detalles']=$this->gasto_detalle->get_gastos_detalles_filtrado($espacio_id, $fecha1, $fecha2);
					$data['cl_pedidos']=$this->traspaso_tienda->get_traspaso_tienda_filtrado($espacio_id, $espacio_rid, $fecha1, $fecha2, $descripcion);
					$data['cta']=0;
					if($data['cl_pedidos']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['cl_pedidos']->all);
				}  else {
					$main_view=true;
					//Cargar los datos para el listado de pedidos de compra en proceso
					$data['principal']=$ruta."/list_general_salidas_traspasos";
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_general_salidas_traspasos";
					$config['per_page'] = '100';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					$ubicacion_id=0;
					$total_registros=$this->traspaso_tienda->get_traspaso_tienda_by_espacio_count($ubicacion_id);
					$data['cl_pedidos']=$this->traspaso_tienda->get_traspaso_tienda_by_espacio($offset, $config['per_page'], $ubicacion_id);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
	  }

	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  	unset($data);
	  } else {
	  	//redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}
}
?>
