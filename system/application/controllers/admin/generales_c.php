<?php
class Generales_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	// iptables -A INPUT -p tcp -s 0/0 --sport 1024:65535 -d grupopavel.no-ip.org  --dport 5432 -m state --state NEW,ESTABLISHED -j ACCEPT
	// iptables -A OUTPUT -p tcp -s grupopavel.no-ip.org --sport 5432 -d 0/0 --dport 1024:65535 -m state --state ESTABLISHED -j ACCEPT
	//iptables -A INPUT -p tcp -d 0/0 -s 0/0 --dport 5432 -j ACCEPT
	//netstat -an | grep "LISTEN "
	function Generales_c()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		// 	  $this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		// 	  $this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		/*	  $this->assetlibpro->add_js('jquery.autocomplete.js.js');
	  $this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');*/
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		/*	  $this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
	  $this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');*/
		$this->load->model("espacio_fisico");
		$this->load->model("tipo_espacio");
		$this->load->model("subtipo_espacio");
		$this->load->model("estado");
		$this->load->model("municipio");
		$this->load->model("admin_validacion");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}


	function formulario() {
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
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE) {

			//Inicia bloque espacio_f
			if($subfuncion=="alta_espacio_f"){
				$main_view=true;
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->admin_validacion->validacion_espacio_f();
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
				$data['tipos_espacios']=$this->tipo_espacio->get_tipos_espacios();
				$data['subtipos_espacios']=$this->subtipo_espacio-> get_subtipos_espacios();


			} else if($subfuncion=="list_espacios_f"){

				if($this->uri->segment(5)=="editar_espacio_f"){
					$main_view=true;
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_espacio_f";
					//Obtener Datos
					$data['validation']=$this->admin_validacion->validacion_espacio_f();
					$data['empresas']=$this->empresa->get_empresas();
					$data['espacio_fisico']=$this->espacio_fisico->get_espacio_f($id);
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
					$data['tipos_espacios']=$this->tipo_espacio->get_tipos_espacios();
					$data['subtipos_espacios']=$this->subtipo_espacio-> get_subtipos_espacios();
					$data['estados']=$this->estado->get_estados();
					$data['municipios']=$this->municipio->get_municipios();

				} else if($this->uri->segment(5)=="borrar_espacio_f"){
					$id=$this->uri->segment(6);

					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Espacio_fisico();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();

						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la UbicaciÃÂÃÂ³n";
					}


				} else  {
					$main_view=true;
					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_espacios_f/";
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

					$u1=new Espacio_fisico();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['espacio_fisico']=$this->espacio_fisico->get_espacios_f_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final bloque espacio_f

			// Inicia bloque estado
			else if($subfuncion=="alta_estado"){
				$main_view=true;
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->admin_validacion->validacion_estado();

			} else if($subfuncion=="list_estados"){
				if($this->uri->segment(5)=="editar_estado"){
					$main_view=true;
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_estado";
					//Obtener Datos
					$data['validation']=$this->admin_validacion->validacion_estado();
					$data['estado']=$this->estado->get_estado($id);

				} else if($this->uri->segment(5)=="borrar_estado"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Estado();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Estado";
					}

				} else  {
					$main_view=true;
					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_estados/";
					$config['per_page'] = '10';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}

					$u1=new Estado();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['estados']=$this->estado->get_estados_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} // Final bloque estado

			// Inicia bloque municipio
			else if($subfuncion=="alta_municipio"){
				$main_view=true;
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->admin_validacion->validacion_municipio();
				$data['estados']=$this->estado->get_estados();


			}  else if($subfuncion=="list_municipios"){
				if($this->uri->segment(5)=="editar_municipio"){
					$main_view=true;
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_municipio";
					//Obtener Datos
					$data['validation']=$this->admin_validacion->validacion_municipio();
					$data['municipio']=$this->municipio->get_municipio($id);
					$data['estados']=$this->estado->get_estados();

				} else if($this->uri->segment(5)=="borrar_municipio"){
					$id=$this->uri->segment(6);
					$main_view=false;
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);

				} else  {
					$main_view=true;
					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_municipios/";
					$config['per_page'] = '40';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}

					$u1=new Municipio();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['municipios']=$this->municipio->get_municipios_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} // Final bloque municipio

	  if($main_view==true){

	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  } else {
	  	redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}
}
?>
