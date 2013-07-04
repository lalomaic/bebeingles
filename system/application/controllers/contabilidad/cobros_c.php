<?php
class Cobros_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Cobros_c()
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
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("modulo");
		$this->load->model("menu");
		$this->load->model("usuario");
		$this->load->model("cobro_validacion");
		$this->load->model("pr_factura");
		$this->load->model("usuario_accion");
		$this->load->model("forma_cobro");
		$this->load->model("tipo_cuenta_bancaria");
		$this->load->model("proveedor");
		$this->load->model("cliente");
		$this->load->model("empresa");
		$this->load->model("cuenta_bancaria");
		$this->load->model("cl_factura");
		$this->load->model("tipo_cobro");
		$this->load->model("cobro");
		$this->load->model("servicios");
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
			//echo "--".$this->uri->segment(5);

			$main_view=true;
			 
			//Inicio del Bloque Alta Forma de Cobro
			if($subfuncion=="alta_cl_forma_cobro"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;

			} else if($subfuncion=="list_cl_formas_cobro"){

				if($this->uri->segment(5)=="editar_cl_forma_cobro"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_cl_forma_cobro";
					//Obtener Datos
					$data['forma_cobro']=$this->forma_cobro->get_forma_cobro($id);

				} else if($this->uri->segment(5)=="borrar_usuario"){
					$iduser=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					//Borrar Datos del Usuario
					$u=new Usuario();
					$u->get_by_id($iduser);
					$u->delete();

					$ua= new Usuario_accion();
					$ua->where('usuario_id', $iduser);
					$ua->delete_all();
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);

				} else  {

					//Cargar los datos para el listado
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cl_formas_cobro";
					$config['per_page'] = '1';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						echo "Sin registros";
					}

					$u1=new Forma_cobro();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['formas_cobros']=$this->forma_cobro->get_formas_cobros_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final del Bloque Alta Forma de Cobro
			 
			//Inicio del Bloque Alta Tipo de Cobro
			if($subfuncion=="alta_tipo_cobro"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;

			} else if($subfuncion=="list_tipos_cobros"){

				if($this->uri->segment(5)=="editar_tipo_cobro"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_tipo_cobro";
					//Obtener Datos
					$data['tipo_cobro']=$this->tipo_cobro->get_tipo_cobro($id);

				} else if($this->uri->segment(5)=="borrar_usuario"){
					$iduser=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					//Borrar Datos del Usuario
					$u=new Usuario();
					$u->get_by_id($iduser);
					$u->delete();

					$ua= new Usuario_accion();
					$ua->where('usuario_id', $iduser);
					$ua->delete_all();
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);

				} else  {

					//Cargar los datos para el listado
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_cobros";
					$config['per_page'] = '1';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						echo "Sin registros";
					}

					$u1=new Tipo_cobro();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['tipos_cobros']=$this->tipo_cobro->get_tipos_cobros_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final del Bloque Alta Tipo de Cobro
			 
			//Inicio del Bloque Alta Cuenta Bancaria
			 
			 
			//Inicio del Bloque Alta Cobro
			else if($subfuncion=="alta_cobro"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->cobro_validacion->validacion_cobro();
				$data['clientes']=$this->cliente->get_clientes();
				//$data['facturas']=$this->cl_factura->get_cl_facturas();
				$data['formas_cobros']=$this->forma_cobro->get_formas_cobros();
				$data['cuentas_bancarias']=$this->cuenta_bancaria->get_cuentas_bancarias();
				$data['tipos_cobros']=$this->tipo_cobro->get_ctipos_cobros();

			} else if($subfuncion=="alta_servicios"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;

			}
			 else if($subfuncion=="list_cobros"){

				if($this->uri->segment(5)=="editar_cobro"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_cobro";
					//Obtener Datos
					$data['cobro']=$this->cobro->get_cobro($id);
					$data['facturas']=$this->cl_factura->get_cl_facturas();
					$data['formas_cobros']=$this->forma_cobro->get_formas_cobros();
					$data['cuentas_bancarias']=$this->cuenta_bancaria->get_cuentas_bancarias();
					$data['tipos_cobros']=$this->tipo_cobro->get_ctipos_cobros();

				} else if($this->uri->segment(5)=="borrar_cobro"){
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);
				} else  {

					//Cargar los datos para el listado
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cobros";
					$config['per_page'] = '20';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						echo "Sin registros";
					}

					$u1=new Cobro();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['cobros']=$this->cobro->get_cobros_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final del Bloque Alta Cobro
    else if($subfuncion=="list_servicios"){
				$data['pag']=1;
	  			$data['principal']=$ruta."/".$subfuncion;
	  			$filtrado=$this->uri->segment(6);
	  			$id=$this->uri->segment(7);
				if($this->uri->segment(5)=="editar_servicio"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_servicio";
	  		//Obtener Datos
	  		$data['marca']=$this->servicios->get_servicio($id);
	  		//$data['estatus']=$this->estatus_general->get_estatus();
	  		

	  	} else if($this->uri->segment(5)=="borrar_servicio"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			$u=new servicios();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id=2;
	  			$u->save();
	  			redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar la Marca de producto";
	  		}
	  	} else  {
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		$data['paginacion']=true;

	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_marcas/";
	  		$config['per_page'] = '100';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0)
	  			$offset=$page;
	  		else if ($page=='')
	  			$offset=0;
	  		else
	  			$offset=0;

	  		//$u1=new Marca_producto();
	  		$u1=$this->servicios->select("count(id) as total")->where("estatus_general_id",1)->get();
	  		$total_registros=$u1->total;
	  		$data['marca_productos']=$this->servicios->get_bancos_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  		$data['cta']=0;
	  	}
				}			
			
			
			else if($subfuncion=="list_cuentas_xcobrar"){
		  //Cargar los datos para el listado
		  $data['principal']=$ruta."/".$subfuncion;
		  //Obtener Datos
		  // load pagination class
		  $this->load->library('pagination');
		  $config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_xcobrar";
		  $config['per_page'] = '50';
		  $page=$this->uri->segment(5);
		  //Identificar el numero de pagina en el paginador si existe
		  if($page>0) {
		  	$offset=$page;
		  } else if ($page==''){
		  	$offset=0;
		  } else {
		  	$offset=0;
		  }

		  $u1=$this->cl_factura->get_cl_facturas_xcobrar_count();
		  $total_registros=$u1->total;
		  $data['pr_facturas']=$this->cl_factura->get_cl_facturas_xcobrar($offset, $config['per_page']);
		  $data['total_registros']=$total_registros;
		  $config['total_rows'] = $total_registros;
		  $this->pagination->initialize($config);

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
}
?>
