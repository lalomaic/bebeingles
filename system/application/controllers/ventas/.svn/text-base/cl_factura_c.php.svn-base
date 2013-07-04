<?php
class Cl_factura_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Cl_factura_c()
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
		$this->load->model("espacio_fisico");
		$this->load->model("ctipo_factura");
		$this->load->model("cl_factura");
		$this->load->model("cl_pedido");
		$this->load->model("salida");
		$this->load->model("cliente");
		$this->load->model("estatus_factura");
		$this->load->model("empleado");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['empresa_id']=$row->empresas_id;
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
			if($subfuncion=="alta_cl_factura"){
				$main_view=false;
				$pre=$this->uri->segment(5);
				if($pre==''){
					//Pedir el nÃÂºmero de remision y validar que exista la remision
					echo "<html><script> pedido=prompt(\"Ingrese el Id del Pedido de Venta\");";
					echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/cl_factura_c/formulario/alta_cl_factura/no_pedido/'+pedido;</script></html>";
				} else if ($pre=='no_pedido') {
					$main_view=true;
					$id=$this->uri->segment(6);
					if (strlen($id) > 0 or is_integer($id)==false) {
		    //Verificar el pedido, el id de la factura y las salidas de esa factura en la tabla salidas
		    $get_factura=$this->cl_factura->get_cl_factura_by_pedido($id);
		    $data['factura']=$get_factura;
		    if($data['factura']==false)
		    	show_error('Error 1 La Factura relacionada con el Pedido de Venta no existe');
		    $get_pedido=$this->cl_pedido->get_cl_pedido_venta($id);
		    $data['pedido']=$get_pedido;
		    if($data['pedido']==false)
		    	show_error('Error 2 Pedido de Venta no existe');

		    if ($get_pedido->ccl_estatus_pedido_id==3 and $get_pedido->ccl_tipo_pedido_id==1) {
		    	$data['principal']=$ruta."/".$subfuncion;
		    	//Traer las Salidas de la Factura
		    	$data['salidas']=$this->salida->get_salidas_by_factura($get_factura->id);
		    	if($data['salidas']==false)
		    		show_error('Error 2 El Pedido de Venta esta incompleto');
		    	$data['clientes']=$this->cliente->get_clientes();
		    	$data['agentes']=$this->empleado->get_vendedores($GLOBALS['empresa_id'], $GLOBALS['ubicacion_id']);
		    	$data['estatus']=$this->estatus_factura->get_estatus_facturas();
		    	$data['tipo_factura']=$this->ctipo_factura->get_ctipos_factura();
		    	$data['empresas']=$this->empresa->get_empresa($GLOBALS['empresa_id']);
		    	$data['rows']=count($data['salidas']->all);
		    } else {
		    	//Regresar al menu
		    	echo "<html><script>alert(\"Se ha ingresado un Id de Pedido no vÃ¡lido 1, intente de nuevo.\");". "window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		    }
					} else {
						//Regresar al menu
						echo "<html><script>alert(\"Se ha ingresado un Id de Pedido no valido 2, intente de nuevo.\")". "window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
					}
					//Obtener el formulario con los datos generales necesarios y el detalle del mismo para generar la factura
	   }



			} else if($subfunction="list_cl_facturas"){

				if($this->uri->segment(5)=="editar_cl_factura"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_cl_factura";
					//Obtener Datos
					$data['tipo_factura']=$this->ctipo_factura->get_ctipos_factura();
					$data['estatus']=$this->estatus_factura->get_estatus_facturas();
					$data['cl_factura']=$this->cl_factura->get_cl_factura($id);
					$data['clientes']=$this->cliente->get_clientes();
					$data['empresas']=$this->empresa->get_empresa($GLOBALS['empresa_id']);


				} else if($this->uri->segment(5)=="borrar_cl_factura"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$factura=$this->cl_factura->get_cl_factura($id);
						$this->db->trans_start();
						$this->db->query("delete from salidas where cl_facturas_id='$id'");
						$this->db->query("update cl_facturas set estatus_general_id=2 where id='$factura->id'");
						$this->db->query("update cl_pedidos set ccl_estatus_pedido_id=1 where id='$factura->cl_pedido_id'");
						$this->db->trans_complete();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);

					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para cancelar la Factura de Cliente";
					}

				} else  {

					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cl_facturas/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						echo "Sin registros";
					}

					$u1=$this->cl_factura->get_cl_facturas_tienda_count($GLOBALS['ubicacion_id']);
					$total_registros=$u1->total;
					$data['cl_facturas']=$this->cl_factura->get_cl_facturas_tienda($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}

			}

	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  } else {
	  	//  redirect(base_url()."index.php/inicio/logout");
	  }
		}
	}
}
?>
