<?php
class Generales_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Generales_c()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("modulo");
		$this->load->model("menu");
		$this->load->model("usuario");
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("tipo_espacio");
		$this->load->model("estado");
		$this->load->model("municipio");
		$this->load->model("usuario_accion");
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


	function formulario() {
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
		//echo 4;
		//Validacion del arreglo del menu, usuarioid y permisos especificos
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE) {
			//echo "--".$this->uri->segment(5);
			//echo 5;
			$main_view=true;
			//Inicia bloque espacio_f
			if($subfuncion=="alta_espacio_f"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['tipos_espacios']=$this->tipo_espacio->get_tipos_espacios();
				$data['estados']=$this->estado->get_estados();
				$data['municipios']=$this->municipio->get_municipios();

			} else if($subfuncion=="rep_usuarios"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

			} else if($subfunction="list_usuarios"){

				if($this->uri->segment(5)=="editar_usuario"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$iduser=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_usuario";
					//Obtener Datos
					$data['usuario']=$this->usuario->get_usuario($iduser);
					$data['empresas']=$this->empresa->get_empresas();
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

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

					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_usuarios/";
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

					$u1=new Usuario();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['usuarios']=$this->usuario->get_usuarios_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final bloque espacio_f

			// Inicia bloque estado
			else if($subfuncion=="alta_estado"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos

			} else if($subfuncion=="rep_usuarios"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

			} else if($subfunction="list_usuarios"){

				if($this->uri->segment(5)=="editar_usuario"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$iduser=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_usuario";
					//Obtener Datos
					$data['usuario']=$this->usuario->get_usuario($iduser);
					$data['empresas']=$this->empresa->get_empresas();
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

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

					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_usuarios/";
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

					$u1=new Usuario();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['usuarios']=$this->usuario->get_usuarios_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} // Final bloque estado

			// Inicia bloque municipio
			else if($subfuncion=="alta_municipio"){
				echo 1;
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['estados']=$this->estado->get_estados();

			} else if($subfuncion=="rep_usuarios"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

			} else if($subfunction="list_usuarios"){

				if($this->uri->segment(5)=="editar_usuario"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$iduser=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_usuario";
					//Obtener Datos
					$data['usuario']=$this->usuario->get_usuario($iduser);
					$data['empresas']=$this->empresa->get_empresas();
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

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

					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_usuarios/";
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

					$u1=new Usuario();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['usuarios']=$this->usuario->get_usuarios_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} // Final bloque municipio

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
