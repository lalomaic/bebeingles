<?php
class Clientes_c extends Controller {

	function Clientes_c()
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
		$this->load->model("cliente");
		$this->load->model("espacio_fisico");
		$this->load->model("estatus_general");
		$this->load->model("municipio");
                $this->load->model("ventas_validacion");
		$this->load->model("cuenta_bancaria");
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
			if($subfuncion=="alta_cliente"){
				$main_view=true;
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
                                $data['validation']=$this->ventas_validacion->validacion_cliente();
				$data['municipios']=$this->municipio->get_municipios();
				$data['estatus']=$this->estatus_general->get_estatus();

			} else if($subfuncion=="list_clientes"){

				if($this->uri->segment(5)=="editar_cliente"){
             				$main_view=true;
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_cliente";
					//Obtener Datos
					$data['cliente']=$this->cliente->get_cliente($id);
					$data['municipios']=$this->municipio->get_municipios();
					$data['estatus']=$this->estatus_general->get_estatus();
                                        $data['cbancarias']=$this->cuenta_bancaria->get_cuentas_bancarias_cliente($id);

				} else if($this->uri->segment(5)=="listado"){
					//echo 1;
					$main_view=false;
					//Definir la vista
					$data['principal']=$ruta."/frame_list_clientes";
					//Obtener Datos
					$this->load->library('pagination');
					if(isset($_POST['cliente']) && $_POST['cliente']>0){
		  		$where=" where id=$_POST[cliente]";
		  		$u1=$this->db->query("select * from cclientes $where");
		  		foreach($u1->result_array() as $row){
		  		}
		  		$data['total_registros']=1;
		  		$data['clientes']=$u1;
		  		$this->load->view("$ruta/frame_list_clientes", $data);
					} else {
						$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/formulario/list_clientes/listado/";
						$config['per_page'] = '30';
						$page=(int)$this->uri->segment(6);
						//Identificar el numero de pagina en el paginador si existe
						//echo $page;
						if($page>0) {
							$offset=$page;
						} else if ($page==0){
							$offset=0;
						} else {
							$offset=0;
						}
						$u1=new Cliente();
						$u1->get();
						$total_registros=$u1->c_rows;
							
						//echo $total_registros;
						$data['clientes']=$this->db->query("select * from cclientes order by razon_social limit $config[per_page] offset $offset");
						$data['total_registros']=$total_registros;
						$config['total_rows'] = $total_registros;
						$this->pagination->initialize($config);
						$this->load->view("$ruta/frame_list_clientes", $data);

					}
						
				} else if($this->uri->segment(5)=="borrar_cliente"){
					$id=$this->uri->segment(6);
					$main_view=false;
					$u=new Cliente();
					$u->get_by_id($id);
					$u->estatus_general_id=2;
					$u->save();
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);

				} else  {
					$main_view=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$data['clientes']=$this->db->query("select * from cclientes where estatus_general_id=1 order by razon_social");

				}

			}
	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  }
		}

	}
}
?>
