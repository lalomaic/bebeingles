<?php
class Ventas_c extends Controller {

	function Ventas_c()
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
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("ventas_validacion");
		$this->load->model("espacio_fisico");
		$this->load->model("estatus_general");
		$this->load->model("ccl_forma_cobro");
		$this->load->model("cobro");
		$this->load->model("cl_pedido");
		$this->load->model("cl_factura");
		$this->load->model("cl_detalle_pedido");
		$this->load->model("origen_pedido");
		$this->load->model("producto");
		$this->load->model("ccl_estatus_pedido");
		$this->load->model("cliente");
		$this->load->model("familia_producto");
		$this->load->model("subfamilia_producto");
		$this->load->model("marca_producto");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['ubicacion_id']);
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
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id;

		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title("$subfuncion"). " en ".$GLOBALS['ubicacion_nombre'];
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
			// Inicia Bloque Actualizar Precio de Venta
			if($subfuncion=="alta_actualizacion"){
				$main_view=true;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['familias']=$this->familia_producto->get_cproductos_familias();
				$data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
				$data['marca_productos']=$this->marca_producto->get_cmarcas_productos();
				//$data['productos']=$this->producto->get_cproductos();
				$data['bandera']=0;
			}

			else if($subfuncion=="alta_venta"){
				$main_view=true;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->ventas_validacion->validacion_cl_pedido();
				$data['rows_pred']='0';
				$data['rows']=10;
				$data['empresa']=$this->empresa->get_empresa($empresa_id);
				$data['clientes']=$this->cliente->get_clientes();
				$data['formas_cobro']=$this->ccl_forma_cobro->get_ccl_formas_cobro();
				$data['productos']=$this->producto->get_cproductos_detalles();
				$data['origenes']=$this->origen_pedido->get_origen_pedidos();
				$data['estatus']=$this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();

			} else if($subfuncion=="list_ventas"){

				if($this->uri->segment(5)=="editar_pedido_venta"){
					$main_view=true;
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pedido_venta";
					$data['validation']=$this->ventas_validacion->validacion_cl_pedido();
					//Obtener Datos
					$data['rows_pred']=0;
					$data['rows']=10;
					$data['pedido_venta']=$this->cl_pedido->get_cl_pedido($id);
					$data['cl_detalle']=$this->cl_detalle_pedido->get_cl_detalles_pedido_parent($id);
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					$data['clientes']=$this->cliente->get_clientes();
					$data['formas_cobro']=$this->ccl_forma_cobro->get_ccl_formas_cobro();
					$data['productos']=$this->producto->get_cproductos_detalles();
					$data['estatus']=$this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
					if($data['cl_detalle']!=false){
						$data['renglon_adic']=count($data['cl_detalle']->all)+1;
					} else {
						$data['renglon_adic']=2;
					}

				} else if($this->uri->segment(5)=="historial_cliente"){
					$id=$this->uri->segment(6);
					$main_view=false;
					//Obtener el Limite de Credito
					$cliente=$this->cliente->get_cliente($id);
					$limite_credito=$cliente->limite_credito;
					$facturas_montos=$this->cl_factura->get_cl_facturas_cliente($id);
					if($facturas_montos==false){
						$adeudo=0;
					} else {
						$adeudo=0;
						foreach($facturas_montos->all as $row){
							$abono_fac=$this->cobro->get_cobro_total_factura_id($row->id);
							if($abono_fac==false)
		      $abono_fac=0;
							$adeudo_fac=$abono_fac->total;
							$adeudo +=$row->monto_total - $adeudo_fac;
						}
					}
					echo "<h3>Datalle del Cliente: $cliente->razon_social - $cliente->clave </h3><p>Limite de CrÃ©dito: $ $limite_credito</p><p>Adeudo Actual: $ $adeudo</p><p>Disponible: $ ". ($limite_credito-$adeudo)."</p>";

					//	      redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);

				} else if($this->uri->segment(5)=="borrar_pedido_venta"){
					$id=$this->uri->segment(6);
					$main_view=false;
					$this->db->trans_start();
					//$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
					$this->db->query("delete from cl_pedidos where id='$id'");
					$this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						echo "algo";
					}
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);


				} else if($this->uri->segment(5)=="aut_pedido_venta"){
					$main_view=false;
					$id=$this->uri->segment(6);
					$llave=$this->uri->segment(7);
					//Identificar la llave del usuario
					$usuario_id=$this->usuario->get_usuario_by_key($llave);
					//Validar que exista el usuario
					if($usuario_id!=false){
						$permisos1=$this->usuario_accion->get_permiso($accion_id, $usuario_id, $puestoid, $grupoid);
						if(substr(decbin($permisos1), 2, 1)==1){
							$u=new Cl_pedido();
							$u->get_by_id($id);
							$u->ccl_estatus_pedido_id=2;
							$u->usuario_validador_id=$usuario_id;
							$u->save();
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else {
							$main_view=true;
							//Definir la vista
							$data['principal']="error";
							$data['error_field']="No tiene permisos para autorizar el Pedido de Compra";
						}
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="La llave no esta asociada a un usuario";
					}
				} else  {
					$main_view=true;
					//Cargar los datos para el formulario
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_ventas/";
					$config['per_page'] = '50';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
						echo "Sin registros";

					}

					$u1=new Cl_pedido();
					$u1->where('espacio_fisico_id', $GLOBALS['ubicacion_id']);
					$u1->where('ccl_estatus_pedido_id', "!=3");
					$u1->get();
					$u1=$this->cl_pedido->get_cl_pedidos_list_count($GLOBALS['ubicacion_id']);
					$total_registros=$u1->total;
					$data['cl_pedidos']=$this->cl_pedido->get_cl_pedidos_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
	  }//End of
	  else if($subfuncion=="alta_promociones"){
	  	$main_view=true;
	  	//Definir la vista
	  	$data['principal']=$ruta."/".$subfuncion;
	  	$data['rows_pred']='0';
	  	$data['espacios']=$this->espacio_fisico->get_espacios_f();
                $data['familias']=$this->familia_producto->get_familias_mtrx();
                $data['subfamilias']=$this->subfamilia_producto->get_subfamilias_mtrx();
	  } else if($subfuncion=="list_promociones"){
	  	$main_view=true;
	  	//echo "shalalala";
	  	$this->load->model("promocion");
	  	if ($this->uri->segment(5) == "imprime_pdf") {

	  		$id = $this->uri->segment(6);
	  		$promocion = new promocion($id);
	  		$data['promocion'] = $promocion;
	  		$data['usuariop'] = new Usuario($promocion->usuario_id);

	  		$ef=new Espacio_fisico();
	  		$spaces=$ef->get_espacios_soleman();
	  		$data['sf']=$spaces;

	  		$this->load->view("$ruta/rep_promocion_pdf", $data);
	  	} else if($this->uri->segment(5) == "editar_promocion"){
	  		$main_view=true;
	  		$id = $this->uri->segment(6);
	  		$data['promocion']= $promocion = new promocion($id);
	  		$data['usuariop'] = new Usuario($promocion->usuario_id);
					//Definir la vista
	  		$data['principal']=$ruta."/editar_promocion";
	  		$data['rows_pred']='0';
	  		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
	  		$data['familias']=$this->familia_producto->get_cproductos_familias();
	  		$data['familia_tag']=$this->familia_producto->get_by_id($data['promocion']->cfamilia_id);
	  		$data['marca_tag']=$this->marca_producto->get_by_id($data['promocion']->cmarca_id);
	  		$data['producto_tag']=$this->producto->get_by_id($data['promocion']->cproducto_id);
	  		$data['subfamilias']=$this->subfamilia_producto->get_cproductos_subfamilias();
	  	} else if ($this->uri->segment(5) == "cancelar_promocion") {
	  		$id = $this->uri->segment(6);
	  		$this->promocion->cancelar_promocion($id);
	  		redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  	} else if ($this->uri->segment(5) == "activar_promocion") {
	  		$id = $this->uri->segment(6);
	  		$this->promocion->activar_promocion($id);
	  		redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  	} else {

	  		//Definir la vista
	  		$data['principal'] = $ruta . "/" . $subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
	  		$config['per_page'] = '20';
	  		$page = $this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if ($page > 0)
	  			$offset = $page;
	  		else if ($page == '')
	  			$offset = 0;
	  		else
	  			$offset=0;
	  		$u1 = $this->promocion->get_promociones_lista($config['per_page'], $offset);
	  		if ($u1 == false)
	  			show_error("No hay registros de promociones");
	  		$total_registros = $this->promocion->get_promociones_count();
	  		$data['promociones'] = $u1->limit(1, 2);
	  		$data['total_registros'] = $total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}
	  } else if($subfuncion=="alta_actualizacion_sucursales"){
	  	if($this->uri->segment(5)){
	  		$main_view=true;
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
	  		//REcibir datos via POST
	  		$producto_str=strtoupper($_POST['producto']);
	  		$cmarca_id=$_POST['cmarca_id'];
	  		$x=0; $efisicos=array();
	  		for($x;$x<100;$x++){
	  			if(isset($_POST['chk'.$x])){
	  				$efisicos[$x]=$_POST['chk'.$x];
	  				unset($_POST['chk'.$x]);
	  			}
	  		}
	  		if(count($efisicos)==0){
	  			show_error("Seleccione al menos una sucursal");
	  		}
	  		if(strlen($producto_str)==0 and $cmarca_id==0){
	  			show_error("Seleccione el producto o una marca que desea actualizar sus precios ");
	  		}
	  		//Obtener registros de la base de datos
	  		$this->load->model('diversos');
	  		$data['datos']=$this->diversos->precios_sucursal($producto_str, $cmarca_id, $efisicos);
	  		$data['producto_str']=$producto_str;
	  	} else {
	  		$main_view=true;
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
	  	}
            } else if($subfuncion=="list_facturas"){
                $main_view=true;
                $data = $this->list_facturas($data);
            }

	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  	unset($data); unset($GLOBALS['main_menu']);
	  } else {
	  	//redirect(base_url()."index.php/inicio/logout");
	  }
		}
	}
        
        function list_facturas($data){
            $espacio = $this->input->post('espacio_fisico');
            $cliente = $this->input->post('cliente');
            $fecha = $this->input->post('fecha');
            $folio = $this->input->post('folio');
            $serie = $this->input->post('serie');
            if($espacio != 0 || $cliente != "" || $fecha != "" || $folio != "" || $serie != ""){
                $args['espacio'] = $espacio;
                $args['cliente'] = $cliente;
                $args['fecha'] = $fecha;
                $args['folio'] = $folio;
                $args['serie'] = $serie;
                
                $data['facturas'] = $this->cl_factura->buscar_cl_facturas($args);
                $total_registros = $data['facturas']->c_rows;
                $config['per_page'] = $total_registros;
                
            } else{
                $page = $this->uri->segment(5);
                $offset = (int)$page;
                $per_page = 15;
                $data['facturas'] = $this->cl_factura->get_cl_facturas_list($offset, $per_page);                
                $total_registros = $this->cl_factura->get_cl_facturas_count();
                $config['per_page'] = '20';
            }
            
            //Definir la vista
            $data['principal']="ventas/list_facturas";
            $data['title'] = "Listado de Facturas";            
            
            $config['base_url'] = base_url() . "index.php/ventas/ventas_c/formulario/list_facturas";
	    $data['total_registros'] = $total_registros;
	    $config['total_rows'] = $total_registros;
	    $this->load->library('pagination');
            $this->pagination->initialize($config);
            
            $data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
            $data['espacios'][0] = "TODAS";
            return $data;
        }

        function actualizacion_productos(){
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $modulos_totales, $empresa_id;
		if(isset($_POST)){
			//echo "si hay post";
			//		print_r($_POST);
			//		exit();
			$data['username']=$username;
			$data['usuarioid']=$usuarioid;
			$data['modulos_totales']=$modulos_totales;
			$data['colect1']=$main_menu;
			$row=$this->usuario->get_usuario($usuarioid);
			$data['title']=$this->accion->get_title("alta_actualizacion");
			$accion_id=$this->accion->get_id('alta_actualizacion');
			$grupoid=$row->grupo_id;
			$puestoid=$row->puesto_id;
	 	$data['ruta']=$ruta;
	 	$data['controller']=$controller;
	 	$data['funcion']=$funcion;
	 	$data['subfuncion']='alta_actualizacion';
	 	$data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);

	 	if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE){
	 		//			print_r($_POST);
	 		//			echo "<br>";
	 		$select="select p.*, cp.proveedor_id from cproductos as p left join cmarcas_productos as cp on cp.id=p.cmarca_producto_id left join cproveedores as pro on pro.id=cp.proveedor_id ";

	 		if (isset($_POST['cfamilia_id']) and isset($_POST['csubfamilia_id'])){
	 			//Filtrado por Familia y Subfamilia
	 			$data['subtitle']= "Se realiza el filtrado por DEPARTAMENTO y TIPO DE CALZADO";
	 			if($_POST['cfamilia_id']==0)
	 				$where=" where p.estatus_general_id=1 and p.cfamilia_id>'0'";
	 			else
	 				$where=" where p.estatus_general_id=1 and p.cfamilia_id='{$_POST['cfamilia_id']}' ";

	 			if($_POST['csubfamilia_id']==0)
	 				$where.=" and p.csubfamilia_id>'0'";
	 			else
	 				$where.=" and p.csubfamilia_id='{$_POST['csubfamilia_id']}' ";

	 		} else if (isset($_POST['cproveedores_id'])){
	 			//Filtrado por Marca de los Productos
	 			$data['subtitle']="Se realiza el filtrado por PROVEEDOR";
	 			if($_POST['cproveedores_id']==0)
	 				$where=" where cp.proveedor_id>'0'";
	 			else
	 				$where=" where cp.proveedor_id='{$_POST['cproveedores_id']}' ";

	 		}  else if (isset($_POST['cmarca_id'])){
	 			//Filtrado por Marca de los Productos
	 			$data['subtitle']="Se realiza el filtrado por MARCA";
	 			if($_POST['cmarca_id']==0)
	 				$where=" where p.cmarca_producto_id>'0'";
	 			else
	 				$where=" where p.cmarca_producto_id='{$_POST['cmarca_id']}' ";

	 		}  else if (isset($_POST['producto_id'])){
	 			//Filtrado por Producto Especifico
	 			$data['subtitle']= "Se realiza el filtrado por ZAPATO";
	 			if($_POST['producto_id']==0)
	 				$where=" where id>'0'";
	 			else
	 				$where=" where id='{$_POST['producto_id']}' ";
	 		} else if (isset($_POST['nombre']) and (strlen(trim($_POST['nombre'])))>0 ) {
	 			$data['subtitle']= "Se realiza el filtrado por DESCRIPCION";
	 			$nombres=explode(" ",strtoupper($_POST['nombre']));
	 			$where=' where ';
	 			$total=count($nombres);
	 			$ultimo=1;
	 			foreach($nombres as $nombre){
	 				if($ultimo<$total)
	 					$where.=' descripcion like \'%'.$nombre.'%\' and ';
	 				else
	 					$where.='descripcion like \'%'.$nombre.'%\' ';

	 				$ultimo++;
	 			}
	 		} else
	 			Show_error("Favor de introducir algun valor para la busqueda");
	 		$order_by="order by descripcion asc";
	 		$query=$select.$where.$order_by;
	 		//print_r($query);
	 		$data['rows']=$this->producto->get_actualiza_productos($query);
	 		$coleccion=array();
			if($data['rows']!=false){
	 		foreach($data['rows']->all as $res){
	 			$coleccion[$res->id]['cproducto_id']=$res->id;
	 			$coleccion[$res->id]['clave']=$res->clave;
	 			$coleccion[$res->id]['descripcion']=$res->descripcion;
	 			$coleccion[$res->id]['presentacion']=$res->presentacion;
	 			$coleccion[$res->id]['precio1']=number_format($res->precio1,2,'.','');
	 			$coleccion[$res->id]['precio2']=$res->precio2;
	 			$coleccion[$res->id]['precio3']=$res->precio3;
                                $coleccion[$res->id]['status']=$res->status;
                                $coleccion[$res->id]['oferta']=$res->oferta;
	 			$costos=$this->db->query("select costo_unitario,fecha from entradas where cproductos_id=$res->id and estatus_general_id='1' and ctipo_entrada='1' order by fecha desc limit 3");
	 			$x=1;
	 			foreach ($costos->result_array() as $row){
	 				$coleccion[$res->id]['precio_compra'.$x]=$row['costo_unitario'];
	 				$coleccion[$res->id]['fecha'.$x]=$row['fecha'];
	 				$x++;
	 			}
      $bi_precios=$this->db->query("select * from bitacora_precios where cproducto_id='$res->id' order by fecha desc limit 1");
      foreach ($bi_precios->result_array() as $bi){       	                     
	 				$coleccion[$res->id]['precio3']=number_format($bi['precio'],2,'.','');
	 		}
                        }
			}
                        else
                        $data['subtitle']= "No se encotraron registros.";
				//exit();
	 		$data['coleccion']=$coleccion;
	 		//$data['rows']=$this->producto->get_actualiza_productos($query);
	 		$this->load->view("$ruta/frame_act_precios", $data);
	 		unset($data);
	 		unset($GLOBALS['main_menu']);
	 	}
		}
		else{
			echo "No hay Post";
			print_r($_POST);
			exit();
		}

	}

}
?>
