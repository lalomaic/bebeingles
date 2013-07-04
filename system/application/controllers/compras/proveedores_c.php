<?php
class Proveedores_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Proveedores_c()
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
		$this->load->model("proveedor");
		$this->load->model("estatus_general");
		$this->load->model("municipio");
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
			//echo "--".$this->uri->segment(5);


			if($subfuncion=="alta_proveedor"){
		  $main_view=true;
		  //Cargar los datos para el formulario
		  //Definir la vista
		  $data['principal']=$ruta."/".$subfuncion;
		  //Obtener Datos
		  $data['municipios']=$this->municipio->get_municipios();
		  $data['estatus']=$this->estatus_general->get_estatus();

			} else if($subfuncion=="list_proveedor") {
				$main_view=true;
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);

				if($this->uri->segment(5)=="editar_proveedor"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$data['principal']=$ruta."/editar_proveedor";
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_proveedor";
					$data['proveedor']=$this->proveedor->get_by_id($id);
					$data['municipios']=$this->municipio->get_municipios();
					$data['estatus']=$this->estatus_general->get_estatus();
                                        $data['cbancarias']=$this->cuenta_bancaria->get_cuentas_bancarias_prov($id);
                                                
                                        
				}  else if($this->uri->segment(5)=="marcas_proveedor") {
					//Obtener las marcas de un proveedor dado
					$main_view=false;
					$data['title']="Listado de Marcas de Proveedor";
					$id=$this->uri->segment(6);
					$this->load->model("marca_producto");
					$data['proveedor']=$this->proveedor->get_by_id($id);
					$data['marcas']=$this->marca_producto->get_cmarcas_proveedor($id);
					$this->load->view('compras/frame_marcas', $data);
				} else if($this->uri->segment(5)=="borrar_proveedor"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Pr_pedido();
						$u=$this->proveedor->get_by_id($id);
						$u->estatus_general_id=2;
						if($u->save()) {
							//$this->db->query("update pr_pedidos set cpr_estatus_pedido=4 where id=$id");
							$this->db->query("update cmarcas_productos set estatus_general_id='2'  where proveedor_id=$u->id");
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else
							show_error("".$u->error->string);
					}
				}else if($filtrado=="buscar"){
                    $proveedor = new Proveedor();
                    $where=" where p.estatus_general_id='1' ";
                    if(isset($_POST['cproveedor_id'])){
                        $provedor=strtoupper($_POST['cproveedor_id']);
                        if($provedor>0)
                        $where=$where." and p.id=".$provedor;
                    }
                    $sql="select p.*, e.tag as estatus, u.username as usuario from cproveedores as p left join estatus_general as e on e.id=p.estatus_general_id left join usuarios as u on u.id=p.usuario_id"
                     .$where." order by p.razon_social";
                    $data['proveedores']=$proveedor->query($sql);
                    $data['total_registros']=count($data['proveedores']->all);
                    $this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_bancarias/1/buscar";
                    
                    
                    } else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '1');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else  {
					$data['paginacion']=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_proveedor/";
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
					//CPR_ESTATUS_PEDIDO = 1 Capturado
					$data['total_registros']=$this->proveedor->get_proveedores_hab_count();
					$data['proveedores']=$this->proveedor->get_proveedores_list($offset, $config['per_page']);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;

				}
			}
			if ($main_view){
				$this->load->view("ingreso", $data);
				unset($data);
			}
			/*	    else
			 redirect(base_url()."index.php/inicio/logout");*/
		}
	}
}
?>

