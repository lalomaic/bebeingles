<?php
class Compras_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Compras_c()
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
		// 	  $this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("proveedor");
		$this->load->model("compras_validacion");
		$this->load->model("pr_pedido");
		$this->load->model("cpr_estatus_pedido");
		$this->load->model("pr_detalle_pedido");
		$this->load->model("producto");
		$this->load->model("cpr_pago");
		$this->load->model("cpr_forma_pago");
		$this->load->model("espacio_fisico");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_tienda']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['ubicacion_tienda']);
		$GLOBALS['username']=$row->username;
		$GLOBALS['puesto']=$row->puesto_id;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}

	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id, $ubicacion_id;

		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title("$subfuncion") ." en ".$GLOBALS['ubicacion_nombre'];
		;
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
			if($subfuncion=="alta_compra"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->compras_validacion->validacion_pr_pedido();
				$data['rows_pred']="";
				$data['rows_pred']=0;
				$data['rows']=100;
				$data['renglon_adic']=0;
				$data['empresa']=$this->empresa->get_empresa($empresa_id);
				//filtro_sucursal=1
				if($GLOBALS['puesto']==6 or $GLOBALS['puesto']==7)
					$data['proveedores']=$this->proveedor->get_proveedores_tienda();
				else
					$data['proveedores']=$this->proveedor->get_proveedores_hab();
				$data['espacios']=$this->espacio_fisico->get_espacios_almacenes();
				$data['formas_pago']=$this->cpr_forma_pago->get_formas_pago();
				$data['estatus']=$this->cpr_estatus_pedido->get_cpr_estatus_all();

			}  else if($subfuncion=="list_compras"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);

				if($this->uri->segment(5)=="editar_pedido_compra"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pedido_compra";
					//Obtener Datos
					$data['rows_pred']=0;
					$data['validation']=$this->compras_validacion->validacion_pr_pedido();
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					$data['proveedores']=$this->proveedor->get_proveedores_hab();
					$data['formas_pago']=$this->cpr_forma_pago->get_formas_pago();
					$data['estatus']=$this->cpr_estatus_pedido->get_cpr_estatus_all();
					$data['pr_pedido']=$this->pr_pedido->get_pr_pedido_detalles($id);
					$data['pr_detalle']=$this->pr_detalle_pedido->get_pr_detalles_pedido_parent($id);
					if($data['pr_detalle']==false) {
						$this->db->query("update pr_pedidos set cpr_estatus_pedido_id='4' where id=$id");
						show_error('El Pedido de compra no se lleno adecuadamente, por lo tanto se ha cancelado');
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					}
					if($data['pr_detalle']!=false){
						$data['rows']=count($data['pr_detalle']->all)+300;
					} else {
						$data['rows']=300;
						$data['renglon_adic']=0;
					}

				} else if($this->uri->segment(5)=="borrar_pedido_compra"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Pr_pedido();
						$u->get_by_id($id);
						$u->cpr_estatus_pedido_id=4;
						if($u->save()) {
							//			      $this->db->query("update pr_pedidos set cpr_estatus_pedido=4 where id=$id");
							$this->db->query("delete from entradas where pr_facturas_id=$u->id");
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else
							show_error("".$u->error->string);
					}
				} else if($this->uri->segment(5)=="aut_pedido_compra"){
					$main_view=false;
					$id=$this->uri->segment(6);
					$llave=$this->uri->segment(7);
					//Identificar la llave del usuario
					$usuario_id=$this->usuario->get_usuario_by_key($llave);
					//Validar que exista el usuario
					if($usuario_id!=false){
						$permisos1=$this->usuario_accion->get_permiso($accion_id, $usuario_id, $puestoid, $grupoid);
						if(substr(decbin($permisos1), 2, 1)==1){
							$u=new Pr_pedido();
							$u->get_by_id($id);
							$u->cpr_estatus_pedido_id=2;
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
				} else if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_sucursal($id, '1'); //2= Autorizado
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '1');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_marcas($id, '1');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				}  else if($filtrado=="pedido_id" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_pedido_id($id);
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else  {

					$data['paginacion']=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras/";
					$config['per_page'] = '80';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					//CPR_ESTATUS_PEDIDO = 1 Capturado
					$this->pedidos_total();
					$data['total_registros']=$this->pr_pedido->get_pr_pedidos_listado_count(1);
					$data['pr_pedidos']=$this->pr_pedido->get_pr_pedidos_listado_capturados($offset, $config['per_page']);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;

				}

			} else if($subfuncion=="list_compras_aut"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				if($this->uri->segment(5)=="editar_pedido_compra"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pedido_compra";
					//Obtener Datos
					$data['rows_pred']=0;
					$data['validation']=$this->compras_validacion->validacion_pr_pedido();
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					$data['proveedores']=$this->proveedor->get_proveedores_hab();
					$data['formas_pago']=$this->cpr_forma_pago->get_formas_pago();
					$data['estatus']=$this->cpr_estatus_pedido->get_cpr_estatus_all();
					$data['pr_pedido']=$this->pr_pedido->get_pr_pedido_detalles($id);
					$data['pr_detalle']=$this->pr_detalle_pedido->get_pr_detalles_pedido_parent($id);
					if($data['pr_detalle']==false) {
						$this->db->query("update pr_pedidos set cpr_estatus_pedido_id='4' where id=$id");
						show_error('El Pedido de compra no se lleno adecuadamente, por lo tanto se ha cancelado');
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					}
					if($data['pr_detalle']!=false){
						$data['rows']=count($data['pr_detalle']->all)+300;
					} else {
						$data['rows']=300;
						$data['renglon_adic']=0;
					}

				} else if($this->uri->segment(5)=="borrar_pedido_compra"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Pr_pedido();
						$u->get_by_id($id);
						if($u->cpr_estatus_pedido_id==3){
							//Pedido Ingresado
							echo "<html> <script>alert(\"El pedido de compra no se puede cancelar, debido a que ya ingreso la mercancia.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras_aut';</script></html>";
						} else {
							$u->cpr_estatus_pedido_id=4;
							if($u->save()) {
								//			      $this->db->query("update pr_pedidos set cpr_estatus_pedido=4 where id=$id");
								$this->db->query("delete from entradas where pr_facturas_id=$u->id");
								redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
							} else
								show_error("".$u->error->string);
						}
					}
				} else if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_sucursal($id, '2'); //2= Autorizado
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '2');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_marcas($id, '2');
					$data['cta']=$id;
					if($data['pr_pedidos']!=false)
						$data['total_registros']=count($data['pr_pedidos']->all);
					else
						$data['total_registros']=0;
				} else if($filtrado=="pedido_id" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_pedido_id($id);
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				}  else  {
					$data['paginacion']=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras_aut/";
					$config['per_page'] = '80';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					//CPR_ESTATUS_PEDIDO = 2 Autorizado
					$this->pedidos_total();
					$data['total_registros']=$this->pr_pedido->get_pr_pedidos_listado_count(2);
					$data['pr_pedidos']=$this->pr_pedido->get_pr_pedidos_listado_autorizados($offset, $config['per_page']);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;

				}
			} else if($subfuncion=="list_pedidos_cancelados"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				if($this->uri->segment(5)=="habilitar_pedido"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Pr_pedido();
						$u->get_by_id($id);
						$u->cpr_estatus_pedido_id=1;
						if($u->save()) {
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else
							show_error("Error al habilitar el pedido");
					}
				} else if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_sucursal($id, '4'); //2= Autorizado
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '4');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_marcas($id, '4');
					$data['cta']=$id;
					if($data['pr_pedidos']!=false)
						$data['total_registros']=count($data['pr_pedidos']->all);
					else
						$data['total_registros']=0;
				} else if($filtrado=="pedido_id" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_pedido_id($id);
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				}  else  {
					$data['paginacion']=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pedidos_cancelados/";
					$config['per_page'] = '80';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					//CPR_ESTATUS_PEDIDO = 2 Autorizado
					$this->pedidos_total();
					$data['total_registros']=$this->pr_pedido->get_pr_pedidos_listado_count(4);
					$data['pr_pedidos']=$this->pr_pedido->get_pr_pedidos_listado_autorizados($offset, $config['per_page'],4);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;

				}
			}

			else if($subfuncion=="list_compras_ingresadas"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_sucursal($id, '3'); //3= Ingresado
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '3');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_marcas($id, '3');
					$data['cta']=$id;
					if($data['pr_pedidos']!=false)
						$data['total_registros']=count($data['pr_pedidos']->all);
					else
						$data['total_registros']=0;
				} else  {
					$data['paginacion']=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras_ingresadas/";
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
					//CPR_ESTATUS_PEDIDO = 2 Autorizado
					$data['total_registros']=$this->pr_pedido->get_pr_pedidos_listado_count(3);
					$data['pr_pedidos']=$this->pr_pedido->get_pr_pedidos_listado_ingresados($offset, $config['per_page']);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;

				}
			}

	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  	unset($data);
	  } else {
	  	redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}
	function pedidos_total(){
		//Funcion para actualizar el monto total de los pedidos pendientes
		$pedidos=$this->db->query("select id, monto_total from pr_pedidos where cpr_estatus_pedido_id='2' and monto_total=0");
		if($pedidos->num_rows()>0){
			$this->load->model('pr_detalle_pedido');
			foreach($pedidos->result() as $row){
				$pr=new Pr_detalle_pedido();
				$pr->select("sum(costo_total) as costo_total");
				$pr->where('pr_pedidos_id', $row->id);
				$pr->get();
				if($pr->costo_total>0)
					$this->db->query("update pr_pedidos set monto_total='$pr->costo_total' where id='$row->id'");
				else
					$this->db->query("update pr_pedidos set cpr_estatus_pedido_id='4' where id='$row->id'");

				$pr->clear();
			}
		}
	}

}//**End of Controller Class*/
?>
