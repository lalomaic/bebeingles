<?php
class Empresa_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Empresa_c()
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
		/*	  $this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');*/
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("admin_validacion");
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
			if($subfuncion=="alta_empresa"){
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->admin_validacion->validacion_empresa();
				 
			}  else if($subfuncion=="list_empresas"){

				if($this->uri->segment(5)=="editar_empresa"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$iduser=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_empresa";
					//Obtener Datos
					$data['validation']=$this->admin_validacion->validacion_empresa();
					$data['empresas']=$this->empresa->get_empresa($iduser);
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

				} else if($this->uri->segment(5)=="borrar_empresa"){
					$id=$this->uri->segment(6);
					$main_view=false;
					//Inhabilitar la Empresa
					if(substr(decbin($data['permisos']), 2, 1)==1){

						$u=new Empresa();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);


					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la empresa seleccionada";
					}
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);


				} else  {

					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_empresas/";
					$config['per_page'] = '5';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}

					$u1=new Empresa();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['empresas']=$this->empresa->get_empresas_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}

			} //Final de la acciÃÂÃÂ³n empresas
	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  } else {
	  	redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}
}
?>
