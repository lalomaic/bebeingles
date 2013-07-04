<?php
class Gerencia_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Gerencia_c()
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
		$this->load->model("pre_traspaso");
		$this->load->model("pre_traspaso_detalle");
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
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $empresa_id, $modulos_totales;

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
			if($subfuncion=="list_pre_pedidos"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);

				if($this->uri->segment(5)=="editar_pre_pedido"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pre_pedido";
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
						$data['rows']=count($data['pr_detalle']->all)+10;
					} else {
						$data['rows']=10;
						$data['renglon_adic']=0;
					}

				} else if($this->uri->segment(5)=="clonar_pedido"){
					$id=$this->uri->segment(6);
					$main_view=false;
					$data['title']="Clonar Pre Pedido de Compra";
					$data['pedido']=$this->pr_pedido->get_pr_pedido_detalles($id);
					$data['espacios']=$this->espacio_fisico->get_espacios_almacenes();
					$this->load->view("$ruta/clonar_pedido", $data);
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
							/** Generar envío de correo electrónico*/
							//Obtener el correo electronico del proveedor
							$pro=$this->proveedor->get_by_id($u->cproveedores_id);
							$destino = $pro->email;
							$this->load->library('My_PHPMailer');
							$this->load->model("enviar_correo");
							$mail = new PHPMailer();
							$mail->IsSMTP(); // establecemos que utilizaremos SMTP
							$mail->SetFrom('pedidos.pavel@gmail.com', 'Grupo Pavel');  //Quien envía el correo
							$mail->AddReplyTo("pedidos.pavel@gmail.com","Grupo Pavel");  //A quien debe ir dirigida la respuesta
							$mail->Body      = $this->enviar_correo->pedido_html($u);
							$mail->AltBody    = "Cuerpo en texto plano";

							if(strlen($pro->email)>5) {
								//$destino="salvador.linux@gmail.com";
								//$destino="lalomaic@gmail.com";
								$mail->AddAddress($destino, utf8_decode("$pro->razon_social"));
								$destino1="pavelleon@live.com.mx";
								//$destino1="salvador.linux@hotmail.com";
								//$destino1="lalomaic@gmail.com";
								$mail->AddCC($destino1, utf8_decode("Almacén General Pavel León"));

							} else {
								//$destino1="salvador.linux@hotmail.com";
								//$destino1="lalomaic@gmail.com";
								$destino1="pavelleon@live.com.mx";
								$mail->AddAddress($destino1, utf8_decode("Almacén General Pavel León"));
							}
							//$mail->AddAttachment("images/phpmailer.gif");      // añadimos archivos adjuntos si es necesario
							//$mail->AddAttachment("images/phpmailer_mini.gif"); // tantos como queramos

							if(!$mail->Send()) {
								$data["message"] = "Error en el envío: " . $mail->ErrorInfo;
								show_error("Error en el envío: " . $mail->ErrorInfo);
							} else {
								$data["message"] = "¡Mensaje enviado correctamente!";
								//show_error("Si se envió");
							}


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
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_sucursal($id, '5'); //5= Pre pedido
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_proveedor($id, '5');
					$data['cta']=$id;
					$data['total_registros']=count($data['pr_pedidos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pr_pedidos']=$this->pr_pedido->get_pedidos_marcas($id, '5');
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
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pre_pedidos/";
					$config['per_page'] = '25';
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
					$data['total_registros']=$this->pr_pedido->get_pr_pedidos_listado_count(5);
					$data['pr_pedidos']=$this->pr_pedido->get_pr_pedidos_listado_prepedidos($offset, $config['per_page']);
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
					$data['cta']=0;
				}
			} //Final del Bloque List Pre Pedidos
			 
			else if($subfuncion=="list_pre_traspasos"){
				$data['pag']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['traspasos']=$this->lote->get_traspasos_pendientes_sucursal($id, '2'); //2= Enviado
					$data['cta']=$id;
					if($data['traspasos']!=false)
						$data['total_registros']=count($data['traspasos']->all);
					else
						$data['total_registros']=0;
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['traspasos']=$this->lote->get_traspasos_pendientes_proveedor($id, '2');
					$data['cta']=$id;
					if($data['traspasos']!=false)
						$data['total_registros']=count($data['traspasos']->all);
					else
						$data['total_registros']=0;
				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['traspasos']=$this->lote->get_traspasos_pendientes_marcas($id, '2');
					$data['cta']=$id;
					if($data['traspasos']!=false)
						$data['total_registros']=count($data['traspasos']->all);
					else
						$data['total_registros']=0;
				} else {
					//	      $id=$this->uri->segment(6);
					$data['paginacion']=true;
					//Cargar los datos para el listado de pedidos de compra en proceso
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					// load pagination class
					$data['title']="Listado de Pre Traspasos ";
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_traspasos";
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

					$total_registros=$this->pre_traspaso->get_pre_traspasos_count();
					$data['traspasos']=$this->pre_traspaso->get_pre_traspasos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
					$data['cta']=0;
				}
			}

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
				unset($data);
			}
		}

	}
}
?>
